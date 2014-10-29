<?php

namespace PBlondeau\Bundle\SlideShowBundle\Form\Type;

use PBlondeau\Bundle\SlideShowBundle\Entity\Slide;
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
            ->add('file', 'file', array(
                'required' => !$this->isEditMode($options)
            ))
            ->add('status', 'pblondeau_status')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PBlondeau\Bundle\SlideShowBundle\Entity\Slide',
            'csrf_protection' => false,
            'slide' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_slideshowbundle_slide';
    }

    /**
     * @param $options
     *
     * @return bool
     */
    private function isEditMode($options)
    {
        /** @var Slide $slide */
        $slide = $options['data'];

        if ($slide && !$slide->isNew()) {
            return true;
        }

        return false;
    }
}
