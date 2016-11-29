<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * XcAdmin
 *
 * @ORM\Table(name="xc_admin")
 * @ORM\Entity
 */
class XcAdmin implements AdvancedUserInterface
{
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->account;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->pwd;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="admin_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminId;

    /**
     * @var string
     *
     * @ORM\Column(name="account", type="string", length=32, nullable=true)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd", type="string", length=64, nullable=true)
     */
    private $pwd;

    /**
     * @var integer
     *
     * @ORM\Column(name="role_id", type="smallint", nullable=true)
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(name="real_name", type="string", length=20, nullable=true)
     */
    private $realName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="login_time", type="datetime", nullable=true)
     */
    private $loginTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_active", type="smallint", nullable=true)
     */
    private $isActive;



    /**
     * Get adminId
     *
     * @return integer 
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set account
     *
     * @param string $account
     * @return XcAdmin
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return string 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set pwd
     *
     * @param string $pwd
     * @return XcAdmin
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;

        return $this;
    }

    /**
     * Get pwd
     *
     * @return string 
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * Set roleId
     *
     * @param integer $roleId
     * @return XcAdmin
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set realName
     *
     * @param string $realName
     * @return XcAdmin
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;

        return $this;
    }

    /**
     * Get realName
     *
     * @return string 
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcAdmin
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
     * Set loginTime
     *
     * @param \DateTime $loginTime
     * @return XcAdmin
     */
    public function setLoginTime($loginTime)
    {
        $this->loginTime = $loginTime;

        return $this;
    }

    /**
     * Get loginTime
     *
     * @return \DateTime 
     */
    public function getLoginTime()
    {
        return $this->loginTime;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     * @return XcAdmin
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
}
