<?php

namespace Scazz\LearnVocabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class SiteController extends Controller {

	/**
	 * @Route("/about")
	 * @Template()
	 */
	public function indexAction() {

	}
	/**
	 * @Route("/")
	 * @Template()
	 */
	public function appAction() {

	}
}