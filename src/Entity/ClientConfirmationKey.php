<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientConfirmationKeyRepository")
 * @ORM\Table(name="client_confirmation_key")
 */
class ClientConfirmationKey
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $key;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_activated", type="boolean")
     */
    private $isActivated = false;

    /**
     * @var ClientUser
     *
     * @ORM\OneToOne(targetEntity="App\Entity\ClientUser", inversedBy="confirmationKey")
     * @ORM\JoinColumn(name="client_id", nullable=false)
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->isActivated;
    }

    /**
     * @param bool $isActivated
     * @return $this
     */
    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;
        return $this;
    }

    /**
     * @return ClientUser
     */
    public function getClient(): ClientUser
    {
        return $this->client;
    }

    /**
     * @param ClientUser $client
     * @return $this
     */
    public function setClient(ClientUser $client): self
    {
        $this->client = $client;
        return $this;
    }

    public static function generateRandomKey($salt = '')
    {
        return sha1(time() . rand(0, 999999) . $salt);
    }
}
