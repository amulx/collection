<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcVideo
 *
 * @ORM\Table(name="xc_video")
 * @ORM\Entity
 */
class XcVideo
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

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
     * @var string
     *
     * @ORM\Column(name="third_party_id", type="string", length=255, nullable=true)
     */
    private $thirdPartyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="length", type="smallint", nullable=true)
     */
    private $length;

    /**
     * @var integer
     *
     * @ORM\Column(name="zindex", type="smallint", nullable=true)
     */
    private $zindex;

    /**
     * @var string
     *
     * @ORM\Column(name="brief", type="string", length=255, nullable=true)
     */
    private $brief;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=128, nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="play_count", type="integer", nullable=true)
     */
    private $playCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_free", type="smallint", nullable=true)
     */
    private $isFree;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;



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
     * Set title
     *
     * @param string $title
     * @return XcVideo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set contentId
     *
     * @param integer $contentId
     * @return XcVideo
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
     * @return XcVideo
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
     * Set thirdPartyId
     *
     * @param string $thirdPartyId
     * @return XcVideo
     */
    public function setThirdPartyId($thirdPartyId)
    {
        $this->thirdPartyId = $thirdPartyId;

        return $this;
    }

    /**
     * Get thirdPartyId
     *
     * @return string 
     */
    public function getThirdPartyId()
    {
        return $this->thirdPartyId;
    }

    /**
     * Set length
     *
     * @param integer $length
     * @return XcVideo
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set zindex
     *
     * @param integer $zindex
     * @return XcVideo
     */
    public function setZindex($zindex)
    {
        $this->zindex = $zindex;

        return $this;
    }

    /**
     * Get zindex
     *
     * @return integer 
     */
    public function getZindex()
    {
        return $this->zindex;
    }

    /**
     * Set brief
     *
     * @param string $brief
     * @return XcVideo
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * Get brief
     *
     * @return string 
     */
    public function getBrief()
    {
        return $this->brief;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return XcVideo
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return XcVideo
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return XcVideo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return XcVideo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set playCount
     *
     * @param integer $playCount
     * @return XcVideo
     */
    public function setPlayCount($playCount)
    {
        $this->playCount = $playCount;

        return $this;
    }

    /**
     * Get playCount
     *
     * @return integer 
     */
    public function getPlayCount()
    {
        return $this->playCount;
    }

    /**
     * Set isFree
     *
     * @param integer $isFree
     * @return XcVideo
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get isFree
     *
     * @return integer 
     */
    public function getIsFree()
    {
        return $this->isFree;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return XcVideo
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
