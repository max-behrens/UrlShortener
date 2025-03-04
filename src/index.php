<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . '/../vendor/autoload.php';

use UrlShortener\Controller\UrlController;

// Handle preflight requests.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$controller = new UrlController();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST' && $uri === '/encode') {
        
        // Encode url.
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['url'])) {
            http_response_code(400);
            echo json_encode(['error' => 'URL is required']);
            exit;
        }

        $shortUrl = $controller->encodeUrl($input['url']);
        echo json_encode([
            'original_url' => $input['url'], 
            'short_url' => $shortUrl
        ], JSON_UNESCAPED_SLASHES);
    } elseif ($method === 'GET' && $uri === '/decode') {
        
        // Decode url.
        $code = $_GET['code'] ?? null;
        
        if (!$code) {
            http_response_code(400);
            echo json_encode(['error' => 'Code is required']);
            exit;
        }

        $originalUrl = $controller->decodeUrl($code);
        echo json_encode([
            'short_url' => $code, 
            'original_url' => $originalUrl
        ], JSON_UNESCAPED_SLASHES);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}