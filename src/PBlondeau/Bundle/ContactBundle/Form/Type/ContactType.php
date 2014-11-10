<?php

namespace PBlondeau\Bundle\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'sender', 'email', array(
                    'label' => 'form.field.sender'
                )
            )
            ->add('subject', 'text')
            ->add(
                'content', 'textarea', array(
                    'attr' => array(
                        'class' => 'form-control',
                        'rows'  => '15'
                    )
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection'    => true,
                'translation_domain' => 'contact'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_contact';
    }
} 