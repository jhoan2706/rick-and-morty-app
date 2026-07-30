<?php
namespace App\Services;

/**
 * Helper functions for the application.
 * 
 * Collects utility methods that are used across different parts of the application, such as escaping output, generating CSS classes based on character status, and building query strings.
 */

class Helpers
{
    /**
     * Escape HTML output to prevent XSS attacks.
     *
     * @param string $string The string to escape.
     * @return string The escaped string.
     */
    public static function escape(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Get CSS classes based on the character's status.
     *
     * @param string $status The status of the character (e.g., "Alive", "Dead", "Unknown").
     * @return string The corresponding CSS classes for styling.
     */
    public static function getStatusClasses(string $status): string
    {
        return match (strtolower($status)) {
            'alive' => 'bg-green-500/20 text-green-400 border-green-500/30',
            'dead' => 'bg-red-500/20 text-red-400 border-red-500/30',
            'unknown' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
            default => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
        };
    }

    /**
     * Get the color of the status dot based on the character's status.
     *
     * @param string $status The status of the character (e.g., "Alive", "Dead", "Unknown").
     * @return string The corresponding CSS class for the status dot color.
     */
    public static function getStatusDotColor(string $status): string
    {
        return match (strtolower($status)) {
            'alive' => 'bg-green-500',
            'dead' => 'bg-red-500',
            'unknown' => 'bg-gray-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Build a query string from an array of parameters, allowing for overrides.
     *
     * @param array $params The base parameters for the query string.
     * @param array $overrides Parameters that will override the base parameters.
     * @return string The constructed query string starting with '?'.
     */
    public static function buildQueryString(array $params, array $overrides = []): string
    {
        $merged = array_merge($params, $overrides);
        $filtered = array_filter($merged, fn($value) => $value !== null && $value !== '');

        return '?' . http_build_query($filtered);
    }

    /**
     * Check if an array is associative.
     *
     * @param array $array The array to check.
     * @return bool True if the array is associative, false otherwise.
     */
    public static function isAssoc(array $array): bool
    {
        if ([] === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }


}