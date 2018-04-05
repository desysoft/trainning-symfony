<?php
// src/OC/platformBundle/Entity/AdvertSkill.php

namespace OC\platformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oc_advert_skill")
 */
class AdvertSkill
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="level", type="string", length=255)
   */
  private $level;

  /**
   * @ORM\ManyToOne(targetEntity="OC\platformBundle\Entity\Advert")
   * @ORM\JoinColumn(nullable=false)
   */
  private $advert;

  /**
   * @ORM\ManyToOne(targetEntity="OC\platformBundle\Entity\Skill")
   * @ORM\JoinColumn(nullable=false)
   */
  private $skill;
  

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level.
     *
     * @param string $level
     *
     * @return AdvertSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set advert.
     *
     * @param \OC\platformBundle\Entity\Advert $advert
     *
     * @return AdvertSkill
     */
    public function setAdvert(\OC\platformBundle\Entity\Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert.
     *
     * @return \OC\platformBundle\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set skill.
     *
     * @param \OC\platformBundle\Entity\Skill $skill
     *
     * @return AdvertSkill
     */
    public function setSkill(\OC\platformBundle\Entity\Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill.
     *
     * @return \OC\platformBundle\Entity\Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
