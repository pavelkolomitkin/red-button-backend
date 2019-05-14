<?php

namespace App\Controller\Admin\Account;

use App\Entity\AnalystUser;
use App\Service\EntityManager\Admin\AnalystAccountManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnalystAccountController
 * @package App\Controller\Admin
 */
class AnalystAccountController extends AccountController
{
    /**
     * @param Request $request
     * @param AnalystAccountManager $manager
     *
     * @Route(name="admin_account_analyst_create", path="/analyst/create", methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, AnalystAccountManager $manager)
    {
        return $this->createAccount($request, $manager);
    }

    /**
     * @param AnalystUser $account
     * @param AnalystAccountManager $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(name="admin_account_analyst_edit", path="/analyst/{id}", methods={"PUT"})
     * @ParamConverter("account", class="App\Entity\AnalystUser")
     */
    public function update(AnalystUser $account, AnalystAccountManager $manager, Request $request)
    {
        return $this->updateAccount($account, $manager, $request);
    }
}