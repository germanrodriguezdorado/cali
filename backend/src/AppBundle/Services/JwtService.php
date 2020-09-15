<?php

namespace AppBundle\Services;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Usuario;



// Uso:
// $this->get("functions")->functionNombre(variable);


class JwtService extends Controller
{

	protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }



    /**
     * Returns token for user.
     *
     * @param Usuario $user
     *
     * @return array
     */
    public function getToken(Usuario $user)
    {
        return $this->container->get('lexik_jwt_authentication.encoder')
                ->encode([
                    'username' => $user->getUsername(),
                    'exp' => $this->getTokenExpiryDateTime(),
                ]);
    }
    /**
     * Returns token expiration datetime.
     *
     * @return string Unixtmestamp
     */
    private function getTokenExpiryDateTime()
    {
        $tokenTtl = $this->container->getParameter('lexik_jwt_authentication.token_ttl');
        $now = new \DateTime();
        $now->add(new \DateInterval("PT".$tokenTtl."S"));
        return $now->format("U");
    }
    



   



   
}