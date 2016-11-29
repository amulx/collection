<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * XcMember
 *
 * @ORM\Table(name="xc_member", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"third_user_id"})})
 * @ORM\Entity
 */
class XcMember implements AdvancedUserInterface
{
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->nickname;
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
    public function getRoles()
    {
        return array('ROLE_USER');
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
        return $this->isActivate;
    }

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
     * @ORM\Column(name="third_user_id", type="string", length=36, nullable=true)
     */
    private $thirdUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @var integer
     *
     * @ORM\Column(name="source_id", type="smallint", nullable=true)
     */
    private $sourceId;

    /**
     * @var string
     *
     * @ORM\Column(name="screen_name", type="string", length=255, nullable=true)
     */
    private $screenName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="cellphone", type="string", length=16, nullable=true)
     */
    private $cellphone;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="brief", type="string", length=64, nullable=true)
     */
    private $brief;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modify_time", type="datetime", nullable=true)
     */
    private $modifyTime;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_large", type="string", length=255, nullable=true)
     */
    private $avatarLarge;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_activate", type="smallint", nullable=true)
     */
    private $isActivate;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=true)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=32, nullable=true)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=64, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="work_field", type="string", length=64, nullable=true)
     */
    private $workField;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=64, nullable=true)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=128, nullable=true)
     */
    private $company;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_registed", type="smallint", nullable=true)
     */
    private $isRegisted;

    /**
     * @var string
     *
     * @ORM\Column(name="tracks", type="string", length=255, nullable=true)
     */
    private $tracks;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="register_ip", type="string", length=32, nullable=true)
     */
    private $registerIp;



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
     * Set thirdUserId
     *
     * @param string $thirdUserId
     * @return XcMember
     */
    public function setThirdUserId($thirdUserId)
    {
        $this->thirdUserId = $thirdUserId;

        return $this;
    }

    /**
     * Get thirdUserId
     *
     * @return string 
     */
    public function getThirdUserId()
    {
        return $this->thirdUserId;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return XcMember
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set sourceId
     *
     * @param integer $sourceId
     * @return XcMember
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * Get sourceId
     *
     * @return integer 
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Set screenName
     *
     * @param string $screenName
     * @return XcMember
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string 
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return XcMember
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return XcMember
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set cellphone
     *
     * @param string $cellphone
     * @return XcMember
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return string 
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return XcMember
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return XcMember
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set brief
     *
     * @param string $brief
     * @return XcMember
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * Get brief
     *
     * @return string 
     */
    public function getBrief()
    {
        return $this->brief;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcMember
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
     * Set modifyTime
     *
     * @param \DateTime $modifyTime
     * @return XcMember
     */
    public function setModifyTime($modifyTime)
    {
        $this->modifyTime = $modifyTime;

        return $this;
    }

    /**
     * Get modifyTime
     *
     * @return \DateTime 
     */
    public function getModifyTime()
    {
        return $this->modifyTime;
    }

    /**
     * Set avatarLarge
     *
     * @param string $avatarLarge
     * @return XcMember
     */
    public function setAvatarLarge($avatarLarge)
    {
        $this->avatarLarge = $avatarLarge;

        return $this;
    }

    /**
     * Get avatarLarge
     *
     * @return string 
     */
    public function getAvatarLarge()
    {
        return $this->avatarLarge;
    }

    /**
     * Set isActivate
     *
     * @param integer $isActivate
     * @return XcMember
     */
    public function setIsActivate($isActivate)
    {
        $this->isActivate = $isActivate;

        return $this;
    }

    /**
     * Get isActivate
     *
     * @return integer 
     */
    public function getIsActivate()
    {
        return $this->isActivate;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return XcMember
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set province
     *
     * @param string $province
     * @return XcMember
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return string 
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return XcMember
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set workField
     *
     * @param string $workField
     * @return XcMember
     */
    public function setWorkField($workField)
    {
        $this->workField = $workField;

        return $this;
    }

    /**
     * Get workField
     *
     * @return string 
     */
    public function getWorkField()
    {
        return $this->workField;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return XcMember
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return XcMember
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set isRegisted
     *
     * @param integer $isRegisted
     * @return XcMember
     */
    public function setIsRegisted($isRegisted)
    {
        $this->isRegisted = $isRegisted;

        return $this;
    }

    /**
     * Get isRegisted
     *
     * @return integer 
     */
    public function getIsRegisted()
    {
        return $this->isRegisted;
    }

    /**
     * Set tracks
     *
     * @param string $tracks
     * @return XcMember
     */
    public function setTracks($tracks)
    {
        $this->tracks = $tracks;

        return $this;
    }

    /**
     * Get tracks
     *
     * @return string 
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return XcMember
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return XcMember
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
     * Set registerIp
     *
     * @param string $registerIp
     * @return XcMember
     */
    public function setRegisterIp($registerIp)
    {
        $this->registerIp = $registerIp;

        return $this;
    }

    /**
     * Get registerIp
     *
     * @return string 
     */
    public function getRegisterIp()
    {
        return $this->registerIp;
    }
}
