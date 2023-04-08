<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\Persistence\ManagerRegistry;
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
        // SELECT * FROM employe ORDER BY nom ASC
        $employes = $doctrine->getRepository(Employe::class)->findBy([], ["nom" => "ASC"]);
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
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
