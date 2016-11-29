<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcThirdparty
 *
 * @ORM\Table(name="xc_thirdparty")
 * @ORM\Entity
 */
class XcThirdparty
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
     * @ORM\Column(name="name", type="string", length=32, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="appkey", type="string", length=32, nullable=true)
     */
    private $appkey;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_active", type="smallint", nullable=true)
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="app_secret", type="string", length=32, nullable=true)
     */
    private $appSecret;



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
     * Set name
     *
     * @param string $name
     * @return XcThirdparty
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
     * @return XcThirdparty
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
     * Set appkey
     *
     * @param string $appkey
     * @return XcThirdparty
     */
    public function setAppkey($appkey)
    {
        $this->appkey = $appkey;

        return $this;
    }

    /**
     * Get appkey
     *
     * @return string 
     */
    public function getAppkey()
    {
        return $this->appkey;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     * @return XcThirdparty
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set appSecret
     *
     * @param string $appSecret
     * @return XcThirdparty
     */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;

        return $this;
    }

    /**
     * Get appSecret
     *
     * @return string 
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }
}
