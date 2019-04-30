<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Repository\CompanyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompanyController
 * @package App\Controller\Client
 */
class CompanyController extends CommonController
{
    /**
     * @param Request $request
     * @param CompanyRepository $repository
     * @param PaginatorInterface $paginator
     * @Route(name="client_company_search", path="/company-search", methods={"GET"}, requirements={"regionId"="\d+"})
     * @return Response
     */
    public function search(Request $request, CompanyRepository $repository, PaginatorInterface $paginator)
    {
        $query = $repository->getSearchQuery($request->query->all());

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'companies' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);
    }
}
