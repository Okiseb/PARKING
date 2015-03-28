<?php
// src/OC/PlatformBundle/Controller/UserController.php

namespace Parking\AdminBundle\Controller;


use Parking\AdminBundle\Form\UserType;
use Parking\AdminBundle\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
  public function indexAction()
  {
/*
// Dans un contrôleur :

// Pour récupérer le service UserManager du bundle
$userManager = $this->get('fos_user.user_manager');

// Pour charger un utilisateur
$user = $userManager->findUserBy(array('username' => 'winzou'));

// Pour modifier un utilisateur
$user->setEmail('cetemail@nexiste.pas');
$userManager->updateUser($user); // Pas besoin de faire un flush avec l'EntityManager, cette méthode le fait toute seule !

// Pour supprimer un utilisateur
$userManager->deleteUser($user);

// Pour récupérer la liste de tous les utilisateurs
$users = $userManager->findUsers();
*/

$userManager = $this->get('fos_user.user_manager');
$users = $userManager->findUsers();

/*
	$userManager = $this->get('fos_user.user_manager');
	// Pour charger un utilisateur
	$user = $userManager->findUserBy(array('username' => 'admin'));
    $user->addRole('ROLE_ADMIN');
    $userManager->updateUser($user);
*/
    // On donne toutes les informations nécessaires à la vue
    return $this->render('ParkingAdminBundle:User:index.html.twig', array(
      'listUsers' => $users
    ));
    
    

    

  }

  public function viewAction($username)
  {
    // On récupère l'EntityManager
    $userManager = $this->get('fos_user.user_manager');

    // Pour récupérer une annonce unique : on utilise find()
    $user = $userManager->findUserBy(array('username' => $username));
    

    // On vérifie que l'annonce avec cet id existe bien
    if ($user === null) {
      throw $this->createNotFoundException("L'utilisateur ".$username." n'existe pas.");
    }

  
    // Puis modifiez la ligne du render comme ceci, pour prendre en compte les variables :
    return $this->render('ParkingAdminBundle:User:view.html.twig', array(
      'user'           => $user,
    ));
  }

  public function addAction(Request $request)
  {
    $userManager = $this->get('fos_user.user_manager');
    $user = $userManager->createUser();
    $form = $this->createForm(new UserType(), $user);
    

    if ($form->handleRequest($request)->isValid()) {
      
      
       $userManager->updateUser($user, true);

      $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien enregistré.');

      return $this->redirect($this->generateUrl('parking_admin_view', array('username' => $user->getUsername())));
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('ParkingAdminBundle:User:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function editAction($username, Request $request)
  {
    // On récupère l'EntityManager
    $userManager = $this->get('fos_user.user_manager');

    // Pour récupérer une annonce unique : on utilise find()
    $user = $userManager->findUserBy(array('username' => $username));
    

    // On vérifie que l'annonce avec cet id existe bien
    if ($user === null) {
      throw $this->createNotFoundException("L'utilisateur ".$username." n'existe pas.");
    }

    $form = $this->createForm(new UserEditType(), $user);

    if ($form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $userManager->updateUser($user, true);

      $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien modifié.');

      return $this->redirect($this->generateUrl('parking_admin_view', array('username' => $user->getUsername())));
    }

    return $this->render('ParkingAdminBundle:User:edit.html.twig', array(
      'form'   => $form->createView(),
      'user' => $user // Je passe également l'annonce à la vue si jamais elle veut l'afficher
    ));
  }

  public function deleteAction($username, Request $request)
  {
    // On récupère l'EntityManager
    $userManager = $this->get('fos_user.user_manager');

    // Pour récupérer une annonce unique : on utilise find()
    $user = $userManager->findUserBy(array('username' => $username));
    

    // On vérifie que l'annonce avec cet id existe bien
    if ($user === null) {
      throw $this->createNotFoundException("L'utilisateur ".$username." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->createFormBuilder()->getForm();

    if ($form->handleRequest($request)->isValid()) {
      $userManager->deleteUser($user);

      $request->getSession()->getFlashBag()->add('info', "L'utilisateur a bien été supprimé.");

      return $this->redirect($this->generateUrl('parking_admin_home'));
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('ParkingAdminBundle:User:delete.html.twig', array(
      'user' => $user,
      'form'   => $form->createView()
    ));
  }
  
  
  

  

  
  
  
}
