<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AnalystUser
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AnalystUserRepository")
 * @ORM\Table(name="analyst_user")
 */
class AnalystUser extends User
{
    public function getRoles(): array
    {
        $result = parent::getRoles();

        $result[] = 'ROLE_ANALYST_USER';

        return $result;
    }
}