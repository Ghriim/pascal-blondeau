<?php

namespace PBlondeau\Bundle\WorkBundle\Form\Type;

use PBlondeau\Bundle\WorkBundle\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbumType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
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
            'data_class' => 'PBlondeau\Bundle\WorkBundle\Entity\Album',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_work_album';
    }

    /**
     * @param $options
     *
     * @return bool
     */
    private function isEditMode($options)
    {
        /** @var Album $album */
        $album = $options['data'];

        if ($album && !$album->isNew()) {
            return true;
        }

        return false;
    }
}
