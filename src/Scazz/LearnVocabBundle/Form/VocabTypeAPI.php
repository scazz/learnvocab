<?php

namespace Scazz\LearnVocabBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VocabTypeAPI extends VocabType {

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults( array(
				'csrf_protection' => false,
				'data_class' => 'Scazz\LearnVocabBundle\Entity\Vocab',
				'timesCorrectlyAnswered' => 0
			));
	}
}

