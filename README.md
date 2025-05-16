# Operational Monolog Handler

A PSR-compliant [Monolog](https://github.com/Seldaek/monolog) handler that sends logs to [Operational.co](https://operational.co) via HTTP API.

> ⚠️ This project is neither officially supported by nor affiliated with Operational.co.  
> Compatibility has only been tested with PHP 8.1+ and Monolog 3.

---

## 📦 Installation

Install via Composer:

```
composer require noxomix/operational-monolog
```

## 🚀 Example usage:
```php
use Monolog\Logger;
use Noxomix\OperationalMonolog\OperationalHandler;

$logger = new Logger('operational');

$handler = new OperationalHandler('your-api-key-here');
$logger->pushHandler($handler);

$logger->info('User signup', ["name" => "Alfredo", "email" => "test@test.test"]);

```
