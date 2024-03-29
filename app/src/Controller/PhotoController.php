<?php
/**
* Photo controller.
*/

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use App\Service\FileUploader;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotoController.
 *
 * @Route("/photo")
 *
 * @IsGranted("ROLE_USER")
 */
class PhotoController extends AbstractController
{
    private $uploaderService = null;

    /**
     * PhotoController constructor.
     *
     * @param FileUploader $uploaderService
     */
    public function __construct(FileUploader $uploaderService)
    {
        $this->uploaderService = $uploaderService;
    }

    /**
     * New action.
     *
     * @param Request         $request    HTTP request
     * @param Movie           $movie
     * @param PhotoRepository $repository Photo repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "POST"},
     *     name="photo_new",
     * )
     *     @IsGranted(
     *     "MANAGE",
     *     subject="movie",
     *     )
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, Movie $movie, PhotoRepository $repository): Response
    {
        if ($movie->getPhoto()) {
            $photo = $movie->getPhoto();

            return $this->redirectToRoute(
                'photo_edit',
                ['id' => $photo->getId()]
            );
        }
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo->setMovie($movie);
            $repository->save($photo);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'photo/new.html.twig',
            ['form' => $form->createView(),
                    'movie' => $movie,
                ]
        );
    }

    /**
     * View action.
     *
     * @param Photo $photo Photo entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="photo_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function view(Photo $photo): Response
    {
        return $this->render(
            'photo/view.html.twig',
            ['photo' => $photo]
        );
    }

    /**
     * Edit action.
     *
     * @param Request         $request
     * @param Photo           $photo
     * @param PhotoRepository $repository
     * @param Filesystem      $filesystem Filesystem component
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="photo_edit",
     * )
     * @IsGranted(
     *     "MANAGE",
     *     subject="photo",
     *     )
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Photo $photo, PhotoRepository $repository, Filesystem $filesystem): Response
    {
        $originalPhoto = clone $photo;

        $form = $this->createForm(PhotoType::class, $photo, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            if ($formData->getFile() instanceof UploadedFile) {
                $repository->save($photo);
                /** @var File $file */
                $file = $originalPhoto->getFile();
                $filesystem->remove($file->getPathname());
            }

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute(
                'user_index'
            );
        }

        return $this->render(
            'photo/edit.html.twig',
            [
            'form' => $form->createView(),
            'photo' => $photo,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request         $request
     * @param Photo           $photo
     * @param PhotoRepository $repository
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="photo_delete",
     * )
     *     @IsGranted(
     *     "MANAGE",
     *     subject="photo",
     *     )
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Photo $photo, PhotoRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $photo, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($photo);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'photo/delete.html.twig',
            [
                'form' => $form->createView(),
                'photo' => $photo,
            ]
        );
    }
}
