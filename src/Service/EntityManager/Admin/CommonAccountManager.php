<?php

namespace App\Service\EntityManager\Admin;

use App\Entity\User;
use App\Form\Admin\AccountResetPasswordType;
use App\Service\EntityManager\CommonEntityManager;
use Symfony\Component\Form\FormInterface;

class CommonAccountManager extends CommonEntityManager
{
    public function resetPassword(User $user, array $data)
    {
        return $this->update($user, $data);
    }

    protected function getCreationForm(): FormInterface
    {
        throw new \Exception('You can not create this entity');
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(AccountResetPasswordType::class);
    }

    public function search(array $criteria)
    {
        $builder = $this->getSearchQueryBuilder($criteria);

        $repository = $this->entityManager->getRepository('App\Entity\CompanyRepresentativeUser');
        $repository->handleCompanyParameter($builder, $criteria);

        $repository = $this->entityManager->getRepository('App\Entity\User');
        $repository->handleEmailParameter($builder, $criteria);
        $repository->handleFullNameParameter($builder, $criteria);

        $builder->orderBy('user.createdAt', 'DESC');

        return $builder->getQuery();
    }

    private function getSearchQueryBuilder(array $criteria)
    {
        $type = !empty($criteria['type']) ? $criteria['type'] : 'user';

        $repository = null;

        switch ($type)
        {
            case 'company':

                $repository = $this->entityManager->getRepository('App\Entity\CompanyRepresentativeUser');

                break;

            case 'analyst':

                $repository = $this->entityManager->getRepository('App\Entity\AnalystUser');

                break;

            case 'client':

                $repository = $this->entityManager->getRepository('App\Entity\ClientUser');

                break;

            default:

                throw new \Exception('Wrong the user type!');
        }

        return $repository->createQueryBuilder('user');

    }
}