<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * Class OSMAddress
 * @package App\Entity
 *
 * @ORM\Embeddable
 * @JMSSerializer\ExclusionPolicy("all")
 */
class OSMAddress
{
    use GeoLocationTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="place_id", type="bigint", nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $placeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="osm_id", type="bigint", nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $osmId;

    /**
     * @var string
     *
     * @ORM\Column(name="osm_type", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $osmType;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="road", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $road;

    /**
     * @var string
     *
     * @ORM\Column(name="village", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $village;


    /**
     * @var string
     *
     * @ORM\Column(name="state_district", type="string", length=255, nullable=true)
     */
    private $stateDistrict;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="post_code", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $postCode;

    /**
     * @var string
     *
     * @ORM\Column(name="county", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $county;


    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $country;


    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=10, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name_details", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $nameDetails;

    /**
     * @return int
     */
    public function getPlaceId(): ?int
    {
        return $this->placeId;
    }

    /**
     * @param int $placeId
     * @return OSMAddress
     */
    public function setPlaceId(?int $placeId = null): OSMAddress
    {
        $this->placeId = $placeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getOsmId(): ?int
    {
        return $this->osmId;
    }

    /**
     * @param int $osmId
     * @return OSMAddress
     */
    public function setOsmId(?int $osmId = null): OSMAddress
    {
        $this->osmId = $osmId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOsmType(): ?string
    {
        return $this->osmType;
    }

    /**
     * @param string $osmType
     * @return OSMAddress
     */
    public function setOsmType(?string $osmType = null): OSMAddress
    {
        $this->osmType = $osmType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     * @return OSMAddress
     */
    public function setDisplayName(?string $displayName = null): OSMAddress
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoad(): ?string
    {
        return $this->road;
    }

    /**
     * @param string $road
     * @return OSMAddress
     */
    public function setRoad(?string $road = null): OSMAddress
    {
        $this->road = $road;
        return $this;
    }

    /**
     * @return string
     */
    public function getVillage(): ?string
    {
        return $this->village;
    }

    /**
     * @param string $village
     * @return OSMAddress
     */
    public function setVillage(?string $village = null): OSMAddress
    {
        $this->village = $village;
        return $this;
    }

    /**
     * @return string
     */
    public function getStateDistrict(): ?string
    {
        return $this->stateDistrict;
    }

    /**
     * @param string $stateDistrict
     * @return OSMAddress
     */
    public function setStateDistrict(?string $stateDistrict = null): OSMAddress
    {
        $this->stateDistrict = $stateDistrict;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return OSMAddress
     */
    public function setCity(?string $city = null): OSMAddress
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     * @return OSMAddress
     */
    public function setPostCode(?string $postCode = null): OSMAddress
    {
        $this->postCode = $postCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCounty(): ?string
    {
        return $this->county;
    }

    /**
     * @param string $county
     * @return OSMAddress
     */
    public function setCounty(?string $county = null): OSMAddress
    {
        $this->county = $county;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return OSMAddress
     */
    public function setState(?string $state = null): OSMAddress
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return OSMAddress
     */
    public function setCountry(?string $country = null): OSMAddress
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return OSMAddress
     */
    public function setCountryCode(?string $countryCode = null): OSMAddress
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameDetails(): ?string
    {
        return $this->nameDetails;
    }

    /**
     * @param string $nameDetails
     * @return OSMAddress
     */
    public function setNameDetails(?string $nameDetails = null): OSMAddress
    {
        $this->nameDetails = $nameDetails;
        return $this;
    }
}
