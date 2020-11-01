<?php

namespace AppBundle\Resources;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

// Uso:
// $this->get("functions")->functionNombre(variable);


class Functions extends Controller
{

    


    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
    }


    


   
}
