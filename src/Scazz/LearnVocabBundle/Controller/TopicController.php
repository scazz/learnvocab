<?php

namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Scazz\LearnVocabBundle\Exception\InvalidFormException;


class TopicController extends FOSRestController {


	/**
	 * @param ParamFetcher $paramFetcher
	 * @return array
	 *
	 * @QueryParam(array=true, name="ids", requirements="\d+", description="List of ids")
	 */
	public function getTopicsAction(ParamFetcher $paramFetcher) {

		$ids = $paramFetcher->get('ids');

		$data = $this->container
			->get('learnvocab.topic.handler')
			->getAll($ids);

		return array( 'topics' => $data );
	}

	public function getTopicAction($id) {
		$view = View::create();

		$data = $this->getOr404($id);
		$view->setData( array('topic' => $data));
		return $view;
	}

	public function postTopicAction(Request $request) {
		try {
			$newTopic = $this
				->container
				->get('learnvocab.topic.handler')
				->post( $request );

		} catch(InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Topic:getTopic', array('id' => $newTopic->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
	}

	public function putTopicAction(Request $request, $id) {
		$topic = $this->getOr404($id);
		try {
			$this
				->container
				->get('learnvocab.topic.handler')
				->put( $request, $topic );
		} catch(InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Topic:getTopic', array('id' => $topic->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
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