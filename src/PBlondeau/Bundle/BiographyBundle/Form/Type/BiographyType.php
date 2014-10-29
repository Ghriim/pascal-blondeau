<?php

namespace PBlondeau\Bundle\BiographyBundle\Form\Type;

use PBlondeau\Bundle\BiographyBundle\Entity\Biography;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BiographyType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'genemu_tinymce', array(
                'required' => true,
                'configs' => array (
                    'width'   => 'auto',
                    'height'  => 400,
                    'theme'   => 'modern',
                    'theme_advanced_toolbar_location' => 'top',
                    'theme_advanced_toolbar_align' => 'left',
                    'theme_advanced_statusbar_location' => 'bottom',
                    'theme_advanced_resizing' => false,
                    'theme_advanced_resizing_use_cookie' => false,
                    'skin_variant' => 'silver',
                    'plugins' => array(
                        "advlist autolink lists link image charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ),
                    'toolbar' => "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                )
            ))
            ->add('file', 'file', array(
                'required' => !$this->isEditMode($options)
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PBlondeau\Bundle\BiographyBundle\Entity\Biography',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_biography';
    }

    /**
     * @param $options
     *
     * @return bool
     */
    private function isEditMode($options)
    {
        /** @var Biography $biography */
        $biography = $options['data'];

        if ($biography && !$biography->isNew()) {
            return true;
        }

        return false;
    }
}