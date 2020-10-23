<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/article")
 * 
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/tout_les_articles", name="articles")
     */
    public function index(ArticleRepository $articleRepository)
    {
        return $this->render('article/index.html.twig', [
            // 'articles' => $articleRepository->findby(['isPublished' => true],['id', 'DESC']),
            'articles' => $articleRepository->findby(['isPublished' => true]),
        ]);
    }

    /**
     * @Route("/nouvel_article", name="article_new")
     * 
     * @Security("is_granted('ROLE_REPORTER')")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $article = new Article;
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setPublishedAt(new \Datetime);
            $article->setisPublished(false);
            // Envoyer un email a un modérateur pour qu'il active ou non l'article.
            $article->setPublishedAt(new \Datetime);
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", "Article bien créé, en attente de validation par un modérateur");
            return $this->redirectToRoute("articles");
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/détails/{slug}", name="article_show")
     * 
     */
    public function show(Article $article)
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/modifier/{slug}", name="article_edit")
     * 
     * @Security("is_granted('ROLE_REPORTER')")
     * 
     */
    public function edit(Request $request, EntityManagerInterface $entityManager)
    {

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", "Article bien crée, en attente de validation par un modérateur");
            return $this->redirectToRoute("articles");

        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}