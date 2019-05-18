<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="descriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "client" = "ClientUser",
 *     "admin" = "AdminUser",
 *     "company_representative" = "CompanyRepresentativeUser",
 *     "analyst" = "AnalystUser"
 * })
 *
 * @JMSSerializer\ExclusionPolicy("all")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @UniqueEntity("email", message="User with that email is already exist!", repositoryMethod="findByEmail")
 */
abstract class User implements UserInterface
{
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 10;

    use SerializeTimestampableTrait;
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max="180")
     *
     * @JMSSerializer\Groups({"private", "admin_default"})
     * @JMSSerializer\Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $fullName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @var boolean
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     *
     * @JMSSerializer\Groups({"default", "admin_default"})
     * @JMSSerializer\Expose
     */
    private $isActive = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\IssueComment", mappedBy="author")
     */
    private $issueComments;

    public function __construct()
    {
        $this->issueComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @JMSSerializer\VirtualProperty(name="roles")
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return (string) $this->fullName;
    }

    /**
     * @param string $fullName
     * @return $this
     */
    public function setFullName(string $fullName = null): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return User
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssueComments()
    {
        return $this->issueComments;
    }

    /**
     * @param ArrayCollection $issueComments
     * @return User
     */
    public function setIssueComments($issueComments): self
    {
        $this->issueComments = $issueComments;
        return $this;
    }
}
