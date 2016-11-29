<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcAdminRole
 *
 * @ORM\Table(name="xc_admin_role")
 * @ORM\Entity
 */
class XcAdminRole
{
    /**
     * @var integer
     *
     * @ORM\Column(name="admin_role_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminRoleId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="active", type="integer", nullable=true)
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="descrip", type="string", length=255, nullable=true)
     */
    private $descrip;



    /**
     * Get adminRoleId
     *
     * @return integer 
     */
    public function getAdminRoleId()
    {
        return $this->adminRoleId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return XcAdminRole
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
     * Set active
     *
     * @param integer $active
     * @return XcAdminRole
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return integer 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set parent
     *
     * @param integer $parent
     * @return XcAdminRole
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set descrip
     *
     * @param string $descrip
     * @return XcAdminRole
     */
    public function setDescrip($descrip)
    {
        $this->descrip = $descrip;

        return $this;
    }

    /**
     * Get descrip
     *
     * @return string 
     */
    public function getDescrip()
    {
        return $this->descrip;
    }
}
