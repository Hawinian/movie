<?php
/**
 * Registration Controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\RegistrationType;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
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
     * @param UserDataRepository           $repository
     * @param UserRepository               $userrepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserDataRepository $repository, UserRepository $userrepository): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('movie_index');
        }
        $user = new User();
        $userdata = new UserData();
        $form = $this->createForm(RegistrationType::class, $userdata);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('password')->getData()
                ));
                $user->setEmail($form->get('user')->get('email')->getData());
                $user->setUserData($userdata);
                $user->setRoles(['ROLE_USER']);
                $userrepository->saveUser($user);
                $repository->save($userdata);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userdata);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'message.registered_successfully');

                return $this->redirectToRoute('security_login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
