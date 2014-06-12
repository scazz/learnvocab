<?php

namespace Scazz\LearnVocabBundle\Controller;

use Scazz\LearnVocabBundle\Entity\SessionKey;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class SecurityController extends Controller {

	/**
	 * @Route("/api/token")
	 * @param Request $request
	 * @return Response
	 */
	public function tokenAction(Request $request) {
		$userRepo = $this->getDoctrine()->getRepository('ScazzLearnVocabBundle:User');
		$sha512Encoder = new MessageDigestPasswordEncoder('sha512', true, 5000);

		$username = $request->get('username');
		$password = $request->get('password');

		if (null === ($user = $userRepo->findOneByUsername($username))) {
			return $this->fail();
		};
		if (! $sha512Encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
			return $this->fail();
		}

		$key = $this->container->get('learnvocab.sessionkey.handler')->generateKey($user);

		$data = array("success" => true, "token" => $key->getKey());
		return new Response( json_encode($data) );
	}

	/**
	 * @Route("/api/deletetoken")
	 * @param Request $request
	 * @return Response
	 */
	public function deleteTokenAction(Request $request) {
		if ( !$request->headers->has('token'))  {
			echo "no token - shouldn't ever be able to reach here... ";
			return;
		}
		$this->container->get('learnvocab.sessionkey.handler')->deleteKey ( $request->headers->get('token') );
		$response = new Response();
		$response->setStatusCode(204);
		return $response;

	}



	private function fail() {
		$data = array("success" => false, "message" => "Incorrect username or password");
		return new Response( json_encode($data));
	}
}