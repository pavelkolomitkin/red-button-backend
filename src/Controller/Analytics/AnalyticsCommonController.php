<?php

namespace App\Controller\Analytics;

use App\Controller\CommonController;

/**
 * Class AnalyticsCommonController
 * @package App\Controller\Analytics
 */
class AnalyticsCommonController extends CommonController
{
    protected function getDefaultSerializeGroups()
    {
        return array_merge(
            parent::getDefaultSerializeGroups(), [
                'analyst_default'
            ]);
    }
}