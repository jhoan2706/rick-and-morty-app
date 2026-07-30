<?php

namespace App\Components;

use App\Services\Helpers;

class Pagination
{
    public static function render(array $info, int $currentPage, array $filters = []): string
    {
        $totalPages = $info['pages'] ?? 1;

        if ($totalPages <= 1) {
            return ''; // No pagination needed if there's only one page
        }

        $pages = self::getPageRange($currentPage, $totalPages);

        $baseParams = array_filter($filters, fn($value) => $value !== 'page', ARRAY_FILTER_USE_KEY);

        $html = '<nav class="flex justify-center items-center space-x-4 mt-6" aria-label="Pagination">';

        $prevDisabled = $currentPage <= 1;
        $html .= self::renderPageButton(
            $prevDisabled ? '#' : Helpers::buildQueryString($baseParams, ['page' => $currentPage - 1]),
            'Previous',
            $prevDisabled,
            true
        );

        // Page numbers
        foreach ($pages as $page) {
            if ($page === '...') {
                $html .= '<span class="px-3 py-2 text-slate-500">...</span>';
            } else {
                $isActive = $page === $currentPage;
                $url = Helpers::buildQueryString($baseParams, ['page' => $page]);

                if ($isActive) {
                    $html .= <<<HTML
                    <span class="px-4 py-2 bg-green-500/20 text-green-400 font-semibold rounded-lg 
                                 border border-green-500/30" 
                          aria-current="page">
                        {$page}
                    </span>
                    HTML;
                } else {
                    $html .= <<<HTML
                    <a href="{$url}" 
                       class="px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-lg 
                              transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400/50"
                       aria-label="Go to page {$page}">
                        {$page}
                    </a>
                    HTML;
                }
            }
        }

        // Next button
        $nextDisabled = $currentPage >= $totalPages;
        $html .= self::renderPageButton(
            $nextDisabled ? '#' : Helpers::buildQueryString($baseParams, ['page' => $currentPage + 1]),
            'Next',
            $nextDisabled,
            false
        );

        // Page info text
        $html .= <<<HTML
        <span class="ml-4 text-sm text-slate-400">
            Page {$currentPage} of {$totalPages}
        </span>
        HTML;

        $html .= '</nav>';

        return $html;
    }


    /**
     * Calculate visible page range
     */
    private static function getPageRange(int $currentPage, int $totalPages): array
    {
        $maxVisible = MAX_VISIBLE_PAGES;
        $pages = [];
        
        if ($totalPages <= $maxVisible + 2) {
            return range(1, $totalPages);
        }
        
        // Always show first page
        $pages[] = 1;
        
        $start = max(2, $currentPage - 1);
        $end = min($totalPages - 1, $currentPage + 1);
        
        // Adjust range to show exactly $maxVisible pages
        while (($end - $start + 1) < $maxVisible - 2 && ($start > 2 || $end < $totalPages - 1)) {
            if ($start > 2) $start--;
            if ($end < $totalPages - 1) $end++;
        }
        
        if ($start > 2) {
            $pages[] = '...';
        }
        
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }
        
        if ($end < $totalPages - 1) {
            $pages[] = '...';
        }
        
        // Always show last page
        $pages[] = $totalPages;
        
        return $pages;
    }
    
    /**
     * Render a page button (previous/next) with appropriate classes and accessibility attributes.
     */
    private static function renderPageButton(string $url, string $label, bool $disabled, bool $isPrevious): string
    {
        $disabledClasses = 'opacity-50 cursor-not-allowed pointer-events-none';
        $enabledClasses = 'hover:bg-slate-700/50 hover:text-white transition-all duration-200';
        
        $classes = $disabled ? $disabledClasses : $enabledClasses;
        $classes .= ' px-4 py-2 text-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400/50';
        
        $arrow = $isPrevious ? '←' : '→';
        $prefix = $isPrevious ? $arrow . ' ' : '';
        $suffix = !$isPrevious ? ' ' . $arrow : '';
        
        if ($disabled) {
            return <<<HTML
            <span class="{$classes}" aria-disabled="true">
                {$prefix}{$label}{$suffix}
            </span>
            HTML;
        }
        
        return <<<HTML
        <a href="{$url}" class="{$classes}" aria-label="{$label} page">
            {$prefix}{$label}{$suffix}
        </a>
        HTML;
    }
}
