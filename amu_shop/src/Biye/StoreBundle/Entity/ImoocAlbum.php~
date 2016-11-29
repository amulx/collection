<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-3-18
 * Time: 上午9:57
 */

namespace Biye\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImoocAlbum
 *
 * @ORM\Table(name="imooc_album")
 * @ORM\Entity
 */

class ImoocAlbum {

    /**
     * @var integer
     *
     * @ORM\Column(name="pid", type="integer", nullable=true)
     */
    private $pid;

    /**
     * @var string
     *
     * @ORM\Column(name="albumPath", type="string", length=60, nullable=true)
     */
    private $albumPath;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set pid
     *
     * @param integer $pid
     * @return ImoocAlbum
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return integer 
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set albumPath
     *
     * @param string $albumPath
     * @return ImoocAlbum
     */
    public function setAlbumPath($albumPath)
    {
        $this->albumPath = $albumPath;

        return $this;
    }

    /**
     * Get albumPath
     *
     * @return string 
     */
    public function getAlbumPath()
    {
        return $this->albumPath;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
