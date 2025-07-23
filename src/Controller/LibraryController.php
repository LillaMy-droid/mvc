<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
        return $this->render('library/create.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/read/{id}', name: 'read_book')]
    public function read_id(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/read', name: 'read_many')]
    public function read(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
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
