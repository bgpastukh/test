<?php

namespace TestProject\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="referral_transition")
 */
class ReferralTransition
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $ip;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="referer_id", referencedColumnName="id")
     **/
    protected $referer;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ReferralTransition
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return ReferralTransition
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set referer
     *
     * @param \TestProject\SecurityBundle\Entity\User $referer
     *
     * @return ReferralTransition
     */
    public function setReferer(\TestProject\SecurityBundle\Entity\User $referer = null)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get referer
     *
     * @return \TestProject\SecurityBundle\Entity\User
     */
    public function getReferer()
    {
        return $this->referer;
    }
}
