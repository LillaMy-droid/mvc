<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route('/api/view', name: 'api_view')]
    public function api(): Response
    {
        return $this->render('api.html.twig');
    }

    #[Route('/api/draw/card/option', name: 'api_option')]
    public function apiCard(): Response
    {
        return $this->render('draw.html.twig');
    }

    #[Route("/lucky/number/twig", name: "lucky_number")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route("/metrics", name: "metrics_report")]
    public function metrics(): Response
    {
        return $this->render('metrics.html.twig');
    }
}
