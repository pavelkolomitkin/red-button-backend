<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * Class ClientUser
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ClientUserRepository")
 * @ORM\Table(name="client_user")
 *
 * @JMSSerializer\ExclusionPolicy("all")
 *
 * @UniqueEntity("phoneNumber", message="User with that phone number is already exist!")
 */
class ClientUser extends User
{
    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="phone_number", length=255, unique=true, nullable=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @AssertPhoneNumber(defaultRegion="RU", type="mobile")
     *
     * @JMSSerializer\Type("libphonenumber\PhoneNumber")
     *
     * @JMSSerializer\Groups({"private"})
     * @JMSSerializer\Expose
     *
     */
    private $phoneNumber;

    /**
     * @var ClientConfirmationKey
     *
     * @ORM\OneToOne(targetEntity="App\Entity\ClientConfirmationKey", mappedBy="client", cascade={"persist", "remove"})
     */
    private $confirmationKey;

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


    /**
     * @return ClientConfirmationKey
     */
    public function getConfirmationKey(): ClientConfirmationKey
    {
        return $this->confirmationKey;
    }

    /**
     * @param ClientConfirmationKey $confirmationKey
     * @return $this
     */
    public function setConfirmationKey(ClientConfirmationKey $confirmationKey): self
    {
        $this->confirmationKey = $confirmationKey;
        $confirmationKey->setClient($this);

        return $this;
    }
}
