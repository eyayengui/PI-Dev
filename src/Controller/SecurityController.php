<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $this->getUser();


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/afterlogin', name: 'afterlogin')]
    public function test(UserRepository $repo, Security $security): Response
    {
        
        if ($security->isGranted('ROLE_ADMIN')) 
        {
            return $this->redirectToRoute('app_admin_index');
        }
        if ($security->isGranted('ROLE_USER')) 
        {
            return $this->render('base-index.html.twig');
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/error', name: 'app_error')]
    public function notFound()
    {
        return $this->render('security/404.html.twig');
    }
}
