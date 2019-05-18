<?php

namespace App\Controller\Client;

use App\Controller\CommonController;

class ClientCommonController extends CommonController
{
    protected function getDefaultSearchDatePeriod()
    {
        return [
            'startDate' => new \DateTime('-1 month'),
            'endDate' => new \DateTime('now')
        ];
    }

    protected function getDefaultSerializeGroups()
    {
        return array_merge(parent::getDefaultSerializeGroups(), ['client_default']);
    }
}