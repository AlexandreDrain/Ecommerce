<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
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
    public function new(Request $request, EntityManagerInterface $entityManager, ?UserInterface $user)
    {
        $article = new Article;
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($user);
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
    public function edit(Request $request, EntityManagerInterface $entityManager, Article $article, ?UserInterface $user)
    {
        if ($user != $article->getAuthor()) {
            $this->addFlash("danger", "Vous n'êtes pas le créateur de cet article, vous ne pouvez donc pas le modifier");
            return $this->redirectToRoute();
        }

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setIsPublished(false);
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", "Article bien modifié, en attente de validation par un modérateur");
            return $this->redirectToRoute("articles");

        } else {
            $this->addFlash("warning", "Valider la modification de l'article entraînera de nouveau la vérification de celui-ci, jusqu'à que ce soit fait l'article ne sera plus visible");
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}