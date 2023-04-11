<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /** 
     * @Route ("/employe", name="app_employe")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        // On remplace le findAll by le findBy pour pouvoir donner des arguments de tri 
        $employes = $doctrine->getRepository(Employe::class)->findBy([], ["nom" => "ASC"]);
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

        /**
     * @Route("/employe/add", name="add_employe")
     */ 
    public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response { // $doctrine pour dire qu'on va intéragir avec la bdd, $employe pour dire quel type d'elmt on ajoute,
        //on créé le formulaire 
        $form = $this->createForm(EmployeType::class, $employe);
        //on gère les données ajoutées au formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) { // on vérifie que les données ont étés soumises et sont valide = ont passés les filtres.
            $employe = $form->getData(); // permet d'hydrater l'objet employe défini au départ
            $entityManager = $doctrine->getManager(); // permet d'accéder au Manager de doctrine qui possède les fonctions "persiste()" et "flush()"
            $entityManager->persist($employe); // equivalent de 'prepare' en pdo
            $entityManager->flush(); // equivalent de execute -> on tire la chasse d'eau

            return $this->redirectToRoute('app_employe'); // redirection vers la liste des employes
        }

        return $this->render('employe/add.html.twig', [
            'formAddEmploye' => $form->createView()
        ]);
    }

    /** 
     * @Route ("/employe/{id}", name="show_employe")
     */
    public function show(Employe $employe): Response {
        // définir entreprise ici... /!\
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}
