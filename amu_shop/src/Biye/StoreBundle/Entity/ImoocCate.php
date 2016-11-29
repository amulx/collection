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
 * ImoocCate
 *
 * @ORM\Table(name="imooc_cate")
 * @ORM\Entity
 */


class ImoocCate {

    /**
     * @var string
     *
     * @ORM\Column(name="cName", type="string", length=50, nullable=true)
     */
    private $cName;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", length=10, nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set cName
     *
     * @param string $cName
     * @return ImoocCate
     */
    public function setCName($cName)
    {
        $this->cName = $cName;

        return $this;
    }

    /**
     * Get cName
     *
     * @return string 
     */
    public function getCName()
    {
        return $this->cName;
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

    /**
     * Set status
     *
     * @param integer $status
     * @return ImoocCate
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
}
