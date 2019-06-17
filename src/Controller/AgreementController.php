<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AgreementController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(name="agreement_index", path="/user-agreement", methods={"GET"})
     */
    public function index()
    {
        return $this->render('user-agreement.html.twig');
    }
}