<?php

namespace App\Controller\Api;

use App\Entity\AbstractEntity;
use App\Service\AbstractService;
use App\Utils\Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as ControllerAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractController extends ControllerAbstractController
{
    protected string $entity;
    protected ?AbstractEntity $registro = null;
    protected array $formParams = [];
    protected array $headers = [];
    protected array $context = [];
    protected Serializer $serializer;

    public function __construct(
        protected string $form,
        protected AbstractService $service
    )
    {
        $this->entity = $this->service->getEntityClass();
        
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    // #[Route('/route', name: 'app_route_home')]
    protected function home(): JsonResponse
    {
        $user = $this->getUser();
        $registros = $this->service->findBy([]);
        
        return $this->json(compact(
            'user',
            'registros'
        ), Response::HTTP_OK, $this->headers, $this->context);
    }

    // #[Route('/route/criar', name: 'app_route_criar', methods:['GET', 'POST'])]
    protected function criar(Request $request): JsonResponse
    {
        try{
            $user = $this->getUser();
            $this->registro = new $this->entity();
    
            if($request->getMethod() === Request::METHOD_GET){
                return $this->json(compact(
                    'user'
                ));
            }
    
            $form = $this->createForm($this->form, $this->registro);
            $form->submit(json_decode($request->getContent(), true));
    
            if(!$form->isValid()){
                throw new \Exception(Form::getErrorsForm($form), Response::HTTP_BAD_REQUEST);
            }

            $this->registro = $this->service->save($this->registro);
            return $this->json([
                'success' => true,
                'user' => $user,
                'entity' => $this->registro,
                'message' => ''
            ], Response::HTTP_CREATED, $this->headers, $this->context);
        }catch(\Throwable $t) {
            return $this->json([
                'success' => false,
                'message' => $t->getMessage()
            ], $this->validaHttpStatusCode($t), $this->headers, $this->context);
        }
    }

    // #[Route('/route/editar/{id}', name: 'app_route_editar', methods:['GET', 'PUT'])]
    protected function editar(Request $request, int $id): JsonResponse
    {
        try{
            if(!$this->existeRegistro($id)){
                throw new \Exception('Registro nao encontrado!', Response::HTTP_NOT_FOUND);
            }

            $user = $this->getUser();
            if($request->getMethod() === Request::METHOD_GET){
                $entity = $this->registro;
                return $this->json(compact(
                    'user',
                    'entity'
                ), Response::HTTP_OK, $this->headers, $this->context);
            }

            $form = $this->createForm($this->form, null);
            $form->submit(json_decode($request->getContent(), true));
    
            if(!$form->isValid()){
                throw new \Exception(Form::getErrorsForm($form), Response::HTTP_BAD_REQUEST);
            }

            $form = $this->createForm($this->form, $this->registro);
            $form->submit(json_decode($request->getContent(), true));

            $this->registro = $this->service->save($this->registro, $this->registro->getId());
            return $this->json([
                'success' => true,
                'user' => $user,
                'entity' => $this->registro,
                'message' => ''
            ], Response::HTTP_OK, $this->headers, $this->context);
        }catch(\Throwable $t) {
            return $this->json([
                'success' => false,
                'message' => $t->getMessage()
            ], $this->validaHttpStatusCode($t), $this->headers, $this->context);
        }
    }

    // #[Route('/route/remover/{id}', name: 'app_route_editar', methods:['DELETE'])]
    protected function remover(Request $request, int $id): JsonResponse
    {
        try{
            if(!$this->existeRegistro($id)){
                throw new \Exception('Registro nao encontrado!', Response::HTTP_NOT_FOUND);
            }

            $this->service->remove($this->registro);
            
            return $this->json([
                'success' => true,
                'entity' => '',
                'message' => 'Registro removido com sucesso!'
            ], Response::HTTP_OK, $this->headers, $this->context);
        }catch(\Throwable $t) {
            return $this->json([
                'success' => false,
                'message' => $t->getMessage()
            ], $this->validaHttpStatusCode($t), $this->headers, $this->context);
        }
    }

    protected function existeRegistro(int $id): bool
    {
        $registro = $this->service->find($id);

        if($registro instanceof AbstractEntity){
            $this->registro = $registro;
            return true;
        }

        return false;
    }

    protected function validaHttpStatusCode(\Throwable $exception): int
    {
        $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $getStatusCode = method_exists($exception, 'getStatusCode');

        if($getStatusCode) {
            $statusCode = $exception->getStatusCode();
        }

        if(!$getStatusCode && method_exists($exception, 'getCode')) {
            $statusCode = $exception->getCode();
        }

        if($statusCode > 99 && $statusCode < 600) {
            return $statusCode;
        }

        return JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
    }
}