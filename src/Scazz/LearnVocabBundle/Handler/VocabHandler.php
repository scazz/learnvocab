<?php
namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Scazz\LearnVocabBundle\Form\VocabTypeAPI;
use Scazz\LearnVocabBundle\Entity\Vocab;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;
use Scazz\LearnVocabBundle\Entity\User;


class VocabHandler {

	private $formFactory;

	public function __construct(ObjectManager $om, $entityClass, $formFactory) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
	}

	public function get($id, User $user=null)
	{
		$vocab = $this->repository->find($id);

		if (is_object($vocab->getUser()) &&
			is_object($user) &&
			$vocab->getUser()->getId() != $user->getId()) {
			throw new AccessDeniedException("You do not have permission to view this resource");
		}
		return $vocab;
	}

	public function getAll($ids = array(), User $user) {
		if (empty($ids)) {
			$vocabs = $this->repository->findAllByUser($user);
		} else {
			$vocabs = $this->repository->findAllByUserAndIds( $user, $ids );
		}
		return $vocabs;
	}

	public function post(Request $request, User $user) {
		$vocab = new Vocab();
		$params = $request->request->all()['vocab'];
		return $this->processForm($vocab, $params, 'POST', $user);
	}

	public function put(Request $request, Vocab $vocab, User $user) {
		$params = $request->request->all()['vocab'];
		return $this->processForm($vocab, $params, 'PUT', $user);
	}

	public function delete(Vocab $vocab) {
		$this->om->remove($vocab);
		$this->om->flush();
	}

	private function processForm(Vocab $vocab, array $parameters, $method='PUT', User $user) {
		/* Set default options */
		if (! array_key_exists('timesCorrectlyAnswered', $parameters)) {
			$parameters['timesCorrectlyAnswered'] = 0;
		}

		$form = $this->formFactory->create(new VocabTypeAPI(), $vocab, array('method'=>$method));
		$form->submit($parameters);

		if ( $form->isValid()) {
			$vocab->setUser($user);
			$this->om->persist($vocab);
			$this->om->flush();
			return $vocab;
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}


}