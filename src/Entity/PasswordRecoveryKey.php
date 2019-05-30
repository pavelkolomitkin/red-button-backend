<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PasswordRecoveryKeyRepository")
 * @ORM\Table(name="password_recovery_key")
 */
class PasswordRecoveryKey
{
    use SerializeTimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="key_hash", type="string", length=255, nullable=false, unique=true)
     */
    private $key;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="passwordRecoveryKey")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return PasswordRecoveryKey
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return PasswordRecoveryKey
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public static function generateRandomKey($salt = '')
    {
        return sha1(time() . rand(0, 999999) . $salt);
    }
}
