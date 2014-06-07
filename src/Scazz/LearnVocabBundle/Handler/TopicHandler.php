<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Scazz\LearnVocabBundle\Entity\Topic;
use Scazz\LearnVocabBundle\Form\TopicTypeAPI;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;

use Symfony\Component\Form\FormFactoryInterface;



class TopicHandler {
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

	public function getAll($ids=array()) {
		if (empty($ids)) {
			$topics = $this->repository->findAll();
		} else {
			$topics = $this->repository->findById( $ids );
		}
		return $topics;
	}

	public function post($request) {
		$topic = new Topic();
		return $this->processForm($topic, $request->request->all()['topic'], 'POST');
	}

	private function processForm(Topic $topic, array $parameters, $method='PUT') {
		$form = $this->formFactory->create(new TopicTypeAPI(), $topic, array('method'=>$method));

		$form->submit($parameters);

		if ( $form->isValid()) {
			$this->om->persist($topic);
			$this->om->flush();
			return $topic;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}
}
