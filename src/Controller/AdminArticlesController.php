<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/articles")
 * 
 * @Security("is_granted('ROLE_ADMIN')")
 * 
 */
class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/liste", name="admin_articles")
     */
    public function indexArticles(ArticleRepository $articleRepository)
    {
        return $this->render('admin/articles/articleIndex.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/liste/{slug}", name="admin_validate_article", methods={"POST"})
     */
    public function validateArticle(Request $request, Article $article, EntityManagerInterface $entityManager)
    {
        // if($request->isXmlHttpRequest() != true) {
        //     return new JsonResponse(['statut' => 'error', 'error' => 'Accès non autorisé.']);
        // }

        if ($article->getIsPublished() == true) {
            $article->setIsPublished(false);
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", "Article désactivé avec succès");
            return $this->redirectToRoute("admin_articles");
            // return new JsonResponse([
            //     'status' => 'ok',
            //     'msg' => 'Article désactivé avec succès',
            //     'id' => $article->getId()
            // ]);
        } else {
            $article->setIsPublished(true);
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", "Article activé avec succès");
            return $this->redirectToRoute("admin_articles");

            // return new JsonResponse([
            //     'status' => 'ok',
            //     'msg' => 'Article activé avec succès',
            //     'id' => $article->getId()
            // ]);
        }
    }
}