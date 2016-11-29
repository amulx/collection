<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcPost
 *
 * @ORM\Table(name="xc_post")
 * @ORM\Entity
 */
class XcPost
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
     * @ORM\Column(name="member_id", type="bigint", nullable=true)
     */
    private $memberId;

    /**
     * @var string
     *
     * @ORM\Column(name="member_name", type="string", length=255, nullable=true)
     */
    private $memberName;

    /**
     * @var string
     *
     * @ORM\Column(name="member_logo", type="string", length=255, nullable=true)
     */
    private $memberLogo;

    /**
     * @var string
     *
     * @ORM\Column(name="member_ip", type="string", length=30, nullable=true)
     */
    private $memberIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modify_time", type="datetime", nullable=true)
     */
    private $modifyTime;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=512, nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="comment_num", type="smallint", nullable=true)
     */
    private $commentNum;

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
     * Set contentId
     *
     * @param integer $contentId
     * @return XcPost
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
     * @return XcPost
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
     * Set memberId
     *
     * @param integer $memberId
     * @return XcPost
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * Get memberId
     *
     * @return integer 
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * Set memberName
     *
     * @param string $memberName
     * @return XcPost
     */
    public function setMemberName($memberName)
    {
        $this->memberName = $memberName;

        return $this;
    }

    /**
     * Get memberName
     *
     * @return string 
     */
    public function getMemberName()
    {
        return $this->memberName;
    }

    /**
     * Set memberLogo
     *
     * @param string $memberLogo
     * @return XcPost
     */
    public function setMemberLogo($memberLogo)
    {
        $this->memberLogo = $memberLogo;

        return $this;
    }

    /**
     * Get memberLogo
     *
     * @return string 
     */
    public function getMemberLogo()
    {
        return $this->memberLogo;
    }

    /**
     * Set memberIp
     *
     * @param string $memberIp
     * @return XcPost
     */
    public function setMemberIp($memberIp)
    {
        $this->memberIp = $memberIp;

        return $this;
    }

    /**
     * Get memberIp
     *
     * @return string 
     */
    public function getMemberIp()
    {
        return $this->memberIp;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcPost
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime 
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set modifyTime
     *
     * @param \DateTime $modifyTime
     * @return XcPost
     */
    public function setModifyTime($modifyTime)
    {
        $this->modifyTime = $modifyTime;

        return $this;
    }

    /**
     * Get modifyTime
     *
     * @return \DateTime 
     */
    public function getModifyTime()
    {
        return $this->modifyTime;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return XcPost
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set commentNum
     *
     * @param integer $commentNum
     * @return XcPost
     */
    public function setCommentNum($commentNum)
    {
        $this->commentNum = $commentNum;

        return $this;
    }

    /**
     * Get commentNum
     *
     * @return integer 
     */
    public function getCommentNum()
    {
        return $this->commentNum;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return XcPost
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
