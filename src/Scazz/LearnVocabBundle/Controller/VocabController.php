<?php


namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;


class VocabController extends FOSRestController {

	/**
	 * @param ParamFetcher $paramFetcher
	 * @return array
	 * @QueryParam(array=true, name="ids", requirements="\d+", description="List of ids")
	 */
	public function getVocabsAction(ParamFetcher $paramFetcher) {
		$ids = $paramFetcher->get('ids');
		$data = $this->container->get('learnvocab.vocab.handler')->getAll($ids);
		return array('vocabs' => $data);
	}

	public function getVocabAction($id) {
		$data = $this->getOr404($id);
		return array('vocab'=>$data);
	}

	public function postVocabAction(Request $request) {
		try {
			$newVocab = $this->container->get('learnvocab.vocab.handler')->post($request);
		} catch (InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Vocab:getVocab', array('id' => $newVocab->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
	}

	public function putVocabAction(Request $request, $id) {
		$vocab = $this->getOr404($id);
		try {
			$this->container->get('learnvocab.vocab.handler')->put($request, $vocab);
		}catch (InvalidFormException $exception) {
			return $exception->getForm();
		}
		$response = $this->forward('ScazzLearnVocabBundle:Vocab:getVocab', array('id' => $vocab->getId()), array('_format'=>'json' ));
		$response->setStatusCode("201");
		return $response;
	}

	/**
	 * Fetch Vocab or throw a 404 exception.
	 *
	 * @param mixed $id
	 *
	 * @return SubjectInterface
	 *
	 * @throws NotFoundHttpException
	 */
	private function getOr404($id) {
		if (! ($vocab =$this->container->get('learnvocab.vocab.handler')->get($id))) {
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
		}
		return $vocab;
	}


}