<?php

namespace Scazz\LearnVocabBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Scazz\LearnVocabBundle\Entity\Topic;

class SubjectType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
			->add('topics')
        	;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Scazz\LearnVocabBundle\Entity\Subject'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'scazz_learnvocabbundle_subject';
    }
}
