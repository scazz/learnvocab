<?php

namespace Scazz\LearnVocabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vocab
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Scazz\LearnVocabBundle\Entity\VocabRepository")
 * @ExclusionPolicy("all")
 */
class Vocab
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
	 * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="native", type="string", length=255)
	 * @Expose
	 * @Assert\NotBlank()
     */
    private $native;

    /**
     * @var string
     *
     * @ORM\Column(name="translated", type="string", length=255)
	 * @Expose
	 * @Assert\NotBlank()
     */
    private $translated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isLearnt", type="boolean")
	 * @SerializedName("isLearnt")
	 * @Expose
     */
    private $isLearnt;

    /**
     * @var integer
     *
     * @ORM\Column(name="timesCorrectlyAnswered", type="integer")
	 * @Assert\NotBlank()
	 * @SerializedName("timesCorrectlyAnswered")
	 * @Expose
     */
    private $timesCorrectlyAnswered;

	/**
	 * @ORM\ManyToOne(targetEntity="Topic", inversedBy="vocabs")
	 * @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
	 */
	private $topic;

	public function __construct() {
		$this->setIsLearnt( false );
		$this->setTimesCorrectlyAnswered(0);
	}

	/**
	 * Set Topic
	 */
	public function setTopic(Topic $topic) {
		$this->topic = $topic;
		return $this;
	}

	/**
	 * Get Topic
	 */
	public function getTopic() {
		return $this->topic;
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
     * Set native
     *
     * @param string $native
     * @return Vocab
     */
    public function setNative($native)
    {
        $this->native = $native;

        return $this;
    }

    /**
     * Get native
     *
     * @return string 
     */
    public function getNative()
    {
        return $this->native;
    }

    /**
     * Set translated
     *
     * @param string $translated
     * @return Vocab
     */
    public function setTranslated($translated)
    {
        $this->translated = $translated;

        return $this;
    }

    /**
     * Get translated
     *
     * @return string 
     */
    public function getTranslated()
    {
        return $this->translated;
    }

    /**
     * Set isLearnt
     *
     * @param boolean $isLearnt
     * @return Vocab
     */
    public function setIsLearnt($isLearnt)
    {
        $this->isLearnt = $isLearnt;

        return $this;
    }

    /**
     * Get isLearnt
     *
     * @return boolean 
     */
    public function getIsLearnt()
    {
        return $this->isLearnt;
    }

    /**
     * Set timesCorrectlyAnswered
     *
     * @param integer $timesCorrectlyAnswered
     * @return Vocab
     */
    public function setTimesCorrectlyAnswered($timesCorrectlyAnswered)
    {
        $this->timesCorrectlyAnswered = $timesCorrectlyAnswered;

        return $this;
    }

    /**
     * Get timesCorrectlyAnswered
     *
     * @return integer 
     */
    public function getTimesCorrectlyAnswered()
    {
        return $this->timesCorrectlyAnswered;
    }
}
