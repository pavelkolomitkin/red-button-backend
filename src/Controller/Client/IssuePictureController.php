<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Service\EntityManager\Client\IssuePictureManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IssuePictureController
 * @package App\Controller\Client
 */
class IssuePictureController extends CommonController
{
    /**
     * @param Request $request
     * @param IssuePictureManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     *
     * @Route(name="issue_picture_create", path="/issue-picture/create", methods={"POST"})
     */
    public function create(Request $request, IssuePictureManager $manager)
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
