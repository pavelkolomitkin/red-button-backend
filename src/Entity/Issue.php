<?php

namespace App\Entity;

use App\Validator\Constraints\Client\IssueComplaintConfirmationUniqueUserListConstraint;
use App\Validator\Constraints\Client\IssuePictureOwnerConstraint;
use App\Validator\Constraints\Client\VideoOwnerConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssueRepository")
 * @ORM\Table(name="issue")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Issue
{
    use SerializeTimestampableTrait;
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintConfirmation", mappedBy="issue", cascade={"persist", "remove"})
     *
     * @IssueComplaintConfirmationUniqueUserListConstraint()
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *
     *     "admin_default",
     *     "company_default"
     * })
     * @JMSSerializer\Expose
     */
    private $complaintConfirmations;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="10000")
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "admin_default",
     *     "company_default"
     *     })
     * @JMSSerializer\Expose
     */
    private $message;

    /**
     * @var ServiceType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ServiceType", inversedBy="issues")
     * @ORM\JoinColumn(name="service_type_id", nullable=true)
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "admin_default",
     *     "company_default"
     *     })
     * @JMSSerializer\Expose
     */
    private $serviceType;

    /**
     * @var ClientUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientUser", inversedBy="issues")
     * @ORM\JoinColumn(name="client_id", nullable=false)
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "client_issue_incoming_confirmation",
     *     "admin_default",
     *     "company_default"
     *     })
     * @JMSSerializer\Expose
     */
    private $client;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="issues")
     * @ORM\JoinColumn(name="company_id", nullable=true)
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "admin_default"
     *     })
     * @JMSSerializer\Expose
     */
    private $company;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="issues")
     * @ORM\JoinColumn(name="region_id", nullable=false)
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "admin_default",
     *     "company_default"
     * })
     * @JMSSerializer\Expose
     */
    private $region;

    /**
     * @var ArrayCollection
     *
     * @Assert\All(
     *     @IssuePictureOwnerConstraint()
     * )
     *
     * @ORM\OneToMany(targetEntity="App\Entity\IssuePicture", mappedBy="issue", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "admin_default",
     *     "company_default"
     * })
     * @JMSSerializer\Expose
     */
    private $pictures;

    /**
     * @var ArrayCollection
     *
     * @Assert\All(
     *     @VideoOwnerConstraint()
     * )
     *
     * @ORM\OneToMany(targetEntity="App\Entity\VideoMaterial", mappedBy="issue", cascade={"persist"})
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *      "client_issue_details",
     *     "admin_default",
     *     "company_default"
     * })
     * @JMSSerializer\Expose
     */
    private $videos;

    /**
     * @var OSMAddress
     * @ORM\Embedded(class="App\Entity\OSMAddress")
     *
     * @JMSSerializer\Groups({
     *     "default",
     *     "admin_default",
     *     "company_default"
     * })
     * @JMSSerializer\Expose
     */
    private $address;

    /**
     * @var int
     *
     * @ORM\Column(name="comment_number", type="integer", nullable=false, options={"default": 0})
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "admin_default",
     *     "company_default"
     *     })
     * @JMSSerializer\Expose
     */
    private $commentNumber = 0;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\IssueComment", mappedBy="issue", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $comments;

    /**
     * @var int
     *
     * @ORM\Column(name="like_number", type="integer", nullable=false, options={"default": 0})
     *
     * @JMSSerializer\Groups({
     *     "client_issue_list",
     *     "client_issue_details",
     *     "admin_default",
     *     "company_default"
     *     })
     * @JMSSerializer\Expose
     */
    private $likeNumber = 0;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ClientUser", inversedBy="likeIssues")
     * @ORM\JoinTable(name="user_like_issue",
     *     joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")})
     */
    private $likes;

    public function __construct()
    {
        $this->complaintConfirmations = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->address = new OSMAddress();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaintConfirmations()
    {
        return $this->complaintConfirmations;
    }

    /**
     * @param $confirmations
     * @return Issue
     */
    public function setComplaintConfirmations($confirmations): self
    {
        foreach ($confirmations as $confirmation)
        {
            $this->addComplaintConfirmation($confirmation);
        }

        return $this;
    }

    /**
     * @param ComplaintConfirmation $confirmation
     * @return Issue
     */
    public function addComplaintConfirmation(ComplaintConfirmation $confirmation): self
    {
        if (!$this->complaintConfirmations->contains($confirmation))
        {
            $this->complaintConfirmations[] = $confirmation;
            $confirmation->setIssue($this);
        }

        return $this;
    }

    /**
     * @param ComplaintConfirmation $confirmation
     * @return Issue
     */
    public function removeComplaintConfirmation(ComplaintConfirmation $confirmation): self
    {
        if ($this->complaintConfirmations->contains($confirmation))
        {
            $this->complaintConfirmations->removeElement($confirmation);
        }

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
     * @return Issue
     */
    public function setClient(ClientUser $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return Issue
     */
    public function setCompany(Company $company = null): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return Region
     */
    public function getRegion(): ?Region
    {
        return $this->region;
    }

    /**
     * @param Region $region
     * @return Issue
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param ArrayCollection $pictures
     * @return $this
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function addPicture(IssuePicture $picture): self
    {
        if (!$this->pictures->contains($picture))
        {
            $this->pictures[] = $picture;
            $picture->setIssue($this);
        }

        return $this;
    }

    public function removePicture(IssuePicture $picture): self
    {
        if ($this->pictures->contains($picture))
        {
            $this->pictures->removeElement($picture);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param ArrayCollection $videos
     * @return $this
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;

        return $this;
    }

    public function addVideo(VideoMaterial $video): self
    {
        if (!$this->videos->contains($video))
        {
            $this->videos[] = $video;
            $video->setIssue($this);
        }

        return $this;
    }

    public function removeVideo(VideoMaterial $video): self
    {
        if ($this->videos->contains($video))
        {
            $this->videos->removeElement($video);
            $video->setIssue(null);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Issue
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return ServiceType
     */
    public function getServiceType(): ?ServiceType
    {
        return $this->serviceType;
    }

    /**
     * @param ServiceType $serviceType
     * @return Issue
     */
    public function setServiceType(ServiceType $serviceType = null): self
    {
        $this->serviceType = $serviceType;
        return $this;
    }

    /**
     * @return OSMAddress
     */
    public function getAddress(): ?OSMAddress
    {
        return $this->address;
    }

    /**
     * @param OSMAddress $address
     * @return Issue
     */
    public function setAddress(OSMAddress $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     * @return Issue
     */
    public function setComments($comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function addComment(IssueComment $comment): self
    {
        if (!$this->comments->contains($comment))
        {
            $this->comments[] = $comment;
            $comment->setIssue($this);

            $this->commentNumber++;
        }

        return $this;
    }

    public function removeComment(IssueComment $comment): self
    {
        if ($this->comments->contains($comment))
        {
            $this->comments->removeElement($comment);

            $this->commentNumber--;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getLikeNumber(): int
    {
        return $this->likeNumber;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }


    public function addLike(ClientUser $user): self
    {
        if (!$this->likes->contains($user))
        {
            $this->likes[] = $user;
            $this->likeNumber++;
        }

        return $this;
    }

    public function removeLike(ClientUser $user): self
    {
        if ($this->likes->contains($user))
        {
            $this->likes->removeElement($user);
            $this->likeNumber--;
        }

        return $this;
    }


    public function __toString()
    {
        return (string)$this->getMessage();
    }
}
