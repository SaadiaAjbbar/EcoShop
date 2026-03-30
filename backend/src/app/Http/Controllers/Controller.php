<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Mon API Documentation",
    version: "1.0.0",
    description: "Documentation dyal l-API b PHP 8.2 Attributes"
)]
#[OA\Server(url: 'http://localhost:8080/api', description: "API Server")]


// --- HADA HOWA L-JIDID ---
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Dkhel l-Token dialk hna bach t-authontifi"
)]


abstract class Controller
{
    //
}
