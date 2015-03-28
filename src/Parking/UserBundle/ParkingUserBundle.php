<?php

namespace Parking\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ParkingUserBundle extends Bundle
{
	public function getParent()
  	{	
    	return 'FOSUserBundle';
  	}
}
