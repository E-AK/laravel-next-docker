<?php

namespace App\Controller\Api;

use App\Entity\Avatar;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

class AvatarUploadController extends AbstractController
{
    private const MAX_WIDTH = 800;
    private const MAX_HEIGHT = 600;
    private const ASPECT_RATIO = 4 / 3;

    private Imagine $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    #[Route('/api/avatar', methods: ['POST'])]
    public function execute(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $userId = $request->headers->get('X-User-Id');
        if (!$userId) {
            throw new AuthenticationException('Missing user id');
        }

        $userId = new Uuid($userId);

        $file = $request->files->get('avatar');
        if (!$file) {
            return new JsonResponse(['error' => 'No image file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
        if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0777, true) && !is_dir($uploadsDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $uploadsDir));
        }

        $filename = uniqid('', true) . '.' . $file->guessExtension();
        $filePath = $uploadsDir . '/' . $filename;

        $file->move($uploadsDir, $filename);

        $this->resizeAndCrop($filePath);

        $avatar = new Avatar();
        $avatar->setUserId($userId);
        $avatar->setPath("uploads/$filename");
        $em->persist($avatar);
        $em->flush();

        return new JsonResponse([
            'user_id' => $userId->toRfc4122(),
            'image_url' => "/uploads/$filename"
        ]);
    }

    private function resizeAndCrop(string $filePath): void
    {
        [$originalWidth, $originalHeight] = getimagesize($filePath);
        $originalRatio = $originalWidth / $originalHeight;

        if ($originalRatio > self::ASPECT_RATIO) {
            $newHeight = min($originalHeight, self::MAX_HEIGHT);
            $newWidth = $newHeight * self::ASPECT_RATIO;
        } else {
            $newWidth = min($originalWidth, self::MAX_WIDTH);
            $newHeight = $newWidth / self::ASPECT_RATIO;
        }

        $photo = $this->imagine->open($filePath);
        $resizedPhoto = $photo->resize(new Box($newWidth, $newHeight));

        $cropX = max(0, ($newWidth - self::MAX_WIDTH) / 2);
        $cropY = max(0, ($newHeight - self::MAX_HEIGHT) / 2);
        $finalPhoto = $resizedPhoto->crop(new Point($cropX, $cropY), new Box(min($newWidth, self::MAX_WIDTH), min($newHeight, self::MAX_HEIGHT)));

        $finalPhoto->save($filePath);
    }
}
