<?php

namespace App\Controller;

use App\Entity\Taches;
use App\Form\TacheType;
use App\Repository\TachesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(TachesRepository $repo)
    {

        $taches = $repo->findAll();

        return $this->render('index.html.twig', [
            'taches' => $taches
        ]);
    }

    /**
     * @Route("/new", name="new")
     */ 
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $taches = new Taches();

        $form = $this->createForm(TacheType::class, $taches);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($taches);

            $manager->flush();

            $this->addFlash(
                'success',
                "Votre tâche est bien enregistrée"
            );

            return $this->redirectToRoute('accueil');

        }

        return $this->render('new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(EntityManagerInterface $manager, Taches $taches)
    {

        $manager->remove($taches);
        $manager->flush();

        $this->addFlash(
            'danger',
            "La tâche a été supprimé"
        );

        return $this->redirectToRoute('accueil');

    }


    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, EntityManagerInterface $manager, Taches $taches) 
    {

        $form = $this->createForm(TacheType::class, $taches);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $taches = $form->getData();

            $manager->flush();

            $this->addFlash(
                'success',
                "Votre tâche a été modifié"
            );

            return $this->redirectToRoute('accueil');

        }

        return $this->render('new.html.twig', [
            "form" => $form->createView()
        ]);


    }


}
