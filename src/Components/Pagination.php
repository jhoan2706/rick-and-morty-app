<?php

namespace App\Components;

use App\Utils\Helpers;

class Pagination
{
    public static function render(array $info, int $currentPage, array $filters = []): string
    {
        $totalPages = $info['pages'] ?? 1;

        if ($totalPages <= 1) {
            return ''; // No pagination needed if there's only one page
        }

        $pages = self::getPageRange($currentPage, $totalPages);
        $baseParams = array_filter($filters, fn($key) => $key !== 'page', ARRAY_FILTER_USE_KEY);

        // Changed: flex-wrap + gap-2 for responsive wrapping, hidden md:flex for mobile pagination
        $html = '<nav class="flex flex-col items-center gap-3 mt-6" aria-label="Pagination">';
        
        // Page buttons row - wraps on small screens
        $html .= '<div class="flex flex-wrap justify-center items-center gap-2">';

        $prevDisabled = $currentPage <= 1;
        $html .= self::renderPageButton(
            $prevDisabled ? '#' : Helpers::buildQueryString($baseParams, ['page' => $currentPage - 1]),
            'Prev',
            $prevDisabled,
            true
        );

        // Page numbers
        foreach ($pages as $page) {
            if ($page === '...') {
                $html .= '<span class="px-2 py-1 text-slate-500 text-sm">...</span>';
            } else {
                $isActive = $page === $currentPage;
                $url = Helpers::buildQueryString($baseParams, ['page' => $page]);

                if ($isActive) {
                    $html .= <<<HTML
                    <span class="px-3 py-1.5 bg-green-500/20 text-green-400 font-semibold rounded-lg 
                                 border border-green-500/30 text-sm" 
                          aria-current="page">
                        {$page}
                    </span>
                    HTML;
                } else {
                    $html .= <<<HTML
                    <a href="{$url}" 
                       class="px-3 py-1.5 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-lg 
                              transition-all duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-green-400/50"
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

        $html .= '</div>';

        // Page info - always below buttons
        $html .= <<<HTML
        <span class="text-sm text-slate-400">
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
        $classes .= ' px-3 py-1.5 text-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-400/50';
        
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
