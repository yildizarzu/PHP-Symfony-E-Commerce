<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use App\Repository\ShopcartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("home/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,CategoriesRepository $categoriesRepository): Response
    {
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('home/security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'contenttitle' => "Giriş Yap",
                'categories' => $categoriesRepository->findAll(),
            ]);
    }



}
