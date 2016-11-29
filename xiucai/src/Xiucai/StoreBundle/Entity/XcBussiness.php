<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcBussiness
 *
 * @ORM\Table(name="xc_bussiness")
 * @ORM\Entity
 */
class XcBussiness
{
    /**
     * @var integer
     *
     * @ORM\Column(name="bussiness_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bussinessId;

    /**
     * @var string
     *
     * @ORM\Column(name="bussiness_name", type="string", length=20, nullable=true)
     */
    private $bussinessName;

    /**
     * @var string
     *
     * @ORM\Column(name="quan_name", type="string", length=20, nullable=true)
     */
    private $quanName;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_count", type="integer", nullable=true)
     */
    private $memberCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="question_count", type="integer", nullable=true)
     */
    private $questionCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="tui_flag", type="smallint", nullable=true)
     */
    private $tuiFlag;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="index_tui", type="smallint", nullable=true)
     */
    private $indexTui;

    /**
     * @var string
     *
     * @ORM\Column(name="ico_name", type="string", length=2, nullable=true)
     */
    private $icoName;



    /**
     * Get bussinessId
     *
     * @return integer 
     */
    public function getBussinessId()
    {
        return $this->bussinessId;
    }

    /**
     * Set bussinessName
     *
     * @param string $bussinessName
     * @return XcBussiness
     */
    public function setBussinessName($bussinessName)
    {
        $this->bussinessName = $bussinessName;

        return $this;
    }

    /**
     * Get bussinessName
     *
     * @return string 
     */
    public function getBussinessName()
    {
        return $this->bussinessName;
    }

    /**
     * Set quanName
     *
     * @param string $quanName
     * @return XcBussiness
     */
    public function setQuanName($quanName)
    {
        $this->quanName = $quanName;

        return $this;
    }

    /**
     * Get quanName
     *
     * @return string 
     */
    public function getQuanName()
    {
        return $this->quanName;
    }

    /**
     * Set memberCount
     *
     * @param integer $memberCount
     * @return XcBussiness
     */
    public function setMemberCount($memberCount)
    {
        $this->memberCount = $memberCount;

        return $this;
    }

    /**
     * Get memberCount
     *
     * @return integer 
     */
    public function getMemberCount()
    {
        return $this->memberCount;
    }

    /**
     * Set questionCount
     *
     * @param integer $questionCount
     * @return XcBussiness
     */
    public function setQuestionCount($questionCount)
    {
        $this->questionCount = $questionCount;

        return $this;
    }

    /**
     * Get questionCount
     *
     * @return integer 
     */
    public function getQuestionCount()
    {
        return $this->questionCount;
    }

    /**
     * Set tuiFlag
     *
     * @param integer $tuiFlag
     * @return XcBussiness
     */
    public function setTuiFlag($tuiFlag)
    {
        $this->tuiFlag = $tuiFlag;

        return $this;
    }

    /**
     * Get tuiFlag
     *
     * @return integer 
     */
    public function getTuiFlag()
    {
        return $this->tuiFlag;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return XcBussiness
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string 
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set indexTui
     *
     * @param integer $indexTui
     * @return XcBussiness
     */
    public function setIndexTui($indexTui)
    {
        $this->indexTui = $indexTui;

        return $this;
    }

    /**
     * Get indexTui
     *
     * @return integer 
     */
    public function getIndexTui()
    {
        return $this->indexTui;
    }

    /**
     * Set icoName
     *
     * @param string $icoName
     * @return XcBussiness
     */
    public function setIcoName($icoName)
    {
        $this->icoName = $icoName;

        return $this;
    }

    /**
     * Get icoName
     *
     * @return string 
     */
    public function getIcoName()
    {
        return $this->icoName;
    }
}
