<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("api")]
    public function jsonIndex(): Response
    {

        $data = [
            'Du har hittat till min JSON-sida!
            Vill du ha en ordvits? Testa: /api/quote',
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("api/quote")]
    public function jsonQuote(): Response
    {
        $number = random_int(0, 4);
        if ($number === 1) {
            $quote = "Vad kallar man en hund som kan trolla? - En labra-kadabra-dor!";
        } elseif ($number === 2) {
            $quote = "Har du sett filmen om lastbilen? - Nej, men jag har sett trailern.";
        } else {
            $quote = "Vilket djur flyger rakast? - Antiloop.";
        }
        $date = date("Y/m/d");
        $time = date("h:i:sa");

        $data = [
            'Din dagliga ordvits ' => $quote,
            'dagens datum ' => $date,
            'och tiden ' => $time
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
