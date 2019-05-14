<?php

namespace App\Controller\Admin\Account;

use App\Controller\Admin\AdminCommonController;
use App\Entity\User;
use App\Service\EntityManager\Admin\CommonAccountManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller\Admin\Account
 */
class AccountController extends AdminCommonController
{
    /**
     * @param Request $request
     * @param CommonAccountManager $manager
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route(name="admin_account_search", path="/search", methods={"GET"})
     */
    public function search(Request $request, CommonAccountManager $manager, PaginatorInterface $paginator)
    {
        $searchCriteria = $request->query->all();

        $query = $manager->search($searchCriteria);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'accounts' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);

    }

    /**
     * @param User $account
     * @param CommonAccountManager $manager
     *
     * @param Request $request
     * @Route(name="admin_account_reset_password", path="/reset-password/{id}", methods={"PUT"}, requirements={"id"="\d+"})
     * @ParamConverter("account", class="App\Entity\User")
     * @return Response
     */
    public function resetPassword(User $account, CommonAccountManager $manager, Request $request)
    {
        $account = $manager->resetPassword($account, $request->request->all());

        return $this->getResponse([
            'account' => $account
        ]);
    }

    /**
     * @param User $account
     * @Route(name="admin_account_get", path="/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("account", class="App\Entity\User")
     * @return Response
     */
    public function details(User $account)
    {
        return $this->getResponse([
            'account' => $account
        ], Response::HTTP_OK, [], [
            'admin_details'
        ]);
    }

    protected function createAccount(Request $request, CommonAccountManager $manager)
    {
        $account = $manager->create($request->request->all());

        return $this->getResponse([
            'account' => $account
        ], Response::HTTP_CREATED);
    }

    protected function updateAccount(User $account, CommonAccountManager $manager, Request $request)
    {
        $account = $manager->update($account, $request->request->all());

        return $this->getResponse([
            'account' => $account
        ]);
    }
}