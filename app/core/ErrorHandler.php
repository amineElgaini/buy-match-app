<?php
require_once __DIR__ . '/Logger.php';
class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }

    public static function handleException(Throwable $e): void
    {
        Logger::error(
            $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()
        );

        $statusCode = ($e instanceof AppException)
            ? $e->getStatusCode()
            : 500;

        http_response_code($statusCode);

        if (self::isDebug()) {
            echo "<pre>";
            echo "Exception: " . get_class($e) . "\n";
            echo "Message: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . "\n";
            echo "Line: " . $e->getLine() . "\n";
            echo "</pre>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        exit;
    }

    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): bool {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    private static function isDebug(): bool
    {
        // return ($_ENV['APP_DEBUG'] ?? true) === true;
        return isset($_ENV['APP_DEBUG']) && strtolower($_ENV['APP_DEBUG']) === 'true';

    }
}
