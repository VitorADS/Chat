<?php

namespace App\Controller\View;

use App\Entity\AbstractEntity;
use App\Service\AbstractService;
use App\Utils\Form;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as ControllerAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends ControllerAbstractController
{
    protected string $entity;
    protected ?AbstractEntity $registro = null;
    protected array $viewParams = [];
    protected array $formParams = [];

    public function __construct(
        protected string $form,
        protected AbstractService $service,
        protected string $view,
    )
    {
        $this->entity = $this->service->getEntityClass();
    }

    // #[Route('/view', name: 'app_view_home')]
    protected function index(): Response
    {
        $user = $this->getUser();
        $registros = $this->service->findBy([]);
        
        return $this->render("{$this->view}/index.html.twig", compact(
            'user',
            'registros'
        ));
    }

    // #[Route('/view/criar', name: 'app_view_criar', methods:['GET', 'POST'])]
    protected function create(Request $request): Response
    {
        $user = $this->getUser();

        if(!$this->registro instanceof AbstractEntity){
            $this->registro = new $this->entity();
        }
    
        $form = $this->createForm($this->form, $this->registro, $this->formParams);
        $this->viewParams['user'] = $user;
        $this->viewParams['form'] = $form;

        if($request->getMethod() === Request::METHOD_GET){
            return $this->render("{$this->view}/create.html.twig", $this->viewParams);
        }

        $form->handleRequest($request);

        if($form->isValid()){
            $entity = $this->service->save($this->registro);
            $this->addFlash('success', 'Registro criado com sucesso!');
            return $this->redirectToRoute("app_{$this->view}_editar", ['id' => $entity->getId()]);
        }

        $error = Form::getErrorsForm($form);
        $this->addFlash('danger', $error);
        return $this->render("{$this->view}/create.html.twig", $this->viewParams);
    }

    // #[Route('/view/editar/{id}', name: 'app_view_editar', methods:['GET', 'POST'])]
    protected function editar(Request $request, int $id): Response
    {
        if(!$this->existeRegistro($id)){
            $this->addFlash('danger', 'Registro nao encontrado!');
        } else {
            $this->formParams['editar'] = true;
            $user = $this->getUser();
            $form = $this->createForm($this->form, $this->registro, $this->formParams);
            $this->viewParams['user'] = $user;
            $this->viewParams['form'] = $form;
            $this->viewParams['registro'] = $this->registro;

            if($request->getMethod() === Request::METHOD_GET){
                return $this->render("{$this->view}/editar.html.twig", $this->viewParams);
            }

            $form->handleRequest($request);

            if($form->isValid()){
                $this->registro = $this->service->save($this->registro, $id);
                $this->addFlash('success', 'Registro editado com sucesso');
                return $this->redirectToRoute("app_{$this->view}_home");
            }

            $error = Form::getErrorsForm($form);
            $this->addFlash('danger', $error);
            return $this->render("{$this->view}/editar.html.twig", $this->viewParams);
        }

        return $this->redirectToRoute("app_{$this->view}_home");
    }

    // #[Route('/view/remover/{id}', name: 'app_view_editar', methods:['POST'])]
    protected function remove(Request $request, int $id): Response
    {
        if(!$this->existeRegistro($id)){
            $this->addFlash('danger', 'Registro nao encontrado!');
        } else {
            try{
                $this->service->remove($this->registro);
                $this->addFlash('success', 'Registro removido!');
            }catch(Exception $e){
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->redirectToRoute("app_{$this->view}_home");
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
}