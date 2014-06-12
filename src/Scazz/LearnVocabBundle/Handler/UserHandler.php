<?php

namespace Scazz\LearnVocabBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;
use Scazz\LearnVocabBundle\Form\UserTypeAPI;

class UserHandler {

	private $formFactory;

	/** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
	private $userManager;

	public function __construct(ObjectManager $om, $entityClass, $formFactory, $userManager, $dispatcher) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->formFactory = $formFactory;
		$this->userManager = $userManager;
		$this->dispatcher = $dispatcher;
		$this->repository = $this->om->getRepository($this->entityClass);
	}

	public function post(Request $request) {
		$user = $this->userManager->createUser();
		$user->setEnabled(true);
		$event = new GetResponseUserEvent($user, $request);
		$this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		$form = $this->formFactory->create(new UserTypeAPI(), $user, array('method'=>'POST'));
		$form->submit( $request->request->all()) ;

		if ( $this->repository->findByUsername( $user->getUsername() ) ) {
			throw new DuplicateKeyException( sprintf("Username: \"%s\" has been taken", $user->getUsername()));
		}
		if ( $this->repository->findByEmail( $user->getEmail() )) {
			throw new DuplicateKeyException( sprintf("Email address: \"%s\" has already been used to register an account", $user->getEmail()));
		}

		if ( $form->isValid()) {
			$event = new FormEvent($form, $request);
			$this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
			$this->userManager->updateUser($user);
		} else {
			throw new InvalidFormException('Invalid submitted data', $form);
		}
		return $user;
	}

}