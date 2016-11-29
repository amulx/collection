<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcCareerTitle
 *
 * @ORM\Table(name="xc_career_title")
 * @ORM\Entity
 */
class XcCareerTitle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="title_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $titleId;

    /**
     * @var string
     *
     * @ORM\Column(name="title_name", type="string", length=20, nullable=true)
     */
    private $titleName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;



    /**
     * Get titleId
     *
     * @return integer 
     */
    public function getTitleId()
    {
        return $this->titleId;
    }

    /**
     * Set titleName
     *
     * @param string $titleName
     * @return XcCareerTitle
     */
    public function setTitleName($titleName)
    {
        $this->titleName = $titleName;

        return $this;
    }

    /**
     * Get titleName
     *
     * @return string 
     */
    public function getTitleName()
    {
        return $this->titleName;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcCareerTitle
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
}
