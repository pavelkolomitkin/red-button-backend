<?php


namespace App\Service\DoctrineFilter;

use App\Entity\AdminUser;
use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Symfony\Component\Security\Core\User\UserInterface;

class ActiveUserFilter extends SQLFilter
{
    /**
     * @var UserInterface
     */
    private $user;

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($this->user instanceof AdminUser)
        {
            return '';
        }

        if ($targetEntity->reflClass->isSubclassOf(User::class))
        {
            return $targetTableAlias . '.is_active = 1';
        }

        return '';
    }
}
