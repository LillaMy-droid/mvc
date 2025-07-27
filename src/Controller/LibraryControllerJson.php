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

        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'titel' => $book->getTitel(),
                'author' => $book->getAuthor(),
                'ISBN' => $book->getISBN(),
                'image' => null,
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/library/book/{Isbn}")]
    public function seeOneBook(LibraryRepository $libraryRepository, string $Isbn): Response
    {
        $book = $libraryRepository->findOneBy(['Isbn' => $Isbn]);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        $data = [
            'titel' => $book->getTitel(),
            'author' => $book->getAuthor(),
            'ISBN' => $book->getISBN(),
            'image' => null,
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

}
