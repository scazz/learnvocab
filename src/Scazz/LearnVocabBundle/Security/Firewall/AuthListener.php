<?php

namespace Scazz\LearnVocabBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

use Scazz\LearnVocabBundle\Security\Authentication\Token\AuthToken;


class AuthListener implements ListenerInterface
{
	protected $securityContext;
	protected $authenticationManager;

	public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
	{
	  $this->securityContext = $securityContext;
	  $this->authenticationManager = $authenticationManager;
	}

	public function handle(GetResponseEvent $event)
	{
		$request = $event->getRequest();

		if ( !$request->headers->has('token'))  {
			$event->setResponse( $this->failResponse() );
			return;
		}
		if ( !$request->headers->has('username'))  {
			$event->setResponse( $this->failResponse() );
			return;
		}
		$token = new AuthToken();
		$token->setUser( $request->headers->get('username') );
		$token->digest = $request->headers->get('token');

		try {
			$authToken = $this->authenticationManager->authenticate($token);
			$this->securityContext->setToken($authToken);
			return;
		} catch (AuthenticationException $failed) {
			$event->setResponse( $this->failResponse() );
			return;
		}
	}

	private function failResponse() {
		$response = new Response();
		$response->setStatusCode(Response::HTTP_FORBIDDEN);
		return $response;
	}
}