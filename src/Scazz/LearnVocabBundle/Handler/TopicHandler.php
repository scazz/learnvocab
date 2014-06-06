<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

class TopicHandler {

	public function __construct(ObjectManager $om, $entityClass) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
	}

	public function get($id)
	{
		return $this->repository->find($id);
	}

	public function getAll() {
		return $this->repository->findAll();
	}
}
