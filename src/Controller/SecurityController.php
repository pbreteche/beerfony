<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 27/04/18
 * Time: 11:46
 */

namespace App\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController
{

    /**
     * @Route("/login")
     * @Template()
     */
    public function login(AuthenticationUtils $utils)
    {
        $lastError = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        return [
            'last_error' => $lastError,
            'last_username' => $lastUsername,
        ];
    }

    /**
     * @Route("/login_check")
     */
    public function loginCheck()
    {
        throw new \Exception('this method should not be called');
    }

    /**
     * @Route("/logout")
     */
    public function logout()
    {
        throw new \Exception('this method should not be called');
    }
}