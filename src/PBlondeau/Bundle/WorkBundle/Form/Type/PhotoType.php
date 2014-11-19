<?php

namespace PBlondeau\Bundle\WorkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PBlondeau\Bundle\CommonBundle\Form\Type\FilesType;

class PhotoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('files', new FilesType(), array(
            "label"    => "Photos",
            "required" => true,
            "mapped"   => false,
            "attr"     => array(
                "accept"   => "image/*",
                "multiple" => "multiple",
            )));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_work_photo';
    }
} 