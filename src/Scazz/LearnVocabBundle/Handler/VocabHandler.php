<?php
namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Scazz\LearnVocabBundle\Form\VocabTypeAPI;
use Scazz\LearnVocabBundle\Entity\Vocab;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;


class VocabHandler {

	private $formFactory;

	public function __construct(ObjectManager $om, $entityClass, $formFactory) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
	}

	public function get($id)
	{
		return $this->repository->find($id);
	}

	public function getAll($ids = array()) {
		if (empty($ids)) {
			$vocabs = $this->repository->findAll();
		} else {
			$vocabs = $this->repository->findById( $ids );
		}
		return $vocabs;
	}

	public function post(Request $request) {
		$vocab = new Vocab();
		return $this->processForm($vocab, $request->request->all()['vocab'], 'POST');
	}

	public function put(Request $request, Vocab $vocab) {
		return $this->processForm($vocab, $request->request->all()['vocab'], 'PUT');
	}

	public function delete(Vocab $vocab) {
		$this->om->remove($vocab);
		$this->om->flush();
	}

	private function processForm(Vocab $vocab, array $parameters, $method='PUT') {
		/* Set default options */
		if (! array_key_exists('timesCorrectlyAnswered', $parameters)) {
			$parameters['timesCorrectlyAnswered'] = 0;
		}

		$form = $this->formFactory->create(new VocabTypeAPI(), $vocab, array('method'=>$method));
		$form->submit($parameters);

		if ( $form->isValid()) {
			$this->om->persist($vocab);
			$this->om->flush();
			return $vocab;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}


}