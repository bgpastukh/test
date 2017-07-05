<?php

namespace TestProject\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="User with this email already exists")
 * @ORM\EntityListeners({"TestProject\SecurityBundle\EventListener\UserListener"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\GreaterThan(6)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $confirmationToken;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isConfirmed;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $referralCode;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="referer")
     * @ORM\JoinColumn(name="referer_id", referencedColumnName="id")
     **/
    protected $referer;

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set referralCode
     *
     * @param string $referralCode
     *
     * @return User
     */
    public function setReferralCode($referralCode)
    {
        $this->referralCode = $referralCode;

        return $this;
    }

    /**
     * Get referralCode
     *
     * @return string
     */
    public function getReferralCode()
    {
        return $this->referralCode;
    }

    /**
     * Set referer
     *
     * @param \TestProject\SecurityBundle\Entity\User $referer
     *
     * @return User
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
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set password
     *
     * @param string $password
     *
     * @return User
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
     * Set confirmationToken
     *
     * @param string $confirmationToken
     *
     * @return User
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Get confirmationToken
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return User
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return boolean
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    // When our system will be bigger, we can use this methods. Now they are written to implement UserInterface.
    public function getSalt()
    {
    }

    public function getRoles()
    {
    }

    public function getUsername()
    {
    }

    public function eraseCredentials()
    {
    }
}
