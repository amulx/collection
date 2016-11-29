<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-5-18
 * Time: 下午8:27
 */

namespace Biye\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * ImoocCate
 *
 * @ORM\Table(name="imooc_car")
 * @ORM\Entity
 */

class ImoocCar {

    /**
     * @var integer
     *
     * @ORM\Column(name="car", type="integer")
     */
    private $car;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="iden", type="string", length=70, nullable=true)
     */
    private $iden;


    /**
     * @var string
     *
     * @ORM\Column(name="purchaser", type="string", length=70, nullable=true)
     */
    private $purchaser;




    /**
     * Set car
     *
     * @param integer $car
     * @return ImoocCar
     */
    public function setCar($car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return integer 
     */
    public function getCar()
    {
        return $this->car;
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
     * Set identity
     *
     * @param string $identity
     * @return ImoocCar
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * Get identity
     *
     * @return string 
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Set iden
     *
     * @param string $iden
     * @return ImoocCar
     */
    public function setIden($iden)
    {
        $this->iden = $iden;

        return $this;
    }

    /**
     * Get iden
     *
     * @return string 
     */
    public function getIden()
    {
        return $this->iden;
    }

    /**
     * Set purchaser
     *
     * @param string $purchaser
     * @return ImoocCar
     */
    public function setPurchaser($purchaser)
    {
        $this->purchaser = $purchaser;

        return $this;
    }

    /**
     * Get purchaser
     *
     * @return string 
     */
    public function getPurchaser()
    {
        return $this->purchaser;
    }
}
