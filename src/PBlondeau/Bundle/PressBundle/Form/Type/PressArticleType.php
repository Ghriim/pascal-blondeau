<?php

namespace PBlondeau\Bundle\PressBundle\Form\Type;

use PBlondeau\Bundle\PressBundle\Entity\PressArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PressArticleType extends AbstractType
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
            'data_class' => 'PBlondeau\Bundle\PressBundle\Entity\PressArticle',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_press_articles';
    }

    /**
     * @param $options
     *
     * @return bool
     */
    private function isEditMode($options)
    {
        /** @var PressArticle $pressArticle */
        $pressArticle = $options['data'];

        if ($pressArticle && !$pressArticle->isNew()) {
            return true;
        }

        return false;
    }
}
