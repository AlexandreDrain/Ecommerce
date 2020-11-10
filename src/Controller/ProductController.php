<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductReview;
use App\Form\ProductFormType;
use App\Service\FileUploader;
use App\Form\ProductReviewFormType;
use App\Repository\ProductRepository;
use App\Entity\ResponseToProductReview;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductReviewRepository;
use App\Form\ResponseToProductReviewFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ResponseToProductReviewRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/Répondre/{slug}/commentaire/{productReview_id}", name="comment_response")
     * @ParamConverter("productReview", options={"mapping": {"productReview_id": "id"}})
     */
    public function ResponseToAComment(Request $request, Product $product, ProductReview $productReview, ?UserInterface $user, EntityManagerInterface $entityManager)gi
    {
        if($request->isXmlHttpRequest() != true) {
            return new JsonResponse(['statut' => 'error', 'error' => 'Accès non autorisé.']);
        }

        $responseToProductReview = new ResponseToProductReview;
        $form = $this->createForm(ResponseToProductReviewFormType::class, $responseToProductReview);
        $form->handleRequest($request);

        if ($user) {
            if ($form->isSubmitted() && $form->isValid()) {
                // j'envoie l'agent courant dans le setter de l'agent.
                $responseToProductReview->setWritedAt(new \Datetime);
                $responseToProductReview->setAuthor($user);
                $responseToProductReview->setRespondTo($productReview);
                // Sauvegarde et envoie des données
                $entityManager->persist($responseToProductReview);
                $entityManager->flush();

                //return new JsonResponse(['statut' => 'ok', 'url' => $_SERVER['HTTP_REFERER']]);
                return new JsonResponse([
                    'statut'    => 'ok'
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
    public function show(Request $request, Product $product, ProductReviewRepository $productReviewRepository, ResponseToProductReviewRepository $responseToProductReviewRepository)
    {
        $productReview = new ProductReview;
        $form = $this->createForm(ProductReviewFormType::class, $productReview);
        $form->handleRequest($request);

        $responseToProductReview = new ResponseToProductReview;
        $formResponse = $this->createForm(ResponseToProductReviewFormType::class, $responseToProductReview);
        $formResponse->handleRequest($request);

        return $this->render('product/details.html.twig', [
            'product' => $product,
            'productReviews' => $productReviewRepository->findBy(['product' => $product],['id' => 'DESC']),
            'responseToProductReviews' => $responseToProductReviewRepository->findAll(),
            'form' => $form->createView(),
            'formResponse' => $formResponse->createView(),
        ]);
    }

}