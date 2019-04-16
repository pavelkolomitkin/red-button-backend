<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Entity\Complaint;
use App\Service\EntityManager\Client\ComplaintManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ComplaintController
 * @package App\Controller\Client
 */
class ComplaintController extends CommonController
{
    /**
     * @param Request $request
     * @param ComplaintManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="complaint_create", path="/complaint", methods={"POST"})
     */
    public function create(Request $request, ComplaintManager $manager)
    {
        $complaint = $manager->create($request->request->all());

        return $this->getResponse([
            'complaint' => $complaint
        ], Response::HTTP_CREATED);
    }

    public function update(Complaint $complaint, ComplaintManager $manager, Request $request)
    {

    }
}
