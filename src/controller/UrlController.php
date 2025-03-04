<?php
namespace UrlShortener\Controller;

use UrlShortener\Service\UrlService;
use Exception;

class UrlController {
    private $urlService;

    /**
     * Constructor to set up URL service.
     */
    public function __construct(string $dataDir = null) {
        // If no data dir provided, use a default.
        $dataDir = $dataDir ?? __DIR__ . '/../../data';
        $this->urlService = new UrlService($dataDir);
    }

    /**
     * Encode a long URL to a short URL.
     */
    public function encodeUrl(string $url): string {
        return $this->urlService->encode($url);
    }

    /**
     * Decode a short URL to its original long URL.
     */
    public function decodeUrl(string $shortCode): string {
        return $this->urlService->decode($shortCode);
    }
}