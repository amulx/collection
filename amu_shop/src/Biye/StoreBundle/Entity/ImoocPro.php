<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-3-18
 * Time: 上午9:58
 */

namespace Biye\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImoocPro
 *
 * @ORM\Table(name="imooc_pro")
 * @ORM\Entity
 */


class ImoocPro {

    /**
     * @var string
     *
     * @ORM\Column(name="pName", type="string", length=255, nullable=true)
     */
    private $pName;

    /**
     * @var string
     *
     * @ORM\Column(name="pSn", type="string", length=50, nullable=true)
     */
    private $pSn;

    /**
     * @var integer
     *
     * @ORM\Column(name="pNum", type="integer", length=10, nullable=true)
     */
    private $pNum;

    /**
     * @var string
     *
     * @ORM\Column(name="mPrice", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $mPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="iPrice", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $iPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="pDesc", type="text", length=65535, nullable=true)
     */
    private $pDesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="pubTime", type="integer", nullable=true)
     */
    private $pubTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="isShow", type="smallint", nullable=true)
     */
    private $isShow;

    /**
     * @var integer
     *
     * @ORM\Column(name="isHot", type="smallint", nullable=true)
     */
    private $isHot;

    /**
     * @var integer
     *
     * @ORM\Column(name="cId", type="smallint", nullable=true)
     */
    private $cId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set pName
     *
     * @param string $pName
     * @return ImoocPro
     */
    public function setPName($pName)
    {
        $this->pName = $pName;

        return $this;
    }

    /**
     * Get pName
     *
     * @return string 
     */
    public function getPName()
    {
        return $this->pName;
    }

    /**
     * Set pSn
     *
     * @param string $pSn
     * @return ImoocPro
     */
    public function setPSn($pSn)
    {
        $this->pSn = $pSn;

        return $this;
    }

    /**
     * Get pSn
     *
     * @return string 
     */
    public function getPSn()
    {
        return $this->pSn;
    }

    /**
     * Set pNum
     *
     * @param integer $pNum
     * @return ImoocPro
     */
    public function setPNum($pNum)
    {
        $this->pNum = $pNum;

        return $this;
    }

    /**
     * Get pNum
     *
     * @return integer 
     */
    public function getPNum()
    {
        return $this->pNum;
    }

    /**
     * Set mPrice
     *
     * @param string $mPrice
     * @return ImoocPro
     */
    public function setMPrice($mPrice)
    {
        $this->mPrice = $mPrice;

        return $this;
    }

    /**
     * Get mPrice
     *
     * @return string 
     */
    public function getMPrice()
    {
        return $this->mPrice;
    }

    /**
     * Set iPrice
     *
     * @param string $iPrice
     * @return ImoocPro
     */
    public function setIPrice($iPrice)
    {
        $this->iPrice = $iPrice;

        return $this;
    }

    /**
     * Get iPrice
     *
     * @return string 
     */
    public function getIPrice()
    {
        return $this->iPrice;
    }

    /**
     * Set pDesc
     *
     * @param string $pDesc
     * @return ImoocPro
     */
    public function setPDesc($pDesc)
    {
        $this->pDesc = $pDesc;

        return $this;
    }

    /**
     * Get pDesc
     *
     * @return string 
     */
    public function getPDesc()
    {
        return $this->pDesc;
    }

    /**
     * Set pubTime
     *
     * @param integer $pubTime
     * @return ImoocPro
     */
    public function setPubTime($pubTime)
    {
        $this->pubTime = $pubTime;

        return $this;
    }

    /**
     * Get pubTime
     *
     * @return integer 
     */
    public function getPubTime()
    {
        return $this->pubTime;
    }

    /**
     * Set isShow
     *
     * @param integer $isShow
     * @return ImoocPro
     */
    public function setIsShow($isShow)
    {
        $this->isShow = $isShow;

        return $this;
    }

    /**
     * Get isShow
     *
     * @return integer 
     */
    public function getIsShow()
    {
        return $this->isShow;
    }

    /**
     * Set isHot
     *
     * @param integer $isHot
     * @return ImoocPro
     */
    public function setIsHot($isHot)
    {
        $this->isHot = $isHot;

        return $this;
    }

    /**
     * Get isHot
     *
     * @return integer 
     */
    public function getIsHot()
    {
        return $this->isHot;
    }

    /**
     * Set cId
     *
     * @param integer $cId
     * @return ImoocPro
     */
    public function setCId($cId)
    {
        $this->cId = $cId;

        return $this;
    }

    /**
     * Get cId
     *
     * @return integer 
     */
    public function getCId()
    {
        return $this->cId;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
