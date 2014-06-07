<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Scazz\LearnVocabBundle\Form\SubjectTypeAPI;
use Scazz\LearnVocabBundle\Form\SubjectType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Scazz\LearnVocabBundle\Entity\Subject;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;

class SubjectHandler {

	private $formFactory;

	public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
	}

	public function get($id)
	{
		return $this->repository->find($id);
	}

	public function getAll($ids = array()) {
		if (empty($ids)) {
			$subjects = $this->repository->findAll();
		} else {
			$subjects = $this->repository->findById($ids);
		}
		return $subjects;
	}

	public function post($request) {
		$subject = new Subject();
		return $this->processForm($subject, $request->request->all()['subject'], 'POST');
	}

	private function processForm(Subject $subject, array $parameters, $method='PUT') {
		$form = $this->formFactory->create(new SubjectTypeAPI(), $subject, array('method'=>$method));

		$form->submit($parameters);

		if ( $form->isValid()) {
			$this->om->persist($subject);
			$this->om->flush();
			return $subject;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}
}