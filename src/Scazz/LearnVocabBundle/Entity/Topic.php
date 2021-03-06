<?php

namespace Scazz\LearnVocabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Topic
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Scazz\LearnVocabBundle\Entity\TopicRepository")
 *
 * @ExclusionPolicy("all")
 */
class Topic
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
	 *
	 * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
	 * @Expose
     */
    private $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Subject", inversedBy="topics")
	 * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
	 *
	 */
	private $subject;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="templateSubjectName", type="string", length=255, nullable=true)
	 */
	private $templateSubjectName;

	/**
	 * @ORM\OneToMany(targetEntity="Vocab", mappedBy="topic")
	 */
	private $vocabs;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="subjects")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 *
	 */
	private $user;

	public function __construct() {
		$this->vocabs = array();
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
	 * Set subject
	 *
	 */
	public function setSubject( Subject $subject ) {
		$this->subject = $subject;

		return $this;
	}

	public function getSubject() {
		return $this->subject;
	}

	/**
     * Set name
     *
     * @param string $name
     * @return Topic
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

	public function getVocabs() {
		return $this->vocabs;
	}

	public function setVocabs($vocabs) {
		// unset all associated topics!
		foreach ($this->vocabs as $vocab) {
			$vocab->setTopic(null);
		}

		$this->vocabs = $vocabs;
		foreach ($this->vocabs as $vocab){
			$vocab->setTopic($this);
		}
		return $this;
	}

	public function addVocabs(Vocab $vocab) {
		$this->vocabs[] = $vocab;
		$vocab->setTopic($this);
		return $this;
	}

	/**
	 * Get array containing associated vocab IDs
	 *
	 *@return array
	 *
	 * @VirtualProperty
	 * @SerializedName("vocabs")
	 */
	public function getVocabIDs() {
		$ids = array();
		foreach($this->getVocabs() as $vocab) {
			$ids[] = $vocab->getId();
		}
		return $ids;
	}

	public function getUser() {
		return $this->user;
	}

	public function setUser(User $user) {
		$this->user = $user;
		return $this;
	}

	public function getTemplateSubjectName() {
		return $this->templateSubjectName;
	}
	public function setTemplateSubjectName($name) {
		$this->templateSubjectName = $name;
		return $this;
	}
}
