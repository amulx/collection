<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcCityProvince
 *
 * @ORM\Table(name="xc_city_province")
 * @ORM\Entity
 */
class XcCityProvince
{
    /**
     * @var string
     *
     * @ORM\Column(name="sheng_code", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $shengCode;

    /**
     * @var string
     *
     * @ORM\Column(name="sheng_name", type="string", length=60, nullable=true)
     */
    private $shengName;

    /**
     * @var string
     *
     * @ORM\Column(name="sheng_pinyin", type="string", length=90, nullable=true)
     */
    private $shengPinyin;

    /**
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", length=45, nullable=true)
     */
    private $shortName;



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
     * Set shengName
     *
     * @param string $shengName
     * @return XcCityProvince
     */
    public function setShengName($shengName)
    {
        $this->shengName = $shengName;

        return $this;
    }

    /**
     * Get shengName
     *
     * @return string 
     */
    public function getShengName()
    {
        return $this->shengName;
    }

    /**
     * Set shengPinyin
     *
     * @param string $shengPinyin
     * @return XcCityProvince
     */
    public function setShengPinyin($shengPinyin)
    {
        $this->shengPinyin = $shengPinyin;

        return $this;
    }

    /**
     * Get shengPinyin
     *
     * @return string 
     */
    public function getShengPinyin()
    {
        return $this->shengPinyin;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return XcCityProvince
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->shortName;
    }
}
