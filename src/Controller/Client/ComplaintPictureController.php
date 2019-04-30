<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Service\EntityManager\Client\ComplaintPictureManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ComplaintPictureController
 * @package App\Controller\Client
 */
class ComplaintPictureController extends CommonController
{
    /**
     * @param Request $request
     * @param ComplaintPictureManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     *
     * @Route(name="client_complaint_picture_create", path="/complaint-picture/create", methods={"POST"})
     */
    public function create(Request $request, ComplaintPictureManager $manager)
    {
        $result = $manager->create($request->files->all());

        return $this->getResponse(
            [
                'picture' => $result
            ],
            Response::HTTP_CREATED
        );
    }

}
