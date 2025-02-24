<?php

namespace App\Helpers;

/**
 * ApiResponse Class
 *
 * This class provides helper methods to send JSON and plain text HTTP responses.
 */
class ApiResponse
{
    /**
     * Send a JSON response.
     *
     * This method sets the HTTP response code, Content-Type header, and outputs a JSON encoded response.
     * The response includes a status (success or fail), a message, and optional data.
     *
     * @param int    $status  The HTTP status code (default is 200).
     * @param string $message The message to include in the response. If empty, a default message is derived from the status code.
     * @param array  $data    Additional data to include in the response.
     * @return void
     */
    public function json(int $status = 200, string $message = '', array $data = [])
    {
        // Set the HTTP response code.
        http_response_code($status);
        // Set the Content-Type header to indicate a JSON response.
        header('Content-Type: application/json');

        // Determine if the response should be considered a success.
        $success = $status >= 200 && $status < 300;

        // Build the response array with status, success flag, message, and data.
        $response = [
            'status'  => $success ? 'success' : 'fail',
            'success' => $success,
            'message' => $message ?: http_status_text($status),
            'data'    => $data,
        ];

        // Output the JSON encoded response.
        echo json_encode($response);
        return;
    }

    /**
     * Send a plain text response.
     *
     * This method sets the HTTP response code, Content-Type header, and outputs a plain text message.
     *
     * @param int    $status  The HTTP status code (default is 200).
     * @param string $message The message to send (default is an empty string).
     * @return void
     */
    public function text(int $status = 200, string $message = '')
    {
        // Set the HTTP response code.
        http_response_code($status);
        // Set the Content-Type header to indicate plain text output.
        header('Content-Type: text/plain');

        // Output the plain text message.
        echo $message;
        return;
    }
}
