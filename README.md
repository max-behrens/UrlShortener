## URL Shortener Service

### Prerequisites

- **PHP 7.4+**
- **Composer**

### Setup Instructions

1. **Clone the Repository**
```
git clone https://github.com/yourusername/url-shortener.git
cd url-shortener
```

2. **Install Dependencies**
```
composer install
composer require --dev phpunit/phpunit ^9.6
```

3. **Start the PHP Built-in Server**
```
php -S localhost:8000 src/index.php
```

*(Replace your composer.json with the following if needed):*

```json
{
    "name": "url-shortener/service",
    "type": "project",
    "require-dev": {
        "phpunit/phpunit": "^9.5"
    },
    "autoload": {
        "psr-4": {
            "UrlShortener\\": "src/"
        }
    }
}
```

### API Endpoints

#### Encode URL

- **Method:** POST
- **Endpoint:** `http://localhost:8000/encode`
- **Content-Type:** application/json

**Request Body:**
```json
{
  "url": "https://www.example.com/very/long/url"
}
```

**Response:**
```json
{
  "original_url": "https://www.example.com/very/long/url",
  "short_url": "http://short.est/oUzdcH"
}
```

#### Decode URL

- **Method:** GET
- **Endpoint:** `http://localhost:8000/decode?code=oUzdcH`

**Response:**
```json
{
  "short_url": "oUzdcH",
  "original_url": "https://www.example.com/very/long/url"
}
```

### PowerShell Commands

#### Encode URL
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/encode" `
  -Method Post `
  -ContentType "application/json" `
  -Body '{"url":"https://www.example.com/very/long"}'
```

**Along with the HTTP response, we will return:**
```json
{
  "original_url":"https://www.example.com/very/long",
  "short_url":"http://short.est/LeSRNl"
}
```

#### Decode URL

```powershell
Invoke-WebRequest -Uri "http://localhost:8000/decode?code=LeSRNl" `
  -Method Get
```

**Along with the HTTP response, we will return:**
```json
{
  "short_url":"LeSRNl",
  "original_url":"https://www.example.com/very/long"
}
```

### PHPUnit Testing

**Run the following to execute tests:**
```
./vendor/bin/phpunit tests/UrlServiceTest.php
```

### Notes

- URLs are stored in `data/urls.json`
- Short URLs are randomly generated 6-character codes
- Repeated URLs will return the same short code
