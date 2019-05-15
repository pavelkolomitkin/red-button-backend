<?php


namespace App\Controller\Admin;


use App\Controller\CommonController;

class AdminCommonController extends CommonController
{
    protected function getDefaultSerializeGroups()
    {
        return array_merge(parent::getDefaultSerializeGroups(), [
            'admin_default'
        ]);
    }
}