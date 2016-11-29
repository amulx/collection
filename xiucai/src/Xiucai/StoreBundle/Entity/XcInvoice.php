<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcInvoice
 *
 * @ORM\Table(name="xc_invoice")
 * @ORM\Entity
 */
class XcInvoice
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
     * @var integer
     *
     * @ORM\Column(name="member_id", type="bigint", nullable=true)
     */
    private $memberId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=512, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=8, nullable=true)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=16, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="recipient", type="string", length=255, nullable=true)
     */
    private $recipient;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="bigint", nullable=true)
     */
    private $orderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="express_name", type="string", length=64, nullable=true)
     */
    private $expressName;

    /**
     * @var string
     *
     * @ORM\Column(name="express_no", type="string", length=32, nullable=true)
     */
    private $expressNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_time", type="datetime", nullable=true)
     */
    private $sendTime;



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
     * @return XcInvoice
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
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcInvoice
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
     * Set amount
     *
     * @param string $amount
     * @return XcInvoice
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
     * Set title
     *
     * @param string $title
     * @return XcInvoice
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return XcInvoice
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return XcInvoice
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return XcInvoice
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set recipient
     *
     * @param string $recipient
     * @return XcInvoice
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient
     *
     * @return string 
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set orderId
     *
     * @param integer $orderId
     * @return XcInvoice
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return XcInvoice
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
     * Set status
     *
     * @param integer $status
     * @return XcInvoice
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
     * Set expressName
     *
     * @param string $expressName
     * @return XcInvoice
     */
    public function setExpressName($expressName)
    {
        $this->expressName = $expressName;

        return $this;
    }

    /**
     * Get expressName
     *
     * @return string 
     */
    public function getExpressName()
    {
        return $this->expressName;
    }

    /**
     * Set expressNo
     *
     * @param string $expressNo
     * @return XcInvoice
     */
    public function setExpressNo($expressNo)
    {
        $this->expressNo = $expressNo;

        return $this;
    }

    /**
     * Get expressNo
     *
     * @return string 
     */
    public function getExpressNo()
    {
        return $this->expressNo;
    }

    /**
     * Set sendTime
     *
     * @param \DateTime $sendTime
     * @return XcInvoice
     */
    public function setSendTime($sendTime)
    {
        $this->sendTime = $sendTime;

        return $this;
    }

    /**
     * Get sendTime
     *
     * @return \DateTime 
     */
    public function getSendTime()
    {
        return $this->sendTime;
    }
}
