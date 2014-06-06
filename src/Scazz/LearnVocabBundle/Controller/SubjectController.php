<?php

namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


use Scazz\LearnVocabBundle\Entity\Subject;

class SubjectController extends FOSRestController {


	public function getSubjectAction( $id ) {
		$data = $this->getOr404($id);
		return array( 'subject' => $data );
	}

	public function getSubjectsAction() {
		$data = $this
			->container
			->get('learnvocab.subject.handler')
			->getAll();
		return array( 'subjects' => $data );
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