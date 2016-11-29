<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcRoomType
 *
 * @ORM\Table(name="xc_room_type")
 * @ORM\Entity
 */
class XcRoomType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_attendee", type="integer", nullable=true)
     */
    private $maxAttendee;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=255, nullable=true)
     */
    private $imgUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_activate", type="boolean", nullable=true)
     */
    private $isActivate;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return XcRoomType
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set maxAttendee
     *
     * @param integer $maxAttendee
     * @return XcRoomType
     */
    public function setMaxAttendee($maxAttendee)
    {
        $this->maxAttendee = $maxAttendee;

        return $this;
    }

    /**
     * Get maxAttendee
     *
     * @return integer 
     */
    public function getMaxAttendee()
    {
        return $this->maxAttendee;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return XcRoomType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return XcRoomType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     * @return XcRoomType
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string 
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set isActivate
     *
     * @param boolean $isActivate
     * @return XcRoomType
     */
    public function setIsActivate($isActivate)
    {
        $this->isActivate = $isActivate;

        return $this;
    }

    /**
     * Get isActivate
     *
     * @return boolean 
     */
    public function getIsActivate()
    {
        return $this->isActivate;
    }
}
