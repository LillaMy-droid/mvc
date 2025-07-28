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
        $titel = $request->request->get("titel");

        $book->setTitel($titel);
        $book->setIsbn($request->request->get('Isbn'));
        $book->setAuthor($request->request->get('author'));

        $entityManager->persist($book);

        $entityManager->flush();
        return $this->render(
            'library/create.html.twig',
            ['message' => "Book added to library"]
        );
    }

    #[Route('/library/read/{Isbn}', name: 'read_book')]
    public function readBook(string $Isbn, LibraryRepository $libraryRepository): Response
    {
        $book = $libraryRepository->findOneBy(['Isbn' => $Isbn]);

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
        $Isbn = $request->query->get('Isbn');

        if (!$Isbn) {
            throw $this->createNotFoundException('No Isbn provided');
        }

        $book = $libraryRepository->findOneBy(['Isbn' => $Isbn]);

        if (!$book) {
            throw $this->createNotFoundException('Book not found for Isbn: ' . $Isbn);
        }

        return $this->render('library/update_book.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/update/save/{Isbn}', name: 'update_book_save', methods: ['POST'])]
    public function updateSave(Request $request, LibraryRepository $libraryRepository, ManagerRegistry $doctrine, string $Isbn): Response
    {
        $book = $libraryRepository->findOneBy(['Isbn' => $Isbn]);
        $entityManager = $doctrine->getManager();

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $book->setTitel($request->request->get('titel'));
        $book->setAuthor($request->request->get('author'));

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
        $Isbn = $request->request->get('Isbn');

        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->findOneBy(['Isbn' => $Isbn]);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for Isbn '.$Isbn
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('read_many');
    }
}
