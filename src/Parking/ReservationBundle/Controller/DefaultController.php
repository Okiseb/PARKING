<?php

namespace Parking\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ParkingReservationBundle:Default:index.html.twig', array('name' => $name));
    }
}
