<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcCategory
 *
 * @ORM\Table(name="xc_category")
 * @ORM\Entity
 */
class XcCategory
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
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true)
     */
    private $categoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=256, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_category_id", type="integer", nullable=true)
     */
    private $parentCategoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=128, nullable=true)
     */
    private $tags;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="zindex", type="smallint", nullable=true)
     */
    private $zindex;



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
     * Set categoryId
     *
     * @param integer $categoryId
     * @return XcCategory
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return XcCategory
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
     * @return XcCategory
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
     * Set parentCategoryId
     *
     * @param integer $parentCategoryId
     * @return XcCategory
     */
    public function setParentCategoryId($parentCategoryId)
    {
        $this->parentCategoryId = $parentCategoryId;

        return $this;
    }

    /**
     * Get parentCategoryId
     *
     * @return integer 
     */
    public function getParentCategoryId()
    {
        return $this->parentCategoryId;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return XcCategory
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return XcCategory
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
     * Set zindex
     *
     * @param integer $zindex
     * @return XcCategory
     */
    public function setZindex($zindex)
    {
        $this->zindex = $zindex;

        return $this;
    }

    /**
     * Get zindex
     *
     * @return integer 
     */
    public function getZindex()
    {
        return $this->zindex;
    }
}
