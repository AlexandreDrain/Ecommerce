<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductReview;
use App\Form\ProductFormType;
use App\Service\FileUploader;
use App\Form\ProductReviewFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/produits")
 * 
 * Cette classe contient les méthodes qui renvoient les vues ou execute le code accessible au utilisateurs lambda,
 * la création, modification et supprssion de produits se fait coté admin dans AdminProductController.
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/liste", name="products")
     */
    public function index(ProductRepository $productRepository)
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy(["isAvailable" => true]),
        ]);
    }

    /**
     * @Route("/ajouter/{slug}/commentaire", name="comment")
     * 
     * L'accés est limité au utilisateurs conencté mais je gère cette accès depuis le js en ajax avec ce que je lui envoi depuis ce controller.
     */
    public function newProductReview(Request $request, Product $product, ?UserInterface $user, ProductReviewRepository $productReviewRepository, EntityManagerInterface $entityManager)
    {

        if($request->isXmlHttpRequest() != true) {
            return new JsonResponse(['statut' => 'error', 'error' => 'Accès non autorisé.']);
        }

        $productReview = new ProductReview;
        $form = $this->createForm(ProductReviewFormType::class, $productReview);
        $form->handleRequest($request);

        if ($user) {
            if ($form->isSubmitted() && $form->isValid()) {
                // j'envoie l'agent courant dans le setter de l'agent.
                $productReview->setWritedAt(new \Datetime);
                $productReview->setProduct($product);
                $productReview->setAuthor($user);
                // Sauvegarde et envoie des données
                $entityManager->persist($productReview);
                $entityManager->flush();

                //return new JsonResponse(['statut' => 'ok', 'url' => $_SERVER['HTTP_REFERER']]);
                return new JsonResponse([
                    'statut'    => 'ok', 
                    'date'      => date('Y-m-d', date_timestamp_get($productReview->getWritedAt())),
                    'author'    => (string) $user,
                    'comment'   => $productReview->getContent()
                ]);
            }
        } else {
            return new JsonResponse([
                'statut'    => 'notConnected',
            ]);
        }

    }

    /**
     * @Route("/details/{slug}", name="product_show")
     */
    public function show(Request $request, Product $product, ProductReviewRepository $productReviewRepository)
    {
        $productReview = new ProductReview;
        $form = $this->createForm(ProductReviewFormType::class, $productReview);
        $form->handleRequest($request);

        return $this->render('product/details.html.twig', [
            'product' => $product,
            'productReviews' => $productReviewRepository->findBy(['product' => $product],['id' => 'DESC']),
            'form' => $form->createView()
        ]);
    }

}