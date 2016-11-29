<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcHitall
 *
 * @ORM\Table(name="xc_hitall")
 * @ORM\Entity
 */
class XcHitall
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="content_id", type="bigint", nullable=true)
     */
    private $contentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="content_type", type="smallint", nullable=true)
     */
    private $contentType;

    /**
     * @var integer
     *
     * @ORM\Column(name="hit", type="integer", nullable=true)
     */
    private $hit;

    /**
     * @var integer
     *
     * @ORM\Column(name="hit_mask", type="integer", nullable=true)
     */
    private $hitMask;



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
     * Set contentId
     *
     * @param integer $contentId
     * @return XcHitall
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * Get contentId
     *
     * @return integer 
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * Set contentType
     *
     * @param integer $contentType
     * @return XcHitall
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return integer 
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set hit
     *
     * @param integer $hit
     * @return XcHitall
     */
    public function setHit($hit)
    {
        $this->hit = $hit;

        return $this;
    }

    /**
     * Get hit
     *
     * @return integer 
     */
    public function getHit()
    {
        return $this->hit;
    }

    /**
     * Set hitMask
     *
     * @param integer $hitMask
     * @return XcHitall
     */
    public function setHitMask($hitMask)
    {
        $this->hitMask = $hitMask;

        return $this;
    }

    /**
     * Get hitMask
     *
     * @return integer 
     */
    public function getHitMask()
    {
        return $this->hitMask;
    }
}
