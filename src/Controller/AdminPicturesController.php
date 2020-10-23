<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Service\FileUploader;
use App\Form\PicturesFormType;
use App\Repository\PicturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/pictures")
 * 
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MARCHAND')")
 * 
 */
class AdminPicturesController extends AbstractController
{

    /**
     * @Route("/liste", name="admin_pictures")
     */
    public function indexPictures(PicturesRepository $picturesRepository)
    {
        return $this->render('admin/pictures/pictureIndex.html.twig', [
            'pictures' => $picturesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_picture_new")
     */
    public function newPictures(Request $request, PicturesRepository $picturesRepository, Fileuploader $fileUploader, EntityManagerInterface $entityManager) : Response
    {
        $pictures = new Pictures;
        $form = $this->createForm(PicturesFormType::class, $pictures);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * On récupère la valeur de l'input avec le name profilPicture envoyé ici en POST.
             */
            $brochureFile = $form->get('nameOfPicture')->getData();

            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $pictures->setNameOfPicture($brochureFileName);
            }

            // j'envoie l'agent courant dans le setter de l'agent.
            // Sauvegarde et envoie des données
            $entityManager->persist($pictures);
            $entityManager->flush();

            $this->addFlash("success", "Image bien ajouté");
            return $this->redirectToRoute("admin_pictures");
        }

        return $this->render('admin/pictures/newPictures.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}