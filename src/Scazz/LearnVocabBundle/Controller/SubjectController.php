<?php

namespace Scazz\LearnVocabBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Scazz\LearnVocabBundle\Entity\Subject;

class SubjectController extends FOSRestController {


	public function getSubjectAction( $id ) {
		return $this->getDoctrine()->getRepository('ScazzLearnVocabBundle:Subject')->find($id);
	}

}