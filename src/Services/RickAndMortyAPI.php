<?php

namespace App\Services;

/*
  Rick and Morty API Service
  
  This class handles all communication with the Rick and Morty REST API.
  It encapsulates the HTTP request logic and provides a clean interface
  for the rest of the application to consume API data.
  
  Design decisions:
  - Uses PHP's native curl for HTTP requests (no external dependencies)
  - Implements caching to avoid unnecessary API calls
  - Provides typed responses for better IDE support
 */

class RickAndMortyAPI
{
    private string $baseUrl;
    private string $timeout;
    private array $cache = [];


    // Constructor to initialize the API service.    
    public function __construct()
    {
        $this->baseUrl = RICK_AND_MORTY_API_BASE_URL;
        $this->timeout = API_TIMEOUT;
    }

    /**
     * Fetches a list of characters from the Rick and Morty API.
     * 
     * @param array $filters Optional filters for the character search (name, status, species, gender).
     * @param int $page The page number for pagination (default is 1).
     * @return array The response from the API, including character data and pagination info.
     * @throws \Exception If the API request fails or returns an error.
     */
    public function getCharacters(array $filters = [], int $page = 1): array
    {
        $endpoint = '/character';

        // Build query parameters based on provided filters and page number
        $queryParams = array_filter([
            'page' => $page,
            'name' => $filters['name'] ?? null,
            'status' => $filters['status'] ?? null,
            'species' => $filters['species'] ?? null,
            'gender' => $filters['gender'] ?? null,
        ]);

        // Generate a unique cache key based on the endpoint and query parameters
        $cacheKey = md5($endpoint . serialize($queryParams));

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $response = $this->makeRequest($endpoint, $queryParams);
        $this->cache[$cacheKey] = $response;

        return $response;
    }

    /**
     * Fetches a single character by ID from the Rick and Morty API.
     */
    public function getCharacterById(int $id): array
    {
        $endpoint = "/character/{$id}";
        return $this->makeRequest($endpoint);
    }

    /**
     * Makes an HTTP GET request to the specified endpoint with optional query parameters.
     * 
     * @param string $endpoint The API endpoint to request.
     * @param array $queryParams Optional query parameters to include in the request.
     * @return array The decoded JSON response from the API.
     * @throws \Exception If the request fails or returns a non-200 status code.
     */
    public function makeRequest(string $endpoint, array $queryParams = []): array
    {
        $url = $this->baseUrl . $endpoint;

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("API Request Failed: {$error}");
        }
        // Handle 404 Not Found responses gracefully
        if ($httpCode === 404) {
            return [
                'info' => [
                    'count' => 0,
                    'pages' => 0,
                    'next' => null,
                    'prev' => null,
                ],
                'results' => []
            ];
        }

        // Handle non-200 HTTP responses
        if ($httpCode !== 200) {
            throw new \RuntimeException("API Request Failed with status code: {$httpCode}");
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Failed to decode JSON response: " . json_last_error_msg());
        }
    

        return $data;
    }
}
