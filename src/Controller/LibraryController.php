<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_home')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/create', name: 'create_book')]
    public function create(): Response
    {
        return $this->render(
            'library/create.html.twig',
            ['message' => ""]
        );
    }

    #[Route('/library/create/book', name: 'create_book_form', methods: ['POST'])]
    public function createBook(Request $request, ManagerRegistry $doctrine): Response
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
    public function readMany(LibraryRepository $libraryRepository): Response
    {
        $library = $libraryRepository->findAll();

        return $this->render('library/library.html.twig', ['library' => $library]);
    }

    #[Route('/library/update', name: 'update_book')]
    public function update(LibraryRepository $libraryRepository): Response
    {
        $library = $libraryRepository->findAll();

        return $this->render('library/update.html.twig', [
            'library' => $library,
        ]);
    }


    #[Route('/library/update/form', name: 'update_book_form')]
    public function updateForm(Request $request, LibraryRepository $libraryRepository): Response
    {
        $isbn = $request->query->get('ISBN');

        if (!$isbn) {
            throw $this->createNotFoundException('No ISBN provided');
        }

        $book = $libraryRepository->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException('Book not found for ISBN: ' . $isbn);
        }

        return $this->render('library/update_book.html.twig', [
            'book' => $book,
        ]);
    }


    #[Route('/library/update/save/{ISBN}', name: 'update_book_save', methods: ['POST'])]
    public function updateSave(Request $request, LibraryRepository $libraryRepository, ManagerRegistry $doctrine, string $ISBN): Response
    {
        $book = $libraryRepository->findOneBy(['ISBN' => $ISBN]);
        $entityManager = $doctrine->getManager();

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $book->setTitel($request->request->get('titel'));
        $book->setAuthor($request->request->get('author'));
        $book->setImage($request->request->get('image'));

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('update_book');
    }


    #[Route('/library/delete', name: 'delete_book_choose')]
    public function delete_book(LibraryRepository $libraryRepository): Response
    {
        $library = $libraryRepository->findAll();

        return $this->render(
            'library/delete.html.twig',
            ['library' => $library ]
        );
    }

    #[Route('/library/delete/chosen', name: 'delete_book_isbn', methods: ['POST'])]
    public function delete(ManagerRegistry $doctrine, Request $request): Response
    {
        $isbn = $request->request->get('isbn');

        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for isbn '.$isbn
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('read_many');
    }
}
