<?php

namespace Scazz\LearnVocabBundle\Tests\Fixtures\Entity;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Scazz\LearnVocabBundle\Entity\Subject;
use Scazz\LearnVocabBundle\Entity\Topic;
use Scazz\LearnVocabBundle\Entity\Vocab;

class LoadBundleData implements FixtureInterface {

	private $om;

    static public $subjects = array();
    static public $topics = array();
    static public $vocabs = array();

    public function load(ObjectManager $objectManager) {
		$this->om = $objectManager;

		$subject = new Subject();
		$subject->setName("German");
		$subject->setIsTemplate(false);

		$topic = new Topic();
		$topic->setName("Animals");
		$topic->setIsTemplate(false);
		$topic->setSubject( $subject );

		$vocab = new Vocab();
		$vocab->setNative("cat");
		$vocab->setTranslated("Katz");
		$vocab->setTopic( $topic );

		$this->om->persist($subject);
		$this->om->persist($topic);
		$this->om->persist($vocab);
		$this->om->flush();
		self::$subjects[] = $subject;
		self::$topics[] = $topic;
		self::$vocabs[] = $vocab;
    }
}