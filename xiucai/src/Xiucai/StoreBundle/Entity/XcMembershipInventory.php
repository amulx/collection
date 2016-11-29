<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcMembershipInventory
 *
 * @ORM\Table(name="xc_membership_inventory")
 * @ORM\Entity
 */
class XcMembershipInventory
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
     * @var integer
     *
     * @ORM\Column(name="service_id", type="bigint", nullable=true)
     */
    private $serviceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="service_level", type="smallint", nullable=true)
     */
    private $serviceLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="smallint", nullable=true)
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_activate", type="smallint", nullable=true)
     */
    private $isActivate;



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
     * @return XcMembershipInventory
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
     * Set serviceId
     *
     * @param integer $serviceId
     * @return XcMembershipInventory
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    /**
     * Get serviceId
     *
     * @return integer 
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Set serviceLevel
     *
     * @param integer $serviceLevel
     * @return XcMembershipInventory
     */
    public function setServiceLevel($serviceLevel)
    {
        $this->serviceLevel = $serviceLevel;

        return $this;
    }

    /**
     * Get serviceLevel
     *
     * @return integer 
     */
    public function getServiceLevel()
    {
        return $this->serviceLevel;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return XcMembershipInventory
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcMembershipInventory
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return XcMembershipInventory
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return XcMembershipInventory
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set isActivate
     *
     * @param integer $isActivate
     * @return XcMembershipInventory
     */
    public function setIsActivate($isActivate)
    {
        $this->isActivate = $isActivate;

        return $this;
    }

    /**
     * Get isActivate
     *
     * @return integer 
     */
    public function getIsActivate()
    {
        return $this->isActivate;
    }
}
