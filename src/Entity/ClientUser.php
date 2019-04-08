<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ClientUser
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ClientUserRepository")
 * @ORM\Table(name="client_user")
 */
class ClientUser extends User
{
    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255, unique=true, nullable=true)
     */
    private $phoneNumber;

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return ClientUser
     */
    public function setPhoneNumber(string $phoneNumber = null): ClientUser
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getRoles(): array
    {
        $result = parent::getRoles();

        $result[] = 'ROLE_CLIENT_USER';

        return $result;
    }
}
