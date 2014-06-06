<?php

namespace Scazz\LearnVocabBundle\Tests\Fixtures\Entity;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Scazz\LearnVocabBundle\Entity\Subject;
use Scazz\LearnVocabBundle\Entity\Topic;

class LoadBundleData implements FixtureInterface {

	private $om;

    static public $subjects = array();
    static public $topics = array();

    public function load(ObjectManager $objectManager) {
		$this->om = $objectManager;

		$this->loadSubjects();
		//$this->loadTopics();
    }

	private function loadSubjects() {
		$subject = new Subject();
		$subject->setName("German");
		$subject->setIsTemplate(false);

		$topic = new Topic();
		$topic->setName("Animals");
		$topic->setIsTemplate(false);
		$topic->setSubject( $subject );

		$this->om->persist($subject);
		$this->om->persist($topic);
		$this->om->flush();
		self::$subjects[] = $subject;
		self::$topics[] = $topic;
	}

	private function loadTopics() {
		$subject = self::$subjects[0];

		$topic = new Topic();
		$topic->setName("Animals");
		$topic->setIsTemplate(false);
		$topic->setSubject( $subject );
		//$this->om->persist($subject);

		$this->om->persist($topic);
		$this->om->flush();
		self::$topics[] = $topic;
	}

}