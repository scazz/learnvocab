<?php

namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;

use Scazz\LearnVocabBundle\Entity\User;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;


class TopicController extends FOSRestController {
	private $user;

	private function setup() {
		$this->user = $this->get('security.context')->getToken()->getUser();
	}

	/**
	 * @param ParamFetcher $paramFetcher
	 * @return array
	 *
	 * @QueryParam(array=true, name="ids", requirements="\d+", description="List of ids")
	 */
	public function getTopicsAction(ParamFetcher $paramFetcher) {
		$this->setup();
		$ids = $paramFetcher->get('ids');

		$data = $this->container
			->get('learnvocab.topic.handler')
			->getAll($this->user,$ids);

		return array( 'topics' => $data );
	}

	public function getTopicAction($id) {
		$this->setup();
		$view = View::create();

		$data = $this->getOr404($id);
		$view->setData( array('topic' => $data));
		return $view;
	}

	public function postTopicAction(Request $request) {
		$this->setup();
		try {
			$newTopic = $this
				->container
				->get('learnvocab.topic.handler')
				->post( $request, $this->user );

		} catch(InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Topic:getTopic', array('id' => $newTopic->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
	}

	public function putTopicAction(Request $request, $id) {
		$this->setup();
		$topic = $this->getOr404($id);
		try {
			$this
				->container
				->get('learnvocab.topic.handler')
				->put( $request, $topic, $this->user );
		} catch(InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Topic:getTopic', array('id' => $topic->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
	}

	public function deleteTopicAction($id) {
		$this->setup();
		$topic = $this->getOr404($id);
		$this->container->get('learnvocab.topic.handler')->delete($topic);
		return new View(null, 204);
	}

	/**
	 * @Get("/topictemplates")
	 * @QueryParam(name="subjectName", description="Subject name")
	 */
	public function getTopicTemplatesAction(ParamFetcher $paramFetcher) {
		$name = $paramFetcher->get("subjectName");
		$data = $this->container->get('learnvocab.topic.handler')->findTopicsForTemplate($name);
		return array('topictemplates'=>$data);
	}

	/**
	 *
	 * @QueryParam(name="subject_id", description="Subject ID to clone topic into")
	 */
	public function getTopicCloneAction($id, ParamFetcher $paramFetcher) {
		$this->setup();
		$topic = $this->getOr404($id);

		if ( is_object($topic->getSubject())) {
			$targetSubject = $topic->getSubject();
		} else {
			$targetSubject = $this->container->get('learnvocab.subject.handler')->get( $paramFetcher->get('subject_id'), $this->user );
		}
		$topic = $this->container->get('learnvocab.topic.handler')->cloneTopic($topic, $this->user, $targetSubject);
		return array('topic'=>$topic);
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
		if (!($topic = $this->container->get('learnvocab.topic.handler')->get($id, $this->user))) {
			echo "not found!";
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
		}

		return $topic;
	}

}