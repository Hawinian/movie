<?php
/**
 * Movie controller.
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MovieController.
 */
class MovieController extends AbstractController
{
    private $uploaderService = null;

    /**
     * MovieController constructor.
     *
     * @param FileUploader $uploaderService
     */
    public function __construct(FileUploader $uploaderService)
    {
        $this->uploaderService = $uploaderService;
    }

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
     *     name="movie_index",
     * )
     */
    public function index(Request $request, MovieRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Movie::NUMBER_OF_ITEMS
        );

        return $this->render(
            'movie/index.html.twig',
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
     *     name="movie_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Movie $movie): Response
    {
        return $this->render(
            'movie/view.html.twig',
            ['movie' => $movie]
        );
    }
}
