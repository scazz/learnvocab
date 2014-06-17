<?php

namespace Scazz\LearnVocabBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Scazz\LearnVocabBundle\Entity\User;
use Scazz\LearnVocabBundle\Exception\InvalidFormException;
use Scazz\LearnVocabBundle\Entity\Subject;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("SubjectTemplate")
 */
class SubjectTemplateController extends FOSRestController {

	public function getCloneAction($id) {
		$user = $this->get('security.context')->getToken()->getUser();

		$template = $this->getOr404($id);
		$this->container->get('learnvocab.subject_template.handler')->cloneTemplate( $template, $user );
	}

	public function cgetAction() {
		$user = $this->get('security.context')->getToken()->getUser();
		$repository = $this->getDoctrine()->getManager()->getRepository('ScazzLearnVocabBundle:SubjectTemplate');
		$data = $repository->findAllUnusedTemplatesForUser($user);
		return array('subjecttemplates' => $data);
	}

	protected function getOr404($id) {
		if (!($template = $this->container->get('learnvocab.subject_template.handler')->get($id))) {
			echo "not found!";
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
		}
		return $template;
	}


}