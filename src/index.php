<?php
// Set CORS headers to allow cross-origin requests from any domain.
header("Access-Control-Allow-Origin: *");
// Specify which HTTP methods are allowed for cross-origin requests.
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Allow Content-Type header in cross-origin requests.
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . '/../vendor/autoload.php';

use UrlShortener\Controller\UrlController;

// Handle preflight OPTIONS requests for CORS (browser checks if cross-origin request is allowed).
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$controller = new UrlController();

// Parse the request URI to get the path component (without query parameters).
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Get the HTTP method of the current request (POST, GET, etc.).
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST' && $uri === '/encode') {
        
        // Read and decode the JSON body of the request.
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate that a URL was provided in the request.
        if (!isset($input['url'])) {
            http_response_code(400);
            echo json_encode(['error' => 'URL is required']);
            exit;
        }

        // Call the encodeUrl method of the controller to generate a short Url.
        $shortUrl = $controller->encodeUrl($input['url']);
        
        // JSON_UNESCAPED_SLASHES prevents double-escaping of forward slashes.
        echo json_encode([
            'original_url' => $input['url'], 
            'short_url' => $shortUrl
        ], JSON_UNESCAPED_SLASHES);
    
    } elseif ($method === 'GET' && $uri === '/decode') {
        
        // Retrieve the short code from the query parameters, use null if not set.
        $code = $_GET['code'] ?? null;
        
        if (!$code) {
            http_response_code(400);
            echo json_encode(['error' => 'Code is required']);
            exit;
        }

        // Call the decodeUrl method of the controller to retrieve the original Url.
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