<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-3-30
 * Time: 下午3:04
 */

namespace Biye\StoreBundle\Entity;


use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role implements RoleInterface{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="ImoocAdmin", mappedBy="roles")
     */
    private $imoocAdmin;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
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
     * Set name
     *
     * @param string $name
     * @return Role
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
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Add imoocAdmin
     *
     * @param \Biye\StoreBundle\Entity\ImoocAdmin $imoocAdmin
     * @return Role
     */
    public function addImoocAdmin(\Biye\StoreBundle\Entity\ImoocAdmin $imoocAdmin)
    {
        $this->imoocAdmin[] = $imoocAdmin;

        return $this;
    }

    /**
     * Remove imoocAdmin
     *
     * @param \Biye\StoreBundle\Entity\ImoocAdmin $imoocAdmin
     */
    public function removeImoocAdmin(\Biye\StoreBundle\Entity\ImoocAdmin $imoocAdmin)
    {
        $this->imoocAdmin->removeElement($imoocAdmin);
    }

    /**
     * Get imoocAdmin
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImoocAdmin()
    {
        return $this->imoocAdmin;
    }
}
