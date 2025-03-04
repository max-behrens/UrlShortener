<?php
namespace UrlShortener\Tests;

use PHPUnit\Framework\TestCase;
use UrlShortener\Service\UrlService;

class UrlServiceTest extends TestCase {
    private $urlService;
    private $tempDir;

    protected function setUp(): void {
        $this->tempDir = sys_get_temp_dir() . '/url_shortener_test_' . uniqid();
        mkdir($this->tempDir, 0777, true);
        $this->urlService = new UrlService($this->tempDir);
    }

    /**
     * Test encoding a valid URL.
     */
    public function testEncodeValidUrl() {
        $originalUrl = "https://www.example.com/very/long/url";
        $shortUrl = $this->urlService->encode($originalUrl);

        $this->assertIsString($shortUrl);
        $this->assertStringStartsWith('http://short.est/', $shortUrl);
    }

    /**
     * Test decoding a short URL.
     */
    public function testDecodeShortUrl() {
        $originalUrl = "https://www.example.com/very/long/url";
        $shortUrl = $this->urlService->encode($originalUrl);
        $shortCode = substr($shortUrl, strrpos($shortUrl, '/') + 1);

        $decodedUrl = $this->urlService->decode($shortCode);
        $this->assertEquals($originalUrl, $decodedUrl);
    }

    /**
     * Test that an invalid URL throws an exception.
     */
    public function testInvalidUrlThrowsException() {
        $this->expectException(\Exception::class);
        $this->urlService->encode('not a valid url');
    }

    /**
     * Test decoding a non-existent short code.
     */
    public function testDecodeNonExistentShortCode() {
        $this->expectException(\Exception::class);
        $this->urlService->decode('nonexistent');
    }
}