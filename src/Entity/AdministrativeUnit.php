<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdministrativeUnitRepository")
 * @ORM\Table(name="administrative_unit")
 *
 * @JMSSerializer\ExclusionPolicy("all")
 */
class AdministrativeUnit
{
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $title;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="administrativeUnits")
     * @ORM\JoinColumn(name="region_id", nullable=false)
     *
     * @JMSSerializer\Groups({"admin_details"})
     * @JMSSerializer\Expose
     */
    private $region;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Company", inversedBy="administrativeUnits", cascade={"persist"})
     * @ORM\JoinTable(name="administrative_unit_company",
     *  joinColumns={@ORM\JoinColumn(name="administrative_unit_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
     *     )
     */
    private $companies;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
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
     * @return AdministrativeUnit
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get region
     *
     * @return Region
     */
    public function getRegion(): Region
    {
        return $this->region;
    }

    /**
     * Set region
     *
     * @param Region $region
     * @return AdministrativeUnit
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company))
        {
            $this->companies[] = $company;
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->contains($company))
        {
            $this->companies->removeElement($company);
        }

        return $this;
    }
}
