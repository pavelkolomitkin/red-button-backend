<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

abstract class CommonController extends AbstractFOSRestController
{
    const SERIALIZE_GROUP_DEFAULT = 'default';

    const SERIALIZE_GROUP_PRIVATE = 'private';

    const SERIALIZE_GROUP_LIST = 'list';

    const SERIALIZE_GROUP_DETAILS = 'details';

    /**
     * @param $data
     * @param int $statusCode
     * @param array $headers
     * @param array $serializeGroups
     * @return Response
     */
    protected function getResponse($data = null, $statusCode = Response::HTTP_OK, array $headers = [], array $serializeGroups = [])
    {
        $view = $this->view($data, $statusCode, $headers);

        $defaultSerializeGroups = $this->getDefaultSerializeGroups();
        $groups = array_merge($defaultSerializeGroups, $serializeGroups);

        $context = $view->getContext();
        $context
            ->setGroups($groups)
            ->disableMaxDepth();

        return $this->handleView($view);
    }

    protected function getDefaultSerializeGroups()
    {
        return [self::SERIALIZE_GROUP_DEFAULT];
    }

    protected function getAllowedSearchParameters()
    {
        return [];
    }

    protected function filterSearchParameters(array $params)
    {
        $result = array_intersect_key($params, array_flip($this->getAllowedSearchParameters()));

        return $result;
    }
}
