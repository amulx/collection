<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcCityDistrict
 *
 * @ORM\Table(name="xc_city_district")
 * @ORM\Entity
 */
class XcCityDistrict
{
    /**
     * @var integer
     *
     * @ORM\Column(name="qu_code", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $quCode;

    /**
     * @var string
     *
     * @ORM\Column(name="qu_name", type="string", length=20, nullable=true)
     */
    private $quName;

    /**
     * @var string
     *
     * @ORM\Column(name="qu_pinyin", type="string", length=20, nullable=true)
     */
    private $quPinyin;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_code", type="integer", nullable=true)
     */
    private $cityCode;



    /**
     * Get quCode
     *
     * @return integer 
     */
    public function getQuCode()
    {
        return $this->quCode;
    }

    /**
     * Set quName
     *
     * @param string $quName
     * @return XcCityDistrict
     */
    public function setQuName($quName)
    {
        $this->quName = $quName;

        return $this;
    }

    /**
     * Get quName
     *
     * @return string 
     */
    public function getQuName()
    {
        return $this->quName;
    }

    /**
     * Set quPinyin
     *
     * @param string $quPinyin
     * @return XcCityDistrict
     */
    public function setQuPinyin($quPinyin)
    {
        $this->quPinyin = $quPinyin;

        return $this;
    }

    /**
     * Get quPinyin
     *
     * @return string 
     */
    public function getQuPinyin()
    {
        return $this->quPinyin;
    }

    /**
     * Set cityCode
     *
     * @param integer $cityCode
     * @return XcCityDistrict
     */
    public function setCityCode($cityCode)
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    /**
     * Get cityCode
     *
     * @return integer 
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }
}
