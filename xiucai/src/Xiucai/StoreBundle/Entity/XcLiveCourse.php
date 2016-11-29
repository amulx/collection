<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcLiveCourse
 *
 * @ORM\Table(name="xc_live_course")
 * @ORM\Entity
 */
class XcLiveCourse
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
     * @ORM\Column(name="author_id", type="integer", nullable=true)
     */
    private $authorId;

    /**
     * @var integer
     *
     * @ORM\Column(name="conf_id", type="bigint", nullable=true)
     */
    private $confId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="brief", type="string", length=512, nullable=true)
     */
    private $brief;

    /**
     * @var integer
     *
     * @ORM\Column(name="instructor_id", type="integer", nullable=true)
     */
    private $instructorId;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="reserve_init", type="integer", nullable=true)
     */
    private $reserveInit;

    /**
     * @var integer
     *
     * @ORM\Column(name="reserve_num", type="integer", nullable=true)
     */
    private $reserveNum;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_star", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $commentStar;

    /**
     * @var integer
     *
     * @ORM\Column(name="course_level", type="smallint", nullable=true)
     */
    private $courseLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_paid", type="smallint", nullable=true)
     */
    private $isPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="original_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $originalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="current_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $currentPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true)
     */
    private $categoryId;

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
     * @var \DateTime
     *
     * @ORM\Column(name="schedule_time", type="datetime", nullable=true)
     */
    private $scheduleTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=255, nullable=true)
     */
    private $imgUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="banner_url", type="string", length=255, nullable=true)
     */
    private $bannerUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="record_url", type="string", length=255, nullable=true)
     */
    private $recordUrl;



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
     * Set authorId
     *
     * @param integer $authorId
     * @return XcLiveCourse
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set confId
     *
     * @param integer $confId
     * @return XcLiveCourse
     */
    public function setConfId($confId)
    {
        $this->confId = $confId;

        return $this;
    }

    /**
     * Get confId
     *
     * @return integer 
     */
    public function getConfId()
    {
        return $this->confId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return XcLiveCourse
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
     * Set brief
     *
     * @param string $brief
     * @return XcLiveCourse
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
     * Set instructorId
     *
     * @param integer $instructorId
     * @return XcLiveCourse
     */
    public function setInstructorId($instructorId)
    {
        $this->instructorId = $instructorId;

        return $this;
    }

    /**
     * Get instructorId
     *
     * @return integer 
     */
    public function getInstructorId()
    {
        return $this->instructorId;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return XcLiveCourse
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
     * Set duration
     *
     * @param integer $duration
     * @return XcLiveCourse
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set reserveInit
     *
     * @param integer $reserveInit
     * @return XcLiveCourse
     */
    public function setReserveInit($reserveInit)
    {
        $this->reserveInit = $reserveInit;

        return $this;
    }

    /**
     * Get reserveInit
     *
     * @return integer 
     */
    public function getReserveInit()
    {
        return $this->reserveInit;
    }

    /**
     * Set reserveNum
     *
     * @param integer $reserveNum
     * @return XcLiveCourse
     */
    public function setReserveNum($reserveNum)
    {
        $this->reserveNum = $reserveNum;

        return $this;
    }

    /**
     * Get reserveNum
     *
     * @return integer 
     */
    public function getReserveNum()
    {
        return $this->reserveNum;
    }

    /**
     * Set commentStar
     *
     * @param string $commentStar
     * @return XcLiveCourse
     */
    public function setCommentStar($commentStar)
    {
        $this->commentStar = $commentStar;

        return $this;
    }

    /**
     * Get commentStar
     *
     * @return string 
     */
    public function getCommentStar()
    {
        return $this->commentStar;
    }

    /**
     * Set courseLevel
     *
     * @param integer $courseLevel
     * @return XcLiveCourse
     */
    public function setCourseLevel($courseLevel)
    {
        $this->courseLevel = $courseLevel;

        return $this;
    }

    /**
     * Get courseLevel
     *
     * @return integer 
     */
    public function getCourseLevel()
    {
        return $this->courseLevel;
    }

    /**
     * Set isPaid
     *
     * @param integer $isPaid
     * @return XcLiveCourse
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid
     *
     * @return integer 
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * Set originalPrice
     *
     * @param string $originalPrice
     * @return XcLiveCourse
     */
    public function setOriginalPrice($originalPrice)
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    /**
     * Get originalPrice
     *
     * @return string 
     */
    public function getOriginalPrice()
    {
        return $this->originalPrice;
    }

    /**
     * Set currentPrice
     *
     * @param string $currentPrice
     * @return XcLiveCourse
     */
    public function setCurrentPrice($currentPrice)
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    /**
     * Get currentPrice
     *
     * @return string 
     */
    public function getCurrentPrice()
    {
        return $this->currentPrice;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return XcLiveCourse
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
     * Set categoryId
     *
     * @param integer $categoryId
     * @return XcLiveCourse
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcLiveCourse
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
     * @return XcLiveCourse
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
     * Set scheduleTime
     *
     * @param \DateTime $scheduleTime
     * @return XcLiveCourse
     */
    public function setScheduleTime($scheduleTime)
    {
        $this->scheduleTime = $scheduleTime;

        return $this;
    }

    /**
     * Get scheduleTime
     *
     * @return \DateTime 
     */
    public function getScheduleTime()
    {
        return $this->scheduleTime;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return XcLiveCourse
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

    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     * @return XcLiveCourse
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
     * Set bannerUrl
     *
     * @param string $bannerUrl
     * @return XcLiveCourse
     */
    public function setBannerUrl($bannerUrl)
    {
        $this->bannerUrl = $bannerUrl;

        return $this;
    }

    /**
     * Get bannerUrl
     *
     * @return string 
     */
    public function getBannerUrl()
    {
        return $this->bannerUrl;
    }

    /**
     * Set recordUrl
     *
     * @param string $recordUrl
     * @return XcLiveCourse
     */
    public function setRecordUrl($recordUrl)
    {
        $this->recordUrl = $recordUrl;

        return $this;
    }

    /**
     * Get recordUrl
     *
     * @return string 
     */
    public function getRecordUrl()
    {
        return $this->recordUrl;
    }
}
