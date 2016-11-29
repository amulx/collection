<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcAdminPrivilege
 *
 * @ORM\Table(name="xc_admin_privilege")
 * @ORM\Entity
 */
class XcAdminPrivilege
{
    /**
     * @var integer
     *
     * @ORM\Column(name="admin_privilege_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminPrivilegeId;

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
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=20, nullable=false)
     */
    private $controller;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=20, nullable=false)
     */
    private $action;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu_show", type="integer", nullable=true)
     */
    private $menuShow;



    /**
     * Get adminPrivilegeId
     *
     * @return integer 
     */
    public function getAdminPrivilegeId()
    {
        return $this->adminPrivilegeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return XcAdminPrivilege
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
     * @return XcAdminPrivilege
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
     * Set controller
     *
     * @param string $controller
     * @return XcAdminPrivilege
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller
     *
     * @return string 
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return XcAdminPrivilege
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return XcAdminPrivilege
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set menuShow
     *
     * @param integer $menuShow
     * @return XcAdminPrivilege
     */
    public function setMenuShow($menuShow)
    {
        $this->menuShow = $menuShow;

        return $this;
    }

    /**
     * Get menuShow
     *
     * @return integer 
     */
    public function getMenuShow()
    {
        return $this->menuShow;
    }
}
