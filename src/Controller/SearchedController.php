<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchedController extends AbstractController
{
    /**
     * @Route("/produit_recherché", name="searched",  methods={"GET"})
     */
    public function index(ProductRepository $productRepository, Request $request)
    {
        if (null !== $request->query->get("q")) {
            if (!empty($request->query->get("q"))) {
                $searched = $request->query->get("q");
            } else {
                $this->addFlash("warning", "Veuillez entrer une recherche valide");
                return $this->redirectToRoute("products");
            }
        } else {
            $this->addFlash("warning", "error");
            return $this->redirectToRoute("products");
        }

        return $this->render('searched/index.html.twig', [
            'products' => $productRepository->findProductByWhatSearched($searched)
        ]);
    }
}