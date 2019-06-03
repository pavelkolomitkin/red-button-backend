<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssuePictureRepository")
 * @ORM\Table(name="issue_picture")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Vich\Uploadable
 *
 * @JMSSerializer\ExclusionPolicy("all")
 */
class IssuePicture
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
     * @Assert\Image(maxSize="5M", mimeTypes={"image/*"}, maxSizeMessage="picture.max_size")
     * @Vich\UploadableField(mapping="issue_picture", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName", dimensions="image.dimensions")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $image;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Issue", inversedBy="pictures")
     * @ORM\JoinColumn(name="issue_id", nullable=true)
     */
    private $issue;

    /**
     * @var ClientUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientUser", inversedBy="issueUploadPictures")
     * @ORM\JoinColumn(name="owner_id", nullable=false)
     */
    private $owner;

    public function __construct()
    {
        $this->image = new EmbeddedFile();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     * @param File|UploadedFile $image
     * @return $this
     * @throws \Exception
     */
    public function setImageFile(?File $image = null)
    {
        $this->imageFile = $image;

        if (null !== $image)
        {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
    }

    public function __toString()
    {
        return $this->getImage()->getOriginalName();
    }

    /**
     * @return Issue
     */
    public function getIssue(): Issue
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     * @return IssuePicture
     */
    public function setIssue(Issue $issue = null): self
    {
        $this->issue = $issue;
        return $this;
    }

    /**
     * @return ClientUser
     */
    public function getOwner(): ClientUser
    {
        return $this->owner;
    }

    /**
     * @param ClientUser $owner
     * @return IssuePicture
     */
    public function setOwner(ClientUser $owner): self
    {
        $this->owner = $owner;
        return $this;
    }
}
