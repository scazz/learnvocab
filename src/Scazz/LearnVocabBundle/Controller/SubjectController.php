<?php

namespace Scazz\LearnVocabBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Scazz\LearnVocabBundle\Entity\User;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;
use Scazz\LearnVocabBundle\Entity\Subject;


class SubjectController extends FOSRestController {

	private $user;

	private function setup() {
		$this->user = $this->get('security.context')->getToken()->getUser();
	}


	public function getSubjectAction( $id ) {
		$this->setup();
		$data = $this->getOr404($id);
		return array( 'subject' => $data );
	}

	/**
	 * @param ParamFetcher $paramFetcher
	 * @return array
	 *
	 * @QueryParam(array=true, name="ids", requirements="\d+", description="List of ids")
	 */
	public function getSubjectsAction(ParamFetcher $paramFetcher) {
		$this->setup();
		$ids = $paramFetcher->get('ids');

		$data = $this
			->container
			->get('learnvocab.subject.handler')
			->getAll( $ids, $this->user );
		return array( 'subjects' => $data );
	}

	public function postSubjectAction(Request $request) {
		$this->setup();
		try {
			$newSubject = $this->container->get('learnvocab.subject.handler')->post( $request, $this->user );
		} catch(InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Subject:getSubject', array('id' => $newSubject->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
	}

	public function putSubjectAction(Request $request, $id) {
		$this->setup();
		$subject = $this->getOr404($id);
		try {
			$newSubject = $this->container->get('learnvocab.subject.handler')->put( $subject, $request, $this->user );
		} catch(InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Subject:getSubject', array('id' => $newSubject->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
	}

	public function deleteSubjectAction($id) {
		$this->setup();
		$subject = $this->getOr404($id);
		$this->container->get('learnvocab.subject.handler')->delete( $subject );
		return new View(null, 204);
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
		if (!($subject = $this->container->get('learnvocab.subject.handler')->get($id, $this->user))) {
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
		}

		return $subject;
	}
}