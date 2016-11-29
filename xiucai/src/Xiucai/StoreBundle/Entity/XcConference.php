<?php

namespace Xiucai\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XcConference
 *
 * @ORM\Table(name="xc_conference", indexes={@ORM\Index(name="i_s_time", columns={"schedule_time"}), @ORM\Index(name="i_status", columns={"conference_status"})})
 * @ORM\Entity
 */
class XcConference
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="conference_status", type="smallint", nullable=true)
     */
    private $conferenceStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_attendee", type="integer", nullable=true)
     */
    private $maxAttendee;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="schedule_time", type="datetime", nullable=true)
     */
    private $scheduleTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="room_type", type="integer", nullable=true)
     */
    private $roomType;

    /**
     * @var integer
     *
     * @ORM\Column(name="privacy", type="smallint", nullable=true)
     */
    private $privacy;

    /**
     * @var string
     *
     * @ORM\Column(name="room_token", type="string", length=64, nullable=true)
     */
    private $roomToken;

    /**
     * @var string
     *
     * @ORM\Column(name="room_url", type="string", length=255, nullable=true)
     */
    private $roomUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_record", type="smallint", nullable=true)
     */
    private $isRecord;

    /**
     * @var string
     *
     * @ORM\Column(name="record_url", type="string", length=255, nullable=true)
     */
    private $recordUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_attendee", type="integer", nullable=true)
     */
    private $numAttendee;

    /**
     * @var integer
     *
     * @ORM\Column(name="video_protocol", type="smallint", nullable=true)
     */
    private $videoProtocol;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_status", type="string", length=255, nullable=true)
     */
    private $customStatus;



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
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return XcConference
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
     * @return XcConference
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
     * Set title
     *
     * @param string $title
     * @return XcConference
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
     * Set status
     *
     * @param integer $status
     * @return XcConference
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
     * Set conferenceStatus
     *
     * @param integer $conferenceStatus
     * @return XcConference
     */
    public function setConferenceStatus($conferenceStatus)
    {
        $this->conferenceStatus = $conferenceStatus;

        return $this;
    }

    /**
     * Get conferenceStatus
     *
     * @return integer 
     */
    public function getConferenceStatus()
    {
        return $this->conferenceStatus;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return XcConference
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
     * Set maxAttendee
     *
     * @param integer $maxAttendee
     * @return XcConference
     */
    public function setMaxAttendee($maxAttendee)
    {
        $this->maxAttendee = $maxAttendee;

        return $this;
    }

    /**
     * Get maxAttendee
     *
     * @return integer 
     */
    public function getMaxAttendee()
    {
        return $this->maxAttendee;
    }

    /**
     * Set scheduleTime
     *
     * @param \DateTime $scheduleTime
     * @return XcConference
     */
    public function setScheduleTime($scheduleTime)
    {
        $this->scheduleTime = $scheduleTime;

        return $this;
    }

    /**
     * Get scheduleTime
     *
     * @return \DateTime 
     */
    public function getScheduleTime()
    {
        return $this->scheduleTime;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return XcConference
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return XcConference
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return XcConference
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set roomType
     *
     * @param integer $roomType
     * @return XcConference
     */
    public function setRoomType($roomType)
    {
        $this->roomType = $roomType;

        return $this;
    }

    /**
     * Get roomType
     *
     * @return integer 
     */
    public function getRoomType()
    {
        return $this->roomType;
    }

    /**
     * Set privacy
     *
     * @param integer $privacy
     * @return XcConference
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * Get privacy
     *
     * @return integer 
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Set roomToken
     *
     * @param string $roomToken
     * @return XcConference
     */
    public function setRoomToken($roomToken)
    {
        $this->roomToken = $roomToken;

        return $this;
    }

    /**
     * Get roomToken
     *
     * @return string 
     */
    public function getRoomToken()
    {
        return $this->roomToken;
    }

    /**
     * Set roomUrl
     *
     * @param string $roomUrl
     * @return XcConference
     */
    public function setRoomUrl($roomUrl)
    {
        $this->roomUrl = $roomUrl;

        return $this;
    }

    /**
     * Get roomUrl
     *
     * @return string 
     */
    public function getRoomUrl()
    {
        return $this->roomUrl;
    }

    /**
     * Set isRecord
     *
     * @param integer $isRecord
     * @return XcConference
     */
    public function setIsRecord($isRecord)
    {
        $this->isRecord = $isRecord;

        return $this;
    }

    /**
     * Get isRecord
     *
     * @return integer 
     */
    public function getIsRecord()
    {
        return $this->isRecord;
    }

    /**
     * Set recordUrl
     *
     * @param string $recordUrl
     * @return XcConference
     */
    public function setRecordUrl($recordUrl)
    {
        $this->recordUrl = $recordUrl;

        return $this;
    }

    /**
     * Get recordUrl
     *
     * @return string 
     */
    public function getRecordUrl()
    {
        return $this->recordUrl;
    }

    /**
     * Set numAttendee
     *
     * @param integer $numAttendee
     * @return XcConference
     */
    public function setNumAttendee($numAttendee)
    {
        $this->numAttendee = $numAttendee;

        return $this;
    }

    /**
     * Get numAttendee
     *
     * @return integer 
     */
    public function getNumAttendee()
    {
        return $this->numAttendee;
    }

    /**
     * Set videoProtocol
     *
     * @param integer $videoProtocol
     * @return XcConference
     */
    public function setVideoProtocol($videoProtocol)
    {
        $this->videoProtocol = $videoProtocol;

        return $this;
    }

    /**
     * Get videoProtocol
     *
     * @return integer 
     */
    public function getVideoProtocol()
    {
        return $this->videoProtocol;
    }

    /**
     * Set customStatus
     *
     * @param string $customStatus
     * @return XcConference
     */
    public function setCustomStatus($customStatus)
    {
        $this->customStatus = $customStatus;

        return $this;
    }

    /**
     * Get customStatus
     *
     * @return string 
     */
    public function getCustomStatus()
    {
        return $this->customStatus;
    }
}
