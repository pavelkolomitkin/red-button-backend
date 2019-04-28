<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;
use JMS\Serializer\Annotation\Type;
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
     * @ORM\Column(name="phone_number", type="phone_number", unique=true, nullable=true)
     *
     * @Assert\Length(max="255")
     * @AssertPhoneNumber(defaultRegion="RU", type="mobile")
     *
     * @Type("libphonenumber\PhoneNumber")
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Complaint", mappedBy="client", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $complaints;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="client", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $issues;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintPicture", mappedBy="owner")
     */
    private $complaintUploadPictures;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\IssuePicture", mappedBy="owner")
     */
    private $issueUploadPictures;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\VideoMaterial", mappedBy="owner")
     */
    private $videos;

    public function __construct()
    {
        $this->complaints = new ArrayCollection();
        $this->issues = new ArrayCollection();

        $this->complaintUploadPictures = new ArrayCollection();
        $this->issueUploadPictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber = null): self
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

    public function getComplaints()
    {
        return $this->complaints;
    }

    public function addComplaint(Complaint $complaint): self
    {
        if (!$this->complaints->contains($complaint))
        {
            $this->complaints[] = $complaint;
            $complaint->setClient($this);
        }

        return $this;
    }

    public function removeComplaint(Complaint $complaint): self
    {
        if ($this->complaints->contains($complaint))
        {
            $this->complaints->removeElement($complaint);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    public function addIssue(Issue $issue): self
    {
        if (!$this->issues->contains($issue))
        {
            $this->issues[] = $issue;
            $issue->setClient($this);
        }

        return $this;
    }

    public function removeIssue(Issue $issue): self
    {
        if ($this->issues->contains($issue))
        {
            $this->issues->removeElement($issue);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaintUploadPictures()
    {
        return $this->complaintUploadPictures;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssueUploadPictures()
    {
        return $this->issueUploadPictures;
    }


    /**
     * @return ArrayCollection
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
