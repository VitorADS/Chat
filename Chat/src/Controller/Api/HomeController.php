<?php

namespace App\Controller\Api;

use App\Service\PessoaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


final class HomeController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct('teste', new PessoaService($entityManager));
    }

    #[Route('/websocket', name: 'app_home_ws')]
    public function index(): JsonResponse
    {
        
    }
}
