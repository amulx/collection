<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcOauthinfo
 *
 * @ORM\Table(name="xc_oauthinfo", indexes={@ORM\Index(name="appkey", columns={"appkey"}), @ORM\Index(name="uid", columns={"uid"})})
 * @ORM\Entity
 */
class XcOauthinfo
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
     * @var integer
     *
     * @ORM\Column(name="source_id", type="smallint", nullable=true)
     */
    private $sourceId;

    /**
     * @var string
     *
     * @ORM\Column(name="appkey", type="string", length=32, nullable=true)
     */
    private $appkey;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="bigint", nullable=true)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=64, nullable=true)
     */
    private $accessToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="token_expiration", type="datetime", nullable=true)
     */
    private $tokenExpiration;

    /**
     * @var integer
     *
     * @ORM\Column(name="verified", type="smallint", nullable=true)
     */
    private $verified;



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
     * Set sourceId
     *
     * @param integer $sourceId
     * @return XcOauthinfo
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
     * Set appkey
     *
     * @param string $appkey
     * @return XcOauthinfo
     */
    public function setAppkey($appkey)
    {
        $this->appkey = $appkey;

        return $this;
    }

    /**
     * Get appkey
     *
     * @return string 
     */
    public function getAppkey()
    {
        return $this->appkey;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return XcOauthinfo
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return XcOauthinfo
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set tokenExpiration
     *
     * @param \DateTime $tokenExpiration
     * @return XcOauthinfo
     */
    public function setTokenExpiration($tokenExpiration)
    {
        $this->tokenExpiration = $tokenExpiration;

        return $this;
    }

    /**
     * Get tokenExpiration
     *
     * @return \DateTime 
     */
    public function getTokenExpiration()
    {
        return $this->tokenExpiration;
    }

    /**
     * Set verified
     *
     * @param integer $verified
     * @return XcOauthinfo
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get verified
     *
     * @return integer 
     */
    public function getVerified()
    {
        return $this->verified;
    }
}
