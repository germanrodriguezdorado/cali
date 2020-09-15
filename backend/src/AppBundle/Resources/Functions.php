<?php

namespace AppBundle\Resources;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

// Uso:
// $this->get("functions")->functionNombre(variable);


class Functions extends Controller
{

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get("doctrine")->getManager();
    }


   




    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
    }





    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomString .= date("YmdHis");
        return $randomString;
    }  


    

    


   
}
