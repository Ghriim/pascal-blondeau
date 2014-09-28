<?php

namespace PBlondeau\Bundle\CommonBundle\Form\Type;

use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StatusType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'label' => 'form.status.label',
			'choices' => array(
				BaseEntity::STATUS_ACTIVE => 'form.status.option.' . BaseEntity::STATUS_ACTIVE,
				BaseEntity::STATUS_STOPPED => 'form.status.option.' . BaseEntity::STATUS_STOPPED,
			),
			'required' => true,
			'multiple' => false,
			'translation_domain' => 'common'
		));
	}

	public function getParent()
	{
		return 'genemu_jquerychosen_choice';
	}

	public function getName()
	{
		return 'pblondeau_status';
	}

}