<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcAdminRolePrivilege
 *
 * @ORM\Table(name="xc_admin_role_privilege")
 * @ORM\Entity
 */
class XcAdminRolePrivilege
{
    /**
     * @var integer
     *
     * @ORM\Column(name="admin_role_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $adminRoleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="admin_privilege_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $adminPrivilegeId;



    /**
     * Set adminRoleId
     *
     * @param integer $adminRoleId
     * @return XcAdminRolePrivilege
     */
    public function setAdminRoleId($adminRoleId)
    {
        $this->adminRoleId = $adminRoleId;

        return $this;
    }

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
     * Set adminPrivilegeId
     *
     * @param integer $adminPrivilegeId
     * @return XcAdminRolePrivilege
     */
    public function setAdminPrivilegeId($adminPrivilegeId)
    {
        $this->adminPrivilegeId = $adminPrivilegeId;

        return $this;
    }

    /**
     * Get adminPrivilegeId
     *
     * @return integer 
     */
    public function getAdminPrivilegeId()
    {
        return $this->adminPrivilegeId;
    }
}
