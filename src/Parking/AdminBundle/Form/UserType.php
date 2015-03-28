<?php

namespace Parking\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
    	 ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
         ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
         ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
         ->add('roles', 'collection', array(
        'type'   => 'choice',
        'options'  => array(
            'choices'  => array(
                'ROLE_ADMIN' => 'Admin',
                'ROLE_FLUX'  => 'Utilisateur',
                'ROLE_USER'  => 'Lecteur',
            ),
            
        ),
        ))
        
        ->add('enabled', 'checkbox')
        ->add('save',      'submit')
    ;

    // On ajoute une fonction qui va écouter l'évènement PRE_SET_DATA
    $builder->addEventListener(
      FormEvents::PRE_SET_DATA,
      function(FormEvent $event) {
        // On récupère notre objet Advert sous-jacent
        $user = $event->getData();

        if (null === $user) {
          return;
        }

/*
        if (!$user->getPublished() || null === $user->getId()) {
          $event->getForm()->add('published', 'checkbox', array('required' => false));
        } else {
          $event->getForm()->remove('published');
        }
        */
      }
    );
  }

  /**
   * @param OptionsResolverInterface $resolver
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Parking\UserBundle\Entity\User'
    ));
  }

  /**
   * @return string
   */
  public function getName()
  {
    return 'parking_Adminbundle_user';
  }
}