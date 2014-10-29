<?php

namespace PBlondeau\Bundle\ExhibitionBundle\Form\Type;

use PBlondeau\Bundle\ExhibitionBundle\Entity\Exhibition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExhibitionType extends AbstractType
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
            'data_class' => 'PBlondeau\Bundle\ExhibitionBundle\Entity\Exhibition',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_exhibitions';
    }

    /**
     * @param $options
     *
     * @return bool
     */
    private function isEditMode($options)
    {
        /** @var Exhibition $exhibition */
        $exhibition = $options['data'];

        if ($exhibition && !$exhibition->isNew()) {
            return true;
        }

        return false;
    }
}
