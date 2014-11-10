<?php

namespace PBlondeau\Bundle\NewsBundle\Form\Type;

use PBlondeau\Bundle\NewsBundle\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add(
                'content', 'textarea', array(
                    'attr' => array(
                        'class' => 'textarea-large'
                    )
                )
            )
            ->add(
                'file', 'file', array(
                    'required' => !$this->isEditMode($options)
                )
            )
            ->add('status', 'pblondeau_status')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => 'PBlondeau\Bundle\NewsBundle\Entity\News',
                'csrf_protection' => false,
                'news' => null
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pblondeau_bundle_news';
    }

    /**
     * @param $options
     *
     * @return bool
     */
    private function isEditMode($options)
    {
        /** @var News $news */
        $news = $options['data'];

        if ($news && !$news->isNew()) {
            return true;
        }

        return false;
    }
}
