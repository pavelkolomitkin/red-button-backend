<?php

namespace App\Controller\Admin\Account;

use App\Entity\CompanyRepresentativeUser;
use App\Service\EntityManager\Admin\CompanyRepresentativeAccountManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompanyRepresentativeAccountController
 * @package App\Controller\Admin
 */
class CompanyRepresentativeAccountController extends AccountController
{
    /**
     * @param Request $request
     * @param CompanyRepresentativeAccountManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     *
     * @Route(name="admin_account_company_representative_create", path="/company-representative/create", methods={"POST"})
     */
    public function create(Request $request, CompanyRepresentativeAccountManager $manager)
    {
        return $this->createAccount($request, $manager);
    }

    /**
     * @param CompanyRepresentativeUser $account
     * @param CompanyRepresentativeAccountManager $manager
     * @param Request $request
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     *
     * @Route(name="admin_account_company_representative_edit", path="/company-representative/{id}", methods={"PUT"})
     * @ParamConverter("account", class="App\Entity\CompanyRepresentativeUser")
     */
    public function update(CompanyRepresentativeUser $account, CompanyRepresentativeAccountManager $manager, Request $request)
    {
        return $this->updateAccount($account, $manager, $request);
    }
}