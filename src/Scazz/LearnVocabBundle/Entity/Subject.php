<?php

namespace Scazz\LearnVocabBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;



/**
 * Subject
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Scazz\LearnVocabBundle\Entity\SubjectRepository")
 * @ExclusionPolicy("all")
 */
class Subject
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
	 *
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
	 * @ORM\OneToMany(targetEntity="Topic", mappedBy="subject")
	 */
	 private $topics;


	public function __construct() {
		$this->topics = new ArrayCollection();
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
	 * Get topics
	 */
	public function getTopics() {
		return $this->topics;
	}

	/**
	 * Get array of the IDs of each topic
	 * @return array
	 *
	 * @VirtualProperty
	 * @SerializedName("topics")
	 *
	 */
	public function getTopicIds() {
		$ids = array();
		$topics = $this->getTopics();
		foreach ($topics as $topic) {
			$ids[] = $topic->getId();
		}
		return $ids;
	}

	/**
     * Set name
     *
     * @param string $name
     * @return Subject
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
     * @return Subject
     */
    public function setIsTemplate($isTemplate)
    {
        $this->isTemplate = $isTemplate;

        return $this;
    }

    /**
     * Get isTemplate
     *
     * @return \boolean" 
     */
    public function getIsTemplate()
    {
        return $this->isTemplate;
    }


}
