<?php


namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VocabController extends FOSRestController {

	public function getVocabsAction() {
		$data = $this->container->get('learnvocab.vocab.handler')->getAll();
		return array('vocabs' => $data);
	}

	public function getVocabAction($id) {
		$data = $this->getOr404($id);
		return array('vocab'=>$data);
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