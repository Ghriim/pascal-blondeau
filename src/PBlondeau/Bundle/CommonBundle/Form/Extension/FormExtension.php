<?php

namespace PBlondeau\Bundle\CommonBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Add some attributes to all form types
 */
class FormExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array(
                                   'form_help',
                                   'form_tooltip',
                                   'form_tooltip_on_label',
                                   'target',
                                   'toggle',
                                   'widget_addon_prepend',
                                   'widget_addon_append',
                               ));

        $resolver->setDefaults(array(
                                   'form_row_attr'     => array(),
                                   'form_label_attr'   => array(),
                                   'form_widget_attr'  => array(),
                                   'horizontal'        => true,
                               ));

        $resolver->setAllowedTypes(array(
                                       'form_row_attr'        => 'array',
                                       'form_label_attr'      => 'array',
                                       'form_widget_attr'     => 'array',
                                       'horizontal'           => 'bool',
                                       'widget_addon_prepend' => 'array',
                                       'widget_addon_append'  => 'array',
                                   ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('form_help', $options)) {
            $view->vars['form_help'] = $options['form_help'];
        }

        if (array_key_exists('form_tooltip', $options)) {
            $view->vars['form_tooltip'] = $options['form_tooltip'];
        }

        if (array_key_exists('form_tooltip_on_label', $options)) {
            $view->vars['form_tooltip_on_label'] = $options['form_tooltip_on_label'];
        }

        if (array_key_exists('target', $options)) {
            $view->vars['target'] = $options['target'];
        }

        if (array_key_exists('toggle', $options)) {
            $view->vars['toggle'] = $options['toggle'];
        }

        $view->vars['form_row_attr']    = $options['form_row_attr'];
        $view->vars['form_label_attr']  = $options['form_label_attr'];
        $view->vars['form_widget_attr'] = $options['form_widget_attr'];
        $view->vars['horizontal']       = $options['horizontal'];

        if (array_key_exists('widget_addon_prepend', $options)) {
            $view->vars['widget_addon_prepend'] = $options['widget_addon_prepend'];
        }

        if (array_key_exists('widget_addon_append', $options)) {
            $view->vars['widget_addon_append'] = $options['widget_addon_append'];
        }
    }
}
