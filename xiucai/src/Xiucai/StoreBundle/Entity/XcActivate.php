<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcActivate
 *
 * @ORM\Table(name="xc_activate")
 * @ORM\Entity
 */
class XcActivate
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
     * @ORM\Column(name="member_id", type="bigint", nullable=true)
     */
    private $memberId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="cellphone", type="string", length=16, nullable=true)
     */
    private $cellphone;

    /**
     * @var string
     *
     * @ORM\Column(name="activate_code", type="string", length=64, nullable=true)
     */
    private $activateCode;

    /**
     * @var string
     *
     * @ORM\Column(name="expired_date", type="string", length=32, nullable=true)
     */
    private $expiredDate;



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
     * Set memberId
     *
     * @param integer $memberId
     * @return XcActivate
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
     * Set email
     *
     * @param string $email
     * @return XcActivate
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
     * Set cellphone
     *
     * @param string $cellphone
     * @return XcActivate
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return string 
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set activateCode
     *
     * @param string $activateCode
     * @return XcActivate
     */
    public function setActivateCode($activateCode)
    {
        $this->activateCode = $activateCode;

        return $this;
    }

    /**
     * Get activateCode
     *
     * @return string 
     */
    public function getActivateCode()
    {
        return $this->activateCode;
    }

    /**
     * Set expiredDate
     *
     * @param string $expiredDate
     * @return XcActivate
     */
    public function setExpiredDate($expiredDate)
    {
        $this->expiredDate = $expiredDate;

        return $this;
    }

    /**
     * Get expiredDate
     *
     * @return string 
     */
    public function getExpiredDate()
    {
        return $this->expiredDate;
    }
}
