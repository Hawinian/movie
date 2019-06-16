<?php
/**
 * Photo upload listener.
 */

namespace App\EventListener;

use App\Entity\Photo;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PhotoUploadListener.
 */
class PhotoUploadListener
{
    /**
     * Uploader service.
     *
     * @var FileUploader|null
     */
    protected $uploaderService = null;

    /**
     * PhotoUploadListener constructor.
     *
     * @param FileUploader $fileUploader File uploader service
     */
    public function __construct(FileUploader $fileUploader)
    {
        $this->uploaderService = $fileUploader;
    }

    /**
     * Pre remove.
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
    }

    /**
     * Pre persist.
     *
     * @param LifecycleEventArgs $args Event args
     *
     * @throws Exception
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Pre update.
     *
     * @param PreUpdateEventArgs $args Event args
     *
     * @throws Exception
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Post load.
     *
     * @param LifecycleEventArgs $args Event args
     *
     * @throws Exception
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Photo) {
            return;
        }

        if ($fileName = $entity->getFile()) {
            $entity->setFile(
                new File(
                    $this->uploaderService->getTargetDirectory().'/'.$fileName
                )
            );
        }
    }

    /**
     * Upload file.
     *
     * @param Photo $entity Photo entity
     *
     * @throws Exception
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Photo) {
            return;
        }

        $file = $entity->getFile();
        if ($file instanceof UploadedFile) {
            $filename = $this->uploaderService->upload($file);
            $entity->setFile($filename);
        }
    }

    /**
     * Remove file from disk.
     *
     * @param Photo $entity Photo entity
     */
    private function removeFile($entity): void
    {
        if (!$entity instanceof Photo) {
            return;
        }

      /*  $file = $entity->getFile();
        if ($file instanceof File) {
            $filesystem = new Filesystem();
            $this->$filesystem->remove($file->getPathname());
        }*/
    }
}
