<?php

namespace Scazz\LearnVocabBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VocabType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('native')
            ->add('translated')
            ->add('isLearnt')
            ->add('timesCorrectlyAnswered')
            ->add('topic')
        ;
    }

    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Scazz\LearnVocabBundle\Entity\Vocab'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'scazz_learnvocabbundle_vocab';
    }
}
