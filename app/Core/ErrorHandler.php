<?php

require_once __DIR__ . '/Logger.php';

class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && self::isFatal($error['type'])) {

            // Create an ErrorException object to reuse logging
            $e = new ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );

            // Log the fatal error
            Logger::error(
                "Fatal Error: " . $e->getMessage() .
                    " in " . $e->getFile() .
                    ":" . $e->getLine()
            );

            // Optionally render it
            if (php_sapi_name() === 'cli') {
                echo "Fatal Error: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}\n";
            } else {
                $isApi = isset($_SERVER['HTTP_ACCEPT']) &&
                    strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

                if ($isApi) {
                    self::renderJson($e);
                } else {
                    self::renderHtml($e);
                }
            }
        }
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

        $isApi = isset($_SERVER['HTTP_ACCEPT']) &&
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if ($isApi) {
            self::renderJson($e);
        } else {
            self::renderHtml($e);
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

    private static function renderJson(Throwable $e): void
    {
        header('Content-Type: application/json');

        if (self::isDebug()) {
            echo json_encode([
                'error'   => get_class($e),
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], JSON_PRETTY_PRINT);
        } else {
            echo json_encode([
                'error'   => 'Server Error',
                'message' => 'Something went wrong. Please try again later.'
            ]);
        }
    }

    private static function renderHtml(Throwable $e): void
    {
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
    }

    private static function isDebug(): bool
    {
        return isset($_ENV['APP_DEBUG']) &&
            strtolower($_ENV['APP_DEBUG']) === 'true';
    }

    private static function isFatal(int $type): bool
    {
        return in_array($type, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR]);
    }
}
