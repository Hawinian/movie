<?php
/**
 * Registration Controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\UserDataType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * Register action.
     *
     * @Route("/register", name="user_register")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('movie_index');
        }
        $user = new User();
        $userdata = new UserData();
        $form1 = $this->createForm(UserType::class, $user);
        $form2 = $this->createForm(UserDataType::class, $userdata);
        if ($request->isMethod('POST')) {
            $form1->handleRequest($request);
            $form2->handleRequest($request);

            if ($form1->isSubmitted()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form1->get('password')->getData()
                    )
                );
                $userdata->setUserData($user);
            }
            if ($form2->isSubmitted()) {
                //Handle
            }

            $user->setRoles(['ROLE_USER']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($userdata);
            $entityManager->flush();

            $this->addFlash('success', 'message.registered_successfully');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('registration/register.html.twig', [
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
        ]);
    }
}
