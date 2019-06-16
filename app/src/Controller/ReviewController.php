<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReviewController.
 *
 * @Route("/review")
 *
 * @IsGranted("ROLE_USER")
 */
class ReviewController extends AbstractController
{
    /**
     * New action.
     *
     * @param Request          $request    HTTP request
     * @param Movie            $movie
     * @param ReviewRepository $repository Review repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/new_review",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="review_new",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, Movie $movie, ReviewRepository $repository): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setMovie($movie);
            $review->setAuthor($this->getUser());
            $repository->save($review);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('movie_index');
        }

        return $this->render(
            'review/new.html.twig',
            ['form' => $form->createView(),
                'movie' => $movie, ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request          $request    HTTP request
     * @param Review           $review
     * @param ReviewRepository $repository Movie repository
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
     *     name="review_delete",
     * )
     * @IsGranted("ROLE_ADMIN");
     */
    public function delete(Request $request, Review $review, ReviewRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $review, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($review);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            'review/delete.html.twig',
            [
                'form' => $form->createView(),
                'review' => $review,
            ]
        );
    }
}
