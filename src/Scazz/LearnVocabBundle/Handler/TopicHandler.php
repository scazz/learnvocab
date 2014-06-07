<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Scazz\LearnVocabBundle\Entity\Topic;
use Scazz\LearnVocabBundle\Form\TopicTypeAPI;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;

use Symfony\Component\Form\FormFactoryInterface;



class TopicHandler {
	private $formFactory;
	private $vocabHandler;

	public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, $vocabHandler) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
		$this->vocabHandler = $vocabHandler;
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

	public function put($request, Topic $topic) {
		return $this->processForm($topic, $request->request->all()['topic'], 'POST');
	}

	public function delete(Topic $topic) {

		/* need to delete all associated vocabs */
		foreach($topic->getVocabs() as $vocab) {
			$this->vocabHandler->delete($vocab);
		}

		$this->om->remove($topic);
		$this->om->flush();
	}

	private function processForm(Topic $topic, array $parameters, $method='PUT') {
		$form = $this->formFactory->create(new TopicTypeAPI(), $topic, array('method'=>$method));

		$vocabIds = array();
		if ( array_key_exists('vocabs', $parameters)) {
			foreach( $parameters['vocabs'] as $vocab_id) {
				$vocabIds[] = $vocab_id;
			}
			unset($parameters['vocabs']);
		}

		$form->submit($parameters);

		foreach( $vocabIds as $vocabId ) {
			$vocab = $this->vocabHandler->get($vocabId);
			if ($vocab) {
				$topic->addVocabs($vocab);
			}
		}

		if ( $form->isValid()) {
			$this->om->persist($topic);
			$this->om->flush();
			return $topic;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}
}
