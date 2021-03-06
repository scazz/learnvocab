<?php

namespace Scazz\LearnVocabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Scazz\LearnVocabBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @ORM\OneToMany(targetEntity="SessionKey", mappedBy="user")
	 */
	protected $keys;

	/**
	 * @ORM\OneToMany(targetEntity="Subject", mappedBy="user")
	 */
	private $subjects;


	/**
	 * @ORM\OneToMany(targetEntity="Topic", mappedBy="user")
	 */
	private $topics;

	/**
	 * @ORM\OneToMany(targetEntity="Vocab", mappedBy="user")
	 */
	private $vocabs;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

	public function getKeys() {
		return $this->keys;
	}

}
