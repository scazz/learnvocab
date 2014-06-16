<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Scazz\LearnVocabBundle\Form\SubjectTypeAPI;
use Scazz\LearnVocabBundle\Form\SubjectType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Scazz\LearnVocabBundle\Entity\Subject;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;
use Scazz\LearnVocabBundle\Entity\User;

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

	public function get($id, User $user)
	{
		$subject = $this->repository->find($id);
		if ( is_object($subject->getUser())
			&& is_object($user)
			&& $user->getId() != $subject->getUser()->getId()) {
				throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
		}
		return $subject;
	}

	public function getAll($ids = array(), User $user) {
		if (empty($ids)) {
			$subjects = $this->repository->findAllForUser($user);
		} else {
			$subjects = $this->repository->findForUserByIds($ids, $user);
		}
		return $subjects;
	}

	public function post($request, User $user) {
		$subject = new Subject();
		$params =  $request->request->all()['subject'];
		return $this->processForm($subject,$params,'POST', $user);
	}

	public function put(Subject $subject, $request, User $user) {
		$params =  $request->request->all()['subject'];
		return $this->processForm($subject, $params, 'POST', $user);
	}

	public function delete(Subject $subject) {
		/* need to remove all associated topics */
		foreach($subject->getTopics() as $topic) {
			$this->topicHandler->delete($topic);
		}
		$this->om->remove($subject);
		$this->om->flush();
	}

	private function processForm(Subject $subject, array $parameters, $method='PUT', User $user) {
		$form = $this->formFactory->create(new SubjectTypeAPI(), $subject, array('method'=>$method));

		$topicIds = array();
		foreach( $parameters['topics'] as $topic_id) {
			$topicIds[] = $topic_id;
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
			$subject->setUser($user);
			$this->om->persist($subject);
			$this->om->flush();
			return $subject;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}
}