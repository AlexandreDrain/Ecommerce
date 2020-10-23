<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * 
 * @Route("/categories")
 * 
 * @Security("is_granted('ROLE_ADMIN')")
 * 
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/liste", name="categorys")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}