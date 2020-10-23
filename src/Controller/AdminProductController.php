<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Service\FileUploader;
use App\Repository\ProductRepository;
use App\Repository\PicturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/produits")
 * 
 * Partie admin de ProductController, donc ici on retrouve les méthodes de création, modififcation et suppression des produits.
 * Ainsi qu'une nouvelle liste simplifié, pour les produits.
 * 
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MARCHAND')")
 * 
 */
class AdminProductController extends AbstractController
{
    /**
     * @Route("/liste", name="admin_products")
     */
    public function index(ProductRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        // KNP paginator
        $limit = 5;
        $page = $request->query->getInt('page', 1);
        $offset = ($page*$limit)-$limit;
        // on limite la requête aux données nécessaires
        $products = $paginator->paginate($repo->findBy([],[], $limit, $offset), 1, $limit);
        // On donne les vraies infos à paginator
        $products->setTotalItemCount(
            $repo->createQueryBuilder('p')->select('count(p.id)')
                ->getQuery()->getSingleScalarResult()
        );
        $products->setCurrentPageNumber($page);
        
        // si pas de données sur la page courante on retourne en page 1
        if(sizeof($products) === 0 && $page > 1) {
            // redirection vers la page 1
            return $this->redirectToRoute('product_list');
        }

        return $this->render('admin/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/ajouter", name="admin_product_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader) : Response
    {
        $product = new Product;
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * On récupère la valeur de l'input avec le name profilPicture envoyé ici en POST.
             */
            $brochureFile = $form->get('picture')->getData();

            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $product->setPicture("images/".$brochureFileName);
            }

            // j'envoie l'agent courant dans le setter de l'agent.
            $product->setCreatedAt(new \Datetime);
            // Sauvegarde et envoie des données
            $entityManager->persist($product);

            $entityManager->flush();

            $this->addFlash("success", "Produit bien ajouté");
            return $this->redirectToRoute("products");
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modification/{slug}", name="admin_product_edit")
     * 
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, FileUploader $fileUploader) : Response
    {
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * On récupère la valeur de l'input avec le name profilPicture envoyé ici en POST.
             */
            $brochureFile = $form->get('picture')->getData();

            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $product->setPicture("images/".$brochureFileName);
            }

            // j'envoie l'agent courant dans le setter de l'agent.
            // Sauvegarde et envoie des données
            $entityManager->persist($product);

            $entityManager->flush();

            $this->addFlash("success", "Produit bien ajouté");
            return $this->redirectToRoute("products");
        }

        return $this->render('admin/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("supprimer/{slug}", name="admin_product_delete", methods={"DELETE"})
     * 
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager)
    {
        if ($this->isCsrfTokenValid('delete'.$product->getSlug(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_products');
    }

}