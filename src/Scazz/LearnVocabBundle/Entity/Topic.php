<?php

namespace Scazz\LearnVocabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

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
     * @var boolean
     *
     * @ORM\Column(name="isTemplate", type="boolean")
	 * @Expose
     */
    private $isTemplate;

	/**
	 * @ORM\ManyToOne(targetEntity="Subject", inversedBy="topics")
	 * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
	 */
	private $subject;

	/**
	 * @ORM\OneToMany(targetEntity="Vocab", mappedBy="topic")
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

	/**
	 * Set subject
	 *
	 */
	public function setSubject( Subject $subject ) {
		$this->subject = $subject;

		return $this;
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

    /**
     * Set isTemplate
     *
     * @param boolean $isTemplate
     * @return Topic
     */
    public function setIsTemplate($isTemplate)
    {
        $this->isTemplate = $isTemplate;

        return $this;
    }

    /**
     * Get isTemplate
     *
     * @return boolean 
     */
    public function getIsTemplate()
    {
        return $this->isTemplate;
    }

	public function getVocabs() {
		return $this->vocabs;
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
}
