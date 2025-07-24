<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class LibraryControllerJson extends AbstractController
{
    #[Route("api/library/book")]
    public function seeAllBooks(LibraryRepository $libraryRepository): Response
    {
        $books = $libraryRepository->findAll();

        $response = new JsonResponse($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/library/book/{isbn}")]
    public function seeOneBook(LibraryRepository $libraryRepository, string $isbn): Response
    {
        $book = $libraryRepository->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        $data = [
            'id' => $book->getId(),
            'titel' => $book->getTitel(),
            'author' => $book->getAuthor(),
            'ISBN' => $book->getISBN(),
            'image' => $book->getImage(),
        ];
        return new JsonResponse($data, 200, [], JSON_PRETTY_PRINT);
    }

}
