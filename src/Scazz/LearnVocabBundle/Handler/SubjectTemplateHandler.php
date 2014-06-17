<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Scazz\LearnVocabBundle\Entity\SubjectTemplate;
use Scazz\LearnVocabBundle\Entity\Subject;
use Scazz\LearnVocabBundle\Entity\User;

class SubjectTemplateHandler {

	public function __construct(ObjectManager $om, $entityClass) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
	}

	public function get($id) {
		return $this->repository->find($id);
	}

	public function cloneTemplate(SubjectTemplate $template, User $user) {
		$subject = new Subject();
		$subject->setName( $template->getName() );
		$subject->setUser( $user );
		$this->om->persist($subject);
		$this->om->flush();
		return $subject;
	}
}