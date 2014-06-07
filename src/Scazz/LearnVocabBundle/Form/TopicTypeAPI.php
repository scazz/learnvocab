<?php

namespace Scazz\LearnVocabBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TopicTypeAPI extends TopicType {

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults( array(
				'csrf_protection' => false,
				'data_class' => 'Scazz\LearnVocabBundle\Entity\Topic'
			));
	}
}