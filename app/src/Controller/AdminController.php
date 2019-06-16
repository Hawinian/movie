<?php
/**
 * Admin controller.
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Entity\UserData;
use App\Form\MovieType;
use App\Form\UserDataType;
use App\Form\UserPassType;
use App\Repository\MovieRepository;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route("/admin")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request    HTTP request
     * @param MovieRepository    $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="admin_index",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, MovieRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Movie::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param Movie $movie
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="admin_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function view(Movie $movie): Response
    {
        return $this->render(
            'admin/view.html.twig',
            ['movie' => $movie]
        );
    }

    /**
     * Edit action.
     *
     * @param Request         $request    HTTP request
     * @param Movie           $movie
     * @param MovieRepository $repository Movie repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_edit",
     * )
     *
     *   @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Movie $movie, MovieRepository $repository): Response
    {
        $form = $this->createForm(MovieType::class, $movie, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($movie);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            'admin/edit.html.twig',
            [
                'form' => $form->createView(),
                'movie' => $movie,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request         $request    HTTP request
     * @param Movie           $movie
     * @param MovieRepository $repository Movie repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_delete",
     * )
     *  @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Movie $movie, MovieRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $movie, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($movie);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            'admin/delete.html.twig',
            [
                'form' => $form->createView(),
                'movie' => $movie,
            ]
        );
    }

    /**
     * Users action.
     *
     * @param Request            $request    HTTP request
     * @param UserRepository     $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/users",
     *     name="admin_users",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function users(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryByNotAdmin(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/users.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     *  ChangeUserPassword action.
     *
     * @param Request                      $request         HTTP request
     * @param User                         $user
     * @param UserRepository               $repository      User repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/pass",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_change_pass",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function changeUserPassword(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserPassType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $repository->saveUser($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'admin/change/password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * ChangeUserData action.
     *
     * @param Request            $request
     * @param UserData           $userdata
     * @param UserDataRepository $repository
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/data",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_change_data",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function changeUserData(Request $request, UserData $userdata, UserDataRepository $repository): Response
    {
        $form = $this->createForm(UserDataType::class, $userdata, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($userdata);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'admin/change/data.html.twig',
            [
                'form' => $form->createView(),
                'userdata' => $userdata,
            ]
        );
    }

    /**
     * GrantAdmin action.
     *
     * @param Request        $request
     * @param User           $user
     * @param UserRepository $repository
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/grant",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_grant_role",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function grantAdmin(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            $repository->saveUser($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'admin/change/grant.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * DeleteUser action.
     *
     * @param Request        $request        HTTP request
     * @param User           $user
     * @param UserRepository $userrepository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete_user",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_delete_user",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser(Request $request, User $user, UserRepository $userrepository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if (array_key_exists('ROLE_ADMIN', $user->getRoles())) {
                $this->addFlash('danger', 'message.deleted_unsuccessfully');
            } else {
                $userrepository->deleteUser($user);
                $this->addFlash('success', 'message.deleted_successfully');
            }

            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'admin/change/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
