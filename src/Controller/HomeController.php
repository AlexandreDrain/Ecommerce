<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(?UserInterface $user)
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user
        ]);
    }
}
