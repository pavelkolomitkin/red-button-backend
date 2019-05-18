<?php


namespace App\Controller\Company;

use App\Controller\CommonController;
use App\Entity\Company;

/**
 * Class CompanyCommonController
 * @package App\Controller\Company
 */
class CompanyCommonController extends CommonController
{
    /**
     * @return Company
     */
    protected function getCompany()
    {
        return $this->getUser()->getCompany();
    }

    protected function getDefaultSerializeGroups()
    {
        return array_merge(parent::getDefaultSerializeGroups(), [
            'company_default'
        ]);
    }
}