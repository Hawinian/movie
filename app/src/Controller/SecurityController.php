<?php
/**
 * Security controller.
 */

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 *
 * * @Route(
 *     "/login",
 * )
 */
class SecurityController extends AbstractController
{
    /**
     * Login form action.
     *
     * @param AuthenticationUtils $authenticationUtils Auth utils
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="security_login",
     * )
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * Logout action.
     *
     * @throws Exception
     *
     * @Route(
     *     "/logout",
     *     name="security_logout",
     * )
     */
    public function logout(): void
    {
        // Request is intercepted before reaches this exception:
        throw new Exception('Internal security module error');
    }
}
