<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$q = urlencode($_GET['q']);
$url = "https://geocode.maps.co/search?q=$q";

// IMPORTANTE: si en el futuro usás API key, solo agregala:
/// $url .= "&api_key=TU_API_KEY";

$response = file_get_contents($url);

echo $response;