<?php

namespace App\Controller;

use App\Form\EntrepriseType;
use App\Entity\Entreprise;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EntrepriseController extends AbstractController
{
    /**
     * @Route ("/entreprise", name="app_entreprise")
     */
    public function index(ManagerRegistry $doctrine): Response //on passe en param le ManagerRegistry pour pouvoir utiliser $doctrine
    {
        $entreprises = $doctrine->getRepository(Entreprise::class)->findAll();
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    /**
     * @Route("/entreprise/add", name="add_entreprise")
     */ 
    public function add(ManagerRegistry $doctrine, Entreprise $entreprise = null, Request $request): Response { // $doctrine pour dire qu'on va intéragir avec la bdd, $entreprise pour dire quel type d'elmt on ajoute,
        //on créé le formulaire 
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        //on gère les données ajoutées au formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) { // on vérifie que les données ont étés soumises et sont valide = ont passés les filtres.
            $entreprise = $form->getData(); // permet d'hydrater l'objet entreprise défini au départ
            $entityManager = $doctrine->getManager(); // permet d'accéder au Manager de doctrine qui possède les fonctions "persiste()" et "flush()"
            $entityManager->persist($entreprise); // equivalent de 'prepare' en pdo
            $entityManager->flush(); // equivalent de execute -> on tire la chasse d'eau

            return $this->redirectToRoute('app_entreprise'); // redirection vers la liste des entreprises
        }

        return $this->render('Entreprise/add.html.twig', [
            'formAddEntreprise' => $form->createView()
        ]);
    }

    // La fonction permettant d'afficher le détail se met a la fin par bonne pratique ->

    /** 
     * @Route ("/entreprise/{id}", name="show_entreprise")
     */
    public function show(Entreprise $entreprise): Response {
        // définir entreprise ici... /!\
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }
}
