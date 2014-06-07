<?php
namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

class VocabHandler {

	public function __construct(ObjectManager $om, $entityClass) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
	}

	public function get($id)
	{
		return $this->repository->find($id);
	}

	public function getAll($ids = array()) {
		if (empty($ids)) {
			$vocabs = $this->repository->findAll();
		} else {
			$vocabs = $this->repository->findById( $ids );
		}
		return $vocabs;
	}
}