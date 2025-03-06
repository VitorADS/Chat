<?php

namespace App\Controller\View;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function __construct(
        UserService $service
    )
    {
        parent::__construct('asd', $service, 'user');
    }

    #[Route('/', name: 'app_user_login', methods:['GET', 'POST'])]
    public function login(AuthenticationUtils $auth): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $error = $auth->getLastAuthenticationError();
        $lastUserName = $auth->getLastUsername();

        if($error instanceof BadCredentialsException){
            $this->addFlash('danger', 'E-mail e/ou senha incorretos!');
        }

        return $this->render($this->view . '/index.html.twig', compact(
            'lastUserName'
        ));
    }

    #[Route('/home', name: 'app_user_home', methods:['GET'])]
    public function home(): Response
    {
        return $this->render($this->view . '/home.html.twig');
    }
}
