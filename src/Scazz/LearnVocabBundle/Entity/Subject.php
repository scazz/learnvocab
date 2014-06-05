<?php

namespace Scazz\LearnVocabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Scazz\LearnVocabBundle\Entity\SubjectRepository")
 */
class Subject
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var boolean"
     *
     * @ORM\Column(name="isTemplate", type="boolean"")
     */
    private $isTemplate;



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
     * Set "title
     *
     * @param string $title
     * @return Subject
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get "title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * Set isTemplate
     *
     * @param \boolean $isTemplate
     * @return Subject
     */
    public function setIsTemplate(\boolean $isTemplate)
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
