<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-3-18
 * Time: 上午10:01
 */

namespace Biye\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImoocUser
 *
 * @ORM\Table(name="imooc_user")
 * @ORM\Entity
 */

class ImoocUser {

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=5, nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="face", type="string", length=50, nullable=true)
     */
    private $face;

    /**
     * @var integer
     *
     * @ORM\Column(name="regTime", type="integer", nullable=true)
     */
    private $regTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="activeFlag", type="smallint", nullable=true)
     */
    private $activeFlag;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set username
     *
     * @param string $username
     * @return ImoocUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return ImoocUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return ImoocUser
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return ImoocUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set face
     *
     * @param string $face
     * @return ImoocUser
     */
    public function setFace($face)
    {
        $this->face = $face;

        return $this;
    }

    /**
     * Get face
     *
     * @return string 
     */
    public function getFace()
    {
        return $this->face;
    }

    /**
     * Set regTime
     *
     * @param integer $regTime
     * @return ImoocUser
     */
    public function setRegTime($regTime)
    {
        $this->regTime = $regTime;

        return $this;
    }

    /**
     * Get regTime
     *
     * @return integer 
     */
    public function getRegTime()
    {
        return $this->regTime;
    }

    /**
     * Set activeFlag
     *
     * @param integer $activeFlag
     * @return ImoocUser
     */
    public function setActiveFlag($activeFlag)
    {
        $this->activeFlag = $activeFlag;

        return $this;
    }

    /**
     * Get activeFlag
     *
     * @return integer 
     */
    public function getActiveFlag()
    {
        return $this->activeFlag;
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
