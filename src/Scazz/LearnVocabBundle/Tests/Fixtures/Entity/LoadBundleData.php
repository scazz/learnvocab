<?php

namespace Scazz\LearnVocabBundle\Tests\Fixtures\Entity;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Scazz\LearnVocabBundle\Entity\Subject;

class LoadBundleData implements FixtureInterface {

    static public $subjects = array();

    public function load(ObjectManager $objectManager) {
		$subject = new Subject();
		$subject->setName("German");
		$subject->setIsTemplate(false);

		$objectManager->persist($subject);
		$objectManager->flush();
		self::$subjects[] = $subject;
    }

}