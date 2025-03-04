URL Shortener Service
Prerequisites

PHP 7.4+
Composer

Setup Instructions
1. Clone the Repository
git clone https://github.com/yourusername/url-shortener.git
cd url-shortener
2. Install Dependencies
composer install
composer require --dev phpunit/phpunit ^9.6
3. Start the PHP Built-in Server
php -S localhost:8000 src/index.php


(Replace your composer.json with the following if needed):

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


API Endpoints
Encode URL

Method: POST
Endpoint: http://localhost:8000/encode
Content-Type: application/json
Request Body:
jsonCopy{
  "url": "https://www.example.com/very/long/url"
}

Response:
jsonCopy{
  "original_url": "https://www.example.com/very/long/url",
  "short_url": "http://short.est/oUzdcH"
}


Decode URL

Method: GET
Endpoint: http://localhost:8000/decode?code=oUzdcH
Response:
jsonCopy{
  "short_url": "oUzdcH",
  "original_url": "https://www.example.com/very/long/url"
}




PowerShell Commands

Encode URL

Invoke-WebRequest -Uri "http://localhost:8000/encode" `
  -Method Post `
  -ContentType "application/json" `
  -Body '{"url":"https://www.example.com/very/long"}'


  This will return:

  StatusCode        : 200
StatusDescription : OK
Content           : {"original_url":"https://www.example.com/very/long","short_url":"http://short.est/LeSRNl"}
RawContent        : HTTP/1.1 200 OK
                    Host: localhost:8000
                    Connection: close
                    Access-Control-Allow-Origin: *
                    Access-Control-Allow-Methods: POST, GET, OPTIONS
                    Access-Control-Allow-Headers: Content-Type
                    Content-Type: ap...
Forms             : {}
Headers           : {[Host, localhost:8000], [Connection, close], [Access-Control-Allow-Origin, *],
                    [Access-Control-Allow-Methods, POST, GET, OPTIONS]...}
Images            : {}
InputFields       : {}
Links             : {}
ParsedHtml        : mshtml.HTMLDocumentClass
RawContentLength  : 90


Decode URL (Use the code in the shortened url from the encode url output.)

Invoke-WebRequest -Uri "http://localhost:8000/decode?code=LeSRN" `
  -Method Get

This will return:

StatusCode        : 200
StatusDescription : OK
Content           : {"short_url":"LeSRNl","original_url":"https://www.example.com/very/long"}
RawContent        : HTTP/1.1 200 OK
                    Host: localhost:8000
                    Connection: close
                    Access-Control-Allow-Origin: *
                    Access-Control-Allow-Methods: POST, GET, OPTIONS
                    Access-Control-Allow-Headers: Content-Type
                    Content-Type: ap...
Forms             : {}
Headers           : {[Host, localhost:8000], [Connection, close], [Access-Control-Allow-Origin, *],
                    [Access-Control-Allow-Methods, POST, GET, OPTIONS]...}
Images            : {}
InputFields       : {}
Links             : {}
ParsedHtml        : mshtml.HTMLDocumentClass
RawContentLength  : 73



PHPUnit Testing:

Run the following to run tests:
./vendor/bin/phpunit tests/UrlServiceTest.php


Notes

URLs are stored in data/urls.json
Short URLs are randomly generated 6-character codes
Repeated URLs will return the same short code