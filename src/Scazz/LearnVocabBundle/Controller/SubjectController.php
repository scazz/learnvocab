<?php

namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


use Scazz\LearnVocabBundle\Entity\Subject;

class SubjectController extends FOSRestController {


	public function getSubjectAction( $id ) {
		$view = View::create();
		$data = $this->getOr404($id);
		$view->setData( array( 'subject' => $data ));
		return $view;
	}

	public function getSubjectsAction() {
		$view = View::create();
		$data = $this
			->container
			->get('learnvocab.subject.handler')
			->getAll();
		$view->setData( array( 'subjects' => $data ));
		return $view;
	}

	/**
	 * Fetch Subject or throw a 404 exception.
	 *
	 * @param mixed $id
	 *
	 * @return SubjectInterface
	 *
	 * @throws NotFoundHttpException
	 */
	protected function getOr404($id)
	{
		if (!($subject = $this->container->get('learnvocab.subject.handler')->get($id))) {
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
		}

		return $subject;
	}
}