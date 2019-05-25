<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * Class OSMRegion
 * @package App\Entity
 *
 * @ORM\Embeddable
 * @JMSSerializer\ExclusionPolicy("all")
 */
class OSMRegion
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
     * @var array
     *
     * @ORM\Column(name="bounding_box", type="array", nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $boundingBox;

    /**
     * @var mixed
     * @ORM\Column(name="geo_json", type="json", nullable=true)
     *
     */
    private $geoJson;

    /**
     * @return int
     */
    public function getPlaceId(): ?int
    {
        return $this->placeId;
    }

    /**
     * @param int $placeId
     * @return OSMRegion
     */
    public function setPlaceId(int $placeId): self
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
     * @return OSMRegion
     */
    public function setOsmId(int $osmId): self
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
     * @return OSMRegion
     */
    public function setOsmType(string $osmType): self
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
     * @return OSMRegion
     */
    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return array
     */
    public function getBoundingBox(): ?array
    {
        return $this->boundingBox;
    }

    /**
     * @param array $boundingBox
     * @return OSMRegion
     */
    public function setBoundingBox(array $boundingBox): self
    {
        $this->boundingBox = $boundingBox;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeoJson()
    {
        return $this->geoJson;
    }

    /**
     * @param mixed $geoJson
     * @return OSMRegion
     */
    public function setGeoJson($geoJson)
    {
        $this->geoJson = $geoJson;
        return $this;
    }
}