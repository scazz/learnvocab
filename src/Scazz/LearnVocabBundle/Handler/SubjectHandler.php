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
	private $topicHandler;

	public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, $topicHandler) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
		$this->topicHandler = $topicHandler;
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

	public function put(Subject $subject, $request) {
		return $this->processForm($subject, $request->request->all()['subject'], 'POST');
	}

	private function processForm(Subject $subject, array $parameters, $method='PUT') {
		$form = $this->formFactory->create(new SubjectTypeAPI(), $subject, array('method'=>$method));

		$topicIds = array();
		foreach( $parameters['topics'] as $topic_id) {
			$topicIds[] = $topic_id;
			echo "topic: $topic_id";
		}
		unset($parameters['topics']);

		$form->submit($parameters);

		//$subject->setTopics(array());
		foreach( $topicIds as $topic_id ) {
			$topic = $this->topicHandler->get($topic_id);
			if ($topic) {
				$subject->addTopics($topic);
			}
		}

		if ( $form->isValid()) {
			$this->om->persist($subject);
			$this->om->flush();
			return $subject;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}
}