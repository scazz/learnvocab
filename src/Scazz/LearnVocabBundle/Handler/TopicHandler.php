<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Scazz\LearnVocabBundle\Entity\Topic;
use Scazz\LearnVocabBundle\Form\TopicTypeAPI;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;
use Scazz\LearnVocabBundle\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;


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

	public function get($id, User $user=null)
	{
		$topic = $this->repository->find($id);

		if (is_object($topic->getUser()) &&
			is_object($user) &&
			$topic->getUser()->getId() != $user->getId()) {
			throw new AccessDeniedException("You do not have permission to view this resource");
		}
		return $topic;
	}

	public function getAll(User $user, $ids=array()) {
		if (empty($ids)) {
			$topics = $this->repository->findAllByUser($user);
		} else {
			$topics = $this->repository->findAllByUserAndIds( $user, $ids );
		}
		return $topics;
	}

	public function post($request, User $user) {
		$topic = new Topic();
		$params =  $request->request->all()['topic'];
		return $this->processForm($topic, $params, 'POST', $user);
	}

	public function put($request, Topic $topic, User $user) {
		$params = $request->request->all()['topic'];
		return $this->processForm($topic, $params, 'POST', $user);
	}

	public function delete(Topic $topic) {

		/* need to delete all associated vocabs */
		foreach($topic->getVocabs() as $vocab) {
			$this->vocabHandler->delete($vocab);
		}

		$this->om->remove($topic);
		$this->om->flush();
	}

	private function processForm(Topic $topic, array $parameters, $method='PUT', User $user) {
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
			$topic->setUser($user);
			$this->om->persist($topic);
			$this->om->flush();
			return $topic;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}
}
