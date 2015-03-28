<?php
// src/OC/AdminBundle/Form/UserEditType.php

namespace Parking\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->remove('date');
  }

  public function getName()
  {
    return 'parking_adminbundle_user_edit';
  }

  public function getParent()
  {
    return new UserType();
  }
}