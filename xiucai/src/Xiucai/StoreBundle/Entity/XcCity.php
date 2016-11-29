<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcCity
 *
 * @ORM\Table(name="xc_city")
 * @ORM\Entity
 */
class XcCity
{
    /**
     * @var string
     *
     * @ORM\Column(name="city_code", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cityCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city_name", type="string", length=90, nullable=true)
     */
    private $cityName;

    /**
     * @var string
     *
     * @ORM\Column(name="city_pinyin", type="string", length=90, nullable=true)
     */
    private $cityPinyin;

    /**
     * @var string
     *
     * @ORM\Column(name="sheng_code", type="string", length=90, nullable=true)
     */
    private $shengCode;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=30, nullable=true)
     */
    private $symbol;



    /**
     * Get cityCode
     *
     * @return string 
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }

    /**
     * Set cityName
     *
     * @param string $cityName
     * @return XcCity
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName
     *
     * @return string 
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set cityPinyin
     *
     * @param string $cityPinyin
     * @return XcCity
     */
    public function setCityPinyin($cityPinyin)
    {
        $this->cityPinyin = $cityPinyin;

        return $this;
    }

    /**
     * Get cityPinyin
     *
     * @return string 
     */
    public function getCityPinyin()
    {
        return $this->cityPinyin;
    }

    /**
     * Set shengCode
     *
     * @param string $shengCode
     * @return XcCity
     */
    public function setShengCode($shengCode)
    {
        $this->shengCode = $shengCode;

        return $this;
    }

    /**
     * Get shengCode
     *
     * @return string 
     */
    public function getShengCode()
    {
        return $this->shengCode;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     * @return XcCity
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}
