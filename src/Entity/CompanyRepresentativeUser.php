<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CompanyRepresentativeUser
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ClientUserRepository")
 * @ORM\Table(name="company_representative_user")
 */
class CompanyRepresentativeUser extends User
{

}
