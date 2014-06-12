<?php

namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;


class UserController extends FOSRestController {

	public function postUserAction(Request $request) {
		try {
			$user = $this->container->get('learnvocab.user.handler')->post( $request );
		} catch(InvalidFormException $exception) {
			return $exception->getForm();
		} catch (DuplicateKeyException $exception) {
			$data = array('success'=>false, 'message'=> $exception->getMessage());
			$response = new Response( json_encode($data) );
			$response->setStatusCode(400);
			return $response;
		}

		$key = $this->container->get('learnvocab.sessionkey.handler')->generateKey($user);

		$data = array('success'=>true, 'token'=>$key->getKey());
		$response = new Response( json_encode($data));
		$response->setStatusCode("201");
		return $response;
	}
}