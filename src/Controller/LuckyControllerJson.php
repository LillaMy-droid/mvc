<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("api", name:"api")]
    public function jsonIndex(): Response
    {
        $data = [
            'Du har hittat till min JSON-sida!
            Vill du ha en ordvits? Testa: /api/quote
            Sortera en kortlek: /api/deck
            Blanda kortleken: /api/shuffle
            Dra kort ur en kortlek: /api/draw
            ',
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("api/quote", name:"api_quote")]
    public function jsonQuote(): Response
    {
        $number = random_int(1, 3);
        $quote = "";
        if ($number === 1) {
            $quote = "Vad kallar man en hund som kan trolla? - En labra-kadabra-dor!";
        }
        if ($number === 2) {
            $quote = "Har du sett filmen om lastbilen? - Nej, men jag har sett trailern.";
        }
        if ($number === 3) {
            $quote = "Vilket djur flyger rakast? - Antiloop.";
        }

        $data = [
            'Din dagliga ordvits ' => $quote,
            'dagens datum ' => date("Y/m/d"),
            'och tiden ' => date("h:i:s")
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
