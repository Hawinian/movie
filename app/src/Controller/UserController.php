<?php
/**
 * User controller.
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
 * @Route("/user")
 *
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
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
     *     name="user_index",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, MovieRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryByAuthor($this->getUser()),
            $request->query->getInt('page', 1),
            Movie::NUMBER_OF_ITEMS
        );

        return $this->render(
            'user/index.html.twig',
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
     *     name="user_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function view(Movie $movie): Response
    {
        return $this->render(
            'user/view.html.twig',
            ['movie' => $movie]
        );
    }

    /**
     * New action.
     *
     * @param Request         $request    HTTP request
     * @param MovieRepository $repository Movie repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="user_new",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, MovieRepository $repository): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie->setAuthor($this->getUser());
            $repository->save($movie);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/new.html.twig',
            ['form' => $form->createView()]
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
     *     name="user_edit",
     * )
     *
     *    @IsGranted(
     *     "MANAGE",
     *     subject="movie",
     *     )
     */
    public function edit(Request $request, Movie $movie, MovieRepository $repository): Response
    {
        $form = $this->createForm(MovieType::class, $movie, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($movie);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/edit.html.twig',
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
     *     name="user_delete",
     * )
     * @IsGranted(
     *     "MANAGE",
     *     subject="movie",
     *     )
     */
    public function delete(Request $request, Movie $movie, MovieRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $movie->getId(), ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($movie);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'movie' => $movie,
            ]
        );
    }

    /**
     * changeData action.
     *
     * @param Request            $request    HTTP request
     * @param UserData           $userdata
     * @param UserDataRepository $repository UserData repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/data",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_change_data",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function changeData(Request $request, UserData $userdata, UserDataRepository $repository): Response
    {
        $form = $this->createForm(UserDataType::class, $userdata, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($userdata);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/change/data.html.twig',
            [
                'form' => $form->createView(),
                'userdata' => $userdata,
            ]
        );
    }

    /**
     * ChangePassword action.
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
     *     name="user_change_pass",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function changePassword(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
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

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/change/password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
