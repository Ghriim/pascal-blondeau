<?php

namespace PBlondeau\Bundle\CommonBundle\Form\Type;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PositionType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'label' => 'form.position.label',
			'required' => false,
			'translation_domain' => 'common',
			'attr' => array('min' => BaseEntity::POSITION_MIN_VALUE_DEFAULT)
		));
	}

	public function getParent()
	{
		return 'integer';
	}

	public function getName()
	{
		return 'pblondeau_position';
	}

}