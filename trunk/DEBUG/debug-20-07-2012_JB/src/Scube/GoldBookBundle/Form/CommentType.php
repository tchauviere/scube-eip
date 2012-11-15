<?php

namespace Scube\GoldBookBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('inform', 'checkbox');
        $builder->add('comm', 'textarea');
        $builder->add('idea', 'textarea');
    }

    public function getName()
    {
        return 'contact';
    }
}