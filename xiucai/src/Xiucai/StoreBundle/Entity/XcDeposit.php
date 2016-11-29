<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcDeposit
 *
 * @ORM\Table(name="xc_deposit")
 * @ORM\Entity
 */
class XcDeposit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="third_order_id", type="string", length=16, nullable=true)
     */
    private $thirdOrderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_id", type="bigint", nullable=true)
     */
    private $memberId;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="virtual_amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $virtualAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approve_time", type="datetime", nullable=true)
     */
    private $approveTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="promotion_id", type="integer", nullable=true)
     */
    private $promotionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="operator_id", type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=32, nullable=true)
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="sign", type="string", length=255, nullable=true)
     */
    private $sign;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="method", type="smallint", nullable=true)
     */
    private $method;



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
     * Set thirdOrderId
     *
     * @param string $thirdOrderId
     * @return XcDeposit
     */
    public function setThirdOrderId($thirdOrderId)
    {
        $this->thirdOrderId = $thirdOrderId;

        return $this;
    }

    /**
     * Get thirdOrderId
     *
     * @return string 
     */
    public function getThirdOrderId()
    {
        return $this->thirdOrderId;
    }

    /**
     * Set memberId
     *
     * @param integer $memberId
     * @return XcDeposit
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
     * Set amount
     *
     * @param string $amount
     * @return XcDeposit
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set virtualAmount
     *
     * @param string $virtualAmount
     * @return XcDeposit
     */
    public function setVirtualAmount($virtualAmount)
    {
        $this->virtualAmount = $virtualAmount;

        return $this;
    }

    /**
     * Get virtualAmount
     *
     * @return string 
     */
    public function getVirtualAmount()
    {
        return $this->virtualAmount;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return XcDeposit
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
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcDeposit
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
     * Set approveTime
     *
     * @param \DateTime $approveTime
     * @return XcDeposit
     */
    public function setApproveTime($approveTime)
    {
        $this->approveTime = $approveTime;

        return $this;
    }

    /**
     * Get approveTime
     *
     * @return \DateTime 
     */
    public function getApproveTime()
    {
        return $this->approveTime;
    }

    /**
     * Set promotionId
     *
     * @param integer $promotionId
     * @return XcDeposit
     */
    public function setPromotionId($promotionId)
    {
        $this->promotionId = $promotionId;

        return $this;
    }

    /**
     * Get promotionId
     *
     * @return integer 
     */
    public function getPromotionId()
    {
        return $this->promotionId;
    }

    /**
     * Set operatorId
     *
     * @param integer $operatorId
     * @return XcDeposit
     */
    public function setOperatorId($operatorId)
    {
        $this->operatorId = $operatorId;

        return $this;
    }

    /**
     * Get operatorId
     *
     * @return integer 
     */
    public function getOperatorId()
    {
        return $this->operatorId;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return XcDeposit
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return XcDeposit
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
     * Set sign
     *
     * @param string $sign
     * @return XcDeposit
     */
    public function setSign($sign)
    {
        $this->sign = $sign;

        return $this;
    }

    /**
     * Get sign
     *
     * @return string 
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return XcDeposit
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set method
     *
     * @param integer $method
     * @return XcDeposit
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return integer 
     */
    public function getMethod()
    {
        return $this->method;
    }
}
