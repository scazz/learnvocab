<?php

namespace Scazz\LearnVocabBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubjectTypeAPI extends SubjectType {

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults( array(
				'csrf_protection' => false,
				'data_class' => 'Scazz\LearnVocabBundle\Entity\Subject'
			));
	}
}