<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Service\EntityManager\Client\VideoMaterialManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VideoMaterialController
 * @package App\Controller\Client
 */
class VideoMaterialController extends ClientCommonController
{
    /**
     * @Route(name="client_video_material", path="/video-material/create", methods={"POST"})
     * @param Request $request
     * @param VideoMaterialManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function create(Request $request, VideoMaterialManager $manager)
    {
        $video = $manager->create($request->request->all());

        return $this->getResponse([
            'video' => $video
        ]);
    }
}
