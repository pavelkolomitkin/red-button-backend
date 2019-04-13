<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 * @ORM\Table(name="region")
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var FederalDistrict
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\FederalDistrict", inversedBy="regions")
     * @ORM\JoinColumn(name="federal_district_id", nullable=false)
     */
    private $federalDistrict;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Complaint", mappedBy="region")
     */
    private $complaints;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="region")
     */
    private $issues;

    public function __construct()
    {
        $this->complaints = new ArrayCollection();
        $this->issues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Region
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return FederalDistrict
     */
    public function getFederalDistrict(): FederalDistrict
    {
        return $this->federalDistrict;
    }

    /**
     * @param FederalDistrict $federalDistrict
     * @return Region
     */
    public function setFederalDistrict(FederalDistrict $federalDistrict): self
    {
        $this->federalDistrict = $federalDistrict;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaints()
    {
        return $this->complaints;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
