<?php
namespace UrlShortener\Service;

use Exception;

class UrlService {

    private $storageFile;


    private $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Constructor to set up storage file path.
     * 
     */
    public function __construct(string $dataDir) {
        $this->storageFile = $dataDir . '/urls.json';
        
        // Ensure data directory exists.
        $dir = dirname($this->storageFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Initialize storage file if it doesn't exist.
        if (!file_exists($this->storageFile)) {
            file_put_contents($this->storageFile, json_encode([]));
        }
    }

    /**
     * Validate a given URL.
     * 
     */
    private function validateUrl(string $url): bool {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Read contents of storage file.
     * 
     */
    private function readStorageFile(): array {
        $content = file_get_contents($this->storageFile);
        if ($content === false) {
            error_log("Failed to read storage file: {$this->storageFile}");
            return [];
        }
        
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            return [];
        }

        return $data ?? [];
    }

    /**
     * Write data to storage file.
     * 
     */
    private function writeStorageFile(array $data): void {
        $result = file_put_contents($this->storageFile, json_encode($data));
        if ($result === false) {
            error_log("Failed to write to storage file: {$this->storageFile}");
            throw new Exception("Failed to save URL mapping");
        }
    }

    /**
     * Find existing short code for a given URL.
     * 
     */
    private function findExistingCode(array $data, string $url): ?string {
        return array_search($url, $data) ?: null;
    }

    /**
     * Generate a random short code.
     * 
     */
    private function generateShortCode(array $existingData, int $length = 6): string {
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $this->characters[random_int(0, strlen($this->characters) - 1)];
            }
        } while (isset($existingData[$code]));

        return $code;
    }

    /**
     * Encode a long URL to a short URL.
     */
    public function encode(string $url): string {
        // Validate URL.
        if (!$this->validateUrl($url)) {
            throw new Exception("Invalid URL");
        }

        // Read existing data.
        $data = $this->readStorageFile();

        // Check if URL already exists.
        $existingCode = $this->findExistingCode($data, $url);
        if ($existingCode) {
            return "http://short.est/{$existingCode}";
        }

        // Generate unique short code.
        $shortCode = $this->generateShortCode($data);

        // Store the new mapping.
        $data[$shortCode] = $url;
        $this->writeStorageFile($data);

        return "http://short.est/{$shortCode}";
    }

    /**
     * Decode a short URL to its original long URL.
     */
    public function decode(string $shortCode): string {
        // Read existing data.
        $data = $this->readStorageFile();

        // Try direct match first.
        if (isset($data[$shortCode])) {
            return $data[$shortCode];
        }

        // If direct match fails, try to handle escaped URLs.
        foreach ($data as $code => $storedUrl) {
            // Remove escaped slashes for comparison.
            $cleanStoredUrl = stripslashes($storedUrl);
            
            if ($cleanStoredUrl === $storedUrl) {
                // If no change, try comparing the original.
                if ($code === $shortCode) {
                    return $storedUrl;
                }
            }
        }

        throw new Exception("Short URL not found");
    }
}