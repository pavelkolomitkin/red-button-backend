<?php


namespace App\Service\DoctrineFilter;

use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ActiveUserFilter extends SQLFilter
{
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
        // TODO: Implement addFilterConstraint() method.
        if ($targetEntity->reflClass->isSubclassOf(User::class))
        {
            return $targetTableAlias . '.is_active = 1';
        }

        return '';
    }
}
