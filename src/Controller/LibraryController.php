<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_home')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'create_book')]
    public function create(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create/book', name: 'create_book_form', methods: ['POST'])]
    public function create_book(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $book = new Library();

        $book->setTitel($request->request->get('titel'));
        $book->setISBN($request->request->get('ISBN'));
        $book->setAuthor($request->request->get('author'));

        $entityManager->persist($book);

        $entityManager->flush();
        return $this->render(
            'library/create.html.twig',
            ['message' => "Book added to library"]
        );
    }

    #[Route('/library/read/{isbn}', name: 'read_book')]
    public function readBook(string $isbn, LibraryRepository $libraryRepository): Response
    {
        $book = $libraryRepository->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('library/book.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/read', name: 'read_many')]
    public function read(LibraryRepository $libraryRepository): Response
    {
        $library = $libraryRepository->findAll();
        
        return $this->render('library/library.html.twig',
        ['library' => $library]);
    }

    #[Route('/library/update', name: 'update_book')]
    public function update(): Response
    {
        return $this->render('library/update.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/delete', name: 'delete_book')]
    public function delete(): Response
    {
        return $this->render('library/delete.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/reset', name: 'reset_library')]
    public function reset(): Response
    {
        return $this->render('library/reset.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }
}
