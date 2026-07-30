<?php

namespace App\Components;

/**
 * Loading Spinner Component
 * 
 * Provides visual feedback during data fetching operations.
 * Uses CSS animation with Tailwind utility classes.
 */

class LoadingSpinner
{
    /**
     * Render loading skeleton for character cards
     * Shows placeholder cards that mimic the actual card layout
     */
    public static function renderSkeletonGrid(int $count = 8): string
    {
        $html = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">';
        
        for ($i = 0; $i < $count; $i++) {
            $html .= <<<HTML
            <div class="bg-slate-800/60 border border-slate-700/30 rounded-2xl overflow-hidden animate-pulse">
                <div class="aspect-square bg-slate-700/50"></div>
                <div class="p-4 space-y-3">
                    <div class="h-5 bg-slate-700/50 rounded-lg w-3/4"></div>
                    <div class="h-4 bg-slate-700/30 rounded-lg w-1/2"></div>
                    <div class="h-4 bg-slate-700/30 rounded-lg w-2/3"></div>
                </div>
            </div>
            HTML;
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Simple spinner for inline loading states
     */
    public static function renderSpinner(string $text = 'Loading...'): string
    {
        return <<<HTML
        <div class="flex flex-col items-center justify-center py-12 space-y-4" role="status">
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 border-4 border-slate-700 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-transparent border-t-green-400 rounded-full animate-spin"></div>
            </div>
            <p class="text-slate-400 text-lg font-medium">{$text}</p>
        </div>
        HTML;
    }
    
    /**
     * No results message
     */
    public static function renderNoResults(): string
    {
        return <<<HTML
        <div class="flex flex-col items-center justify-center py-16 text-center" role="status">
            <span class="text-6xl mb-6">😕</span>
            <h3 class="text-xl font-semibold text-white mb-2">No characters found</h3>
            <p class="text-slate-400 max-w-md">
                No characters match your search criteria. Try adjusting your filters or search with a different name.
            </p>
            <a href="/" class="mt-6 px-6 py-2.5 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 
                              font-medium rounded-xl transition-all duration-200">
                Clear all filters
            </a>
        </div>
        HTML;
    }
}