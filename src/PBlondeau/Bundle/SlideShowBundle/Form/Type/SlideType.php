<?php

namespace PBlondeau\Bundle\SlideShowBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlideType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path')
            ->add('position')
            ->add('creation')
            ->add('status')
            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PBlondeau\Bundle\SlideShowBundle\Entity\Slide'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_slideshowbundle_slide';
    }
}
