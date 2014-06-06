<?php

namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicController extends FOSRestController {

	public function getTopicsAction() {
		$view = View::create();
		$data = $this
			->container
			->get('learnvocab.topic.handler')
			->getAll();
		$view->setData( array( 'topics' => $data ));
		return $view;
	}

	public function getTopicAction($id) {
		$view = View::create();

		$data = $this->getOr404($id);
		$view->setData( array('topic' => $data));
		return $view;
	}

	/**
	 * Fetch Topic or throw a 404 exception.
	 *
	 * @param mixed $id
	 *
	 * @return SubjectInterface
	 *
	 * @throws NotFoundHttpException
	 */
	protected function getOr404($id) {
		if (!($topic = $this->container->get('learnvocab.topic.handler')->get($id))) {
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
		}

		return $topic;
	}



}