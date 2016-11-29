<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcBilling
 *
 * @ORM\Table(name="xc_billing")
 * @ORM\Entity
 */
class XcBilling
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
     * @ORM\Column(name="current_balance", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $currentBalance;

    /**
     * @var string
     *
     * @ORM\Column(name="total_amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="virtual_amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $virtualAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="total_invoice", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalInvoice;

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
     * @ORM\Column(name="contact_number", type="string", length=16, nullable=true)
     */
    private $contactNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_name", type="string", length=128, nullable=true)
     */
    private $contactName;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $companyName;



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
     * @return XcBilling
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
     * @return XcBilling
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
     * Set currentBalance
     *
     * @param string $currentBalance
     * @return XcBilling
     */
    public function setCurrentBalance($currentBalance)
    {
        $this->currentBalance = $currentBalance;

        return $this;
    }

    /**
     * Get currentBalance
     *
     * @return string 
     */
    public function getCurrentBalance()
    {
        return $this->currentBalance;
    }

    /**
     * Set totalAmount
     *
     * @param string $totalAmount
     * @return XcBilling
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return string 
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set virtualAmount
     *
     * @param string $virtualAmount
     * @return XcBilling
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
     * Set totalInvoice
     *
     * @param string $totalInvoice
     * @return XcBilling
     */
    public function setTotalInvoice($totalInvoice)
    {
        $this->totalInvoice = $totalInvoice;

        return $this;
    }

    /**
     * Get totalInvoice
     *
     * @return string 
     */
    public function getTotalInvoice()
    {
        return $this->totalInvoice;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return XcBilling
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
     * @return XcBilling
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
     * Set contactNumber
     *
     * @param string $contactNumber
     * @return XcBilling
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return string 
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return XcBilling
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string 
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return XcBilling
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }
}
