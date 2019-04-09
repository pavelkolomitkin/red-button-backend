<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AdminUser
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\AdminUserRepository")
 * @ORM\Table(name="admin_user")
 */
class AdminUser extends User
{
    public function getRoles(): array
    {
        $result = parent::getRoles();

        $result[] = 'ROLE_ADMIN_USER';

        return $result;
    }
}
