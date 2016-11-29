<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcOrder
 *
 * @ORM\Table(name="xc_order")
 * @ORM\Entity
 */
class XcOrder
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
     * @var string
     *
     * @ORM\Column(name="order_code", type="string", length=64, nullable=true)
     */
    private $orderCode;

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
     * @ORM\Column(name="pay_amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $payAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_gateway", type="string", length=16, nullable=true)
     */
    private $paymentGateway;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=64, nullable=true)
     */
    private $transactionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_status", type="smallint", nullable=true)
     */
    private $transactionStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_status", type="smallint", nullable=true)
     */
    private $orderStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="operator_id", type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=32, nullable=true)
     */
    private $ipAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=true)
     */
    private $updateTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="notify_time", type="datetime", nullable=true)
     */
    private $notifyTime;

    /**
     * @var string
     *
     * @ORM\Column(name="notify_type", type="string", length=32, nullable=true)
     */
    private $notifyType;

    /**
     * @var string
     *
     * @ORM\Column(name="notify_id", type="string", length=64, nullable=true)
     */
    private $notifyId;

    /**
     * @var string
     *
     * @ORM\Column(name="sign_type", type="string", length=16, nullable=true)
     */
    private $signType;

    /**
     * @var string
     *
     * @ORM\Column(name="sign", type="string", length=255, nullable=true)
     */
    private $sign;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;



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
     * Set orderCode
     *
     * @param string $orderCode
     * @return XcOrder
     */
    public function setOrderCode($orderCode)
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    /**
     * Get orderCode
     *
     * @return string 
     */
    public function getOrderCode()
    {
        return $this->orderCode;
    }

    /**
     * Set memberId
     *
     * @param integer $memberId
     * @return XcOrder
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
     * @return XcOrder
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
     * Set payAmount
     *
     * @param string $payAmount
     * @return XcOrder
     */
    public function setPayAmount($payAmount)
    {
        $this->payAmount = $payAmount;

        return $this;
    }

    /**
     * Get payAmount
     *
     * @return string 
     */
    public function getPayAmount()
    {
        return $this->payAmount;
    }

    /**
     * Set paymentGateway
     *
     * @param string $paymentGateway
     * @return XcOrder
     */
    public function setPaymentGateway($paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;

        return $this;
    }

    /**
     * Get paymentGateway
     *
     * @return string 
     */
    public function getPaymentGateway()
    {
        return $this->paymentGateway;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return XcOrder
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set transactionStatus
     *
     * @param integer $transactionStatus
     * @return XcOrder
     */
    public function setTransactionStatus($transactionStatus)
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }

    /**
     * Get transactionStatus
     *
     * @return integer 
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * Set orderStatus
     *
     * @param integer $orderStatus
     * @return XcOrder
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return integer 
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set operatorId
     *
     * @param integer $operatorId
     * @return XcOrder
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
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return XcOrder
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcOrder
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
     * Set updateTime
     *
     * @param \DateTime $updateTime
     * @return XcOrder
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return \DateTime 
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return XcOrder
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
     * Set notifyTime
     *
     * @param \DateTime $notifyTime
     * @return XcOrder
     */
    public function setNotifyTime($notifyTime)
    {
        $this->notifyTime = $notifyTime;

        return $this;
    }

    /**
     * Get notifyTime
     *
     * @return \DateTime 
     */
    public function getNotifyTime()
    {
        return $this->notifyTime;
    }

    /**
     * Set notifyType
     *
     * @param string $notifyType
     * @return XcOrder
     */
    public function setNotifyType($notifyType)
    {
        $this->notifyType = $notifyType;

        return $this;
    }

    /**
     * Get notifyType
     *
     * @return string 
     */
    public function getNotifyType()
    {
        return $this->notifyType;
    }

    /**
     * Set notifyId
     *
     * @param string $notifyId
     * @return XcOrder
     */
    public function setNotifyId($notifyId)
    {
        $this->notifyId = $notifyId;

        return $this;
    }

    /**
     * Get notifyId
     *
     * @return string 
     */
    public function getNotifyId()
    {
        return $this->notifyId;
    }

    /**
     * Set signType
     *
     * @param string $signType
     * @return XcOrder
     */
    public function setSignType($signType)
    {
        $this->signType = $signType;

        return $this;
    }

    /**
     * Get signType
     *
     * @return string 
     */
    public function getSignType()
    {
        return $this->signType;
    }

    /**
     * Set sign
     *
     * @param string $sign
     * @return XcOrder
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
     * Set status
     *
     * @param integer $status
     * @return XcOrder
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
}
