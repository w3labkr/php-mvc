<?php

namespace App\Helpers;

class ApiResponse {
    /**
     * Return a JSON response.
     *
     * @param int    $httpStatusCode HTTP status code (e.g. 200, 400, 404, etc.)
     * @param string $message        Response message
     * @param array  $data           Additional data (default is an empty array)
     */
    public function json(int $httpStatusCode = 200, string $message = 'OK', array $data = []) {
        http_response_code($httpStatusCode);
        header('Content-Type: application/json');

        // If the HTTP status code is below 400, consider it a success
        $success = ($httpStatusCode < 400);
        $status = $success ? 'success' : 'fail';

        $response = [
            'status'  => $status,
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ];

        echo json_encode($response);
        return;
    }

    /**
     * Return a plain text response.
     *
     * @param int    $httpStatusCode HTTP status code (e.g. 200, 400, 404, etc.)
     * @param string $message        Response message
     */
    public function text(int $httpStatusCode = 200, string $message = 'OK') {
        http_response_code($httpStatusCode);
        header('Content-Type: text/plain');

        // Output the plain text message
        echo $message;
        return;
    }

    /**
     * Return a success JSON response with a custom HTTP status code (default is 200).
     *
     * @param string $message        Success message
     * @param array  $data           Additional data (default is an empty array)
     * @param int    $httpStatusCode HTTP status code (default is 200)
     */
    public function success(string $message = 'OK', array $data = [], int $httpStatusCode = 200) {
        // Send a successful JSON response with the specified HTTP status code
        $this->json($httpStatusCode, $message, $data);
    }

    /**
     * Return an error JSON response with a specified HTTP status code.
     *
     * @param string $message        Error message
     * @param array  $data           Additional data (default is an empty array)
     * @param int    $httpStatusCode HTTP status code (default is 400 for client errors)
     */
    public function error(string $message = 'Bad Request', array $data = [], int $httpStatusCode = 400) {
        // Send an error JSON response with the specified HTTP status code
        $this->json($httpStatusCode, $message, $data);
    }
}
