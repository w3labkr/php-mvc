<?php

namespace App\Helpers;

/**
 * HttpStatusCode Class
 *
 * This class provides methods for converting between HTTP status texts and their numeric codes.
 */
class HttpStatusCode
{
    /**
     * Mapping of HTTP status texts to numeric codes.
     *
     * @var array
     */
    private static array $statusMap = [
        'CONTINUE' => 100,
        'SWITCHING_PROTOCOLS' => 101,
        'PROCESSING' => 102,
        'EARLY_HINTS' => 103,

        'OK' => 200,
        'CREATED' => 201,
        'ACCEPTED' => 202,
        'NON_AUTHORITATIVE_INFORMATION' => 203,
        'NO_CONTENT' => 204,
        'RESET_CONTENT' => 205,
        'PARTIAL_CONTENT' => 206,
        'MULTI_STATUS' => 207,

        'MULTIPLE_CHOICES' => 300,
        'MOVED_PERMANENTLY' => 301,
        'MOVED_TEMPORARILY' => 302,
        'SEE_OTHER' => 303,
        'NOT_MODIFIED' => 304,
        'USE_PROXY' => 305,
        'TEMPORARY_REDIRECT' => 307,
        'PERMANENT_REDIRECT' => 308,

        'BAD_REQUEST' => 400,
        'UNAUTHORIZED' => 401,
        'PAYMENT_REQUIRED' => 402,
        'FORBIDDEN' => 403,
        'NOT_FOUND' => 404,
        'METHOD_NOT_ALLOWED' => 405,
        'NOT_ACCEPTABLE' => 406,
        'PROXY_AUTHENTICATION_REQUIRED' => 407,
        'REQUEST_TIMEOUT' => 408,
        'CONFLICT' => 409,
        'GONE' => 410,
        'LENGTH_REQUIRED' => 411,
        'PRECONDITION_FAILED' => 412,
        'REQUEST_TOO_LONG' => 413,
        'REQUEST_URI_TOO_LONG' => 414,
        'UNSUPPORTED_MEDIA_TYPE' => 415,
        'REQUESTED_RANGE_NOT_SATISFIABLE' => 416,
        'EXPECTATION_FAILED' => 417,
        'IM_A_TEAPOT' => 418,
        'INSUFFICIENT_SPACE_ON_RESOURCE' => 419,
        'METHOD_FAILURE' => 420,
        'MISDIRECTED_REQUEST' => 421,
        'UNPROCESSABLE_ENTITY' => 422,
        'LOCKED' => 423,
        'FAILED_DEPENDENCY' => 424,
        'UPGRADE_REQUIRED' => 426,
        'PRECONDITION_REQUIRED' => 428,
        'TOO_MANY_REQUESTS' => 429,
        'REQUEST_HEADER_FIELDS_TOO_LARGE' => 431,
        'UNAVAILABLE_FOR_LEGAL_REASONS' => 451,

        'INTERNAL_SERVER_ERROR' => 500,
        'NOT_IMPLEMENTED' => 501,
        'BAD_GATEWAY' => 502,
        'SERVICE_UNAVAILABLE' => 503,
        'GATEWAY_TIMEOUT' => 504,
        'HTTP_VERSION_NOT_SUPPORTED' => 505,
        'INSUFFICIENT_STORAGE' => 507,
        'NETWORK_AUTHENTICATION_REQUIRED' => 511,
    ];

    /**
     * Get the HTTP status code for a given status text.
     *
     * This method converts the provided status text to uppercase (to ensure
     * case-insensitive matching) and returns the corresponding HTTP status code.
     * If the status text is not found, it returns 500.
     *
     * @param string $statusText The status text (e.g., "OK", "BAD_REQUEST").
     * @return int The corresponding HTTP status code, or 500 if not found.
     */
    public static function status(string $statusText)
    {
        // Convert status text to uppercase for case-insensitive lookup.
        $statusText = strtoupper($statusText);

        // Return the matching HTTP status code, defaulting to 500 if not found.
        return self::$statusMap[$statusText] ?? 500;
    }

    /**
     * Get the HTTP status text for a given status code.
     *
     * This method searches for the provided status code within the mapping array
     * and returns the corresponding status text. If the code is not found, it returns
     * "UNKNOWN_STATUS".
     *
     * @param int $status The HTTP status code (e.g., 200, 404).
     * @return string The corresponding status text, or "UNKNOWN_STATUS" if not found.
     */
    public static function statusText(int $status)
    {
        // Find the status text by searching for the status code in the mapping array.
        $text = array_search($status, self::$statusMap, true);

        // Return the found status text or "UNKNOWN_STATUS" if not found.
        return $text ?: "UNKNOWN_STATUS";
    }
}
