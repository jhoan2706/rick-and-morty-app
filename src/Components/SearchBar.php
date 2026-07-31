<?php

namespace App\Components;

use App\Utils\Helpers;

class SearchBar
{
    public static function render(array $filters = []): string
    {
        $name = Helpers::escape($filters['name'] ?? '');
        
        return '
        <div class="mt-8 relative">
            <form method="GET" action="" id="search-form" class="relative">
                <div class="flex h-[52px] items-center rounded-lg bg-gray-100 px-5">
                    <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    
                    <input 
                        type="text" 
                        name="name" 
                        id="search-input"
                        value="' . $name . '" 
                        placeholder="Search or filter results" 
                        class="flex-1 bg-transparent px-3 outline-none text-sm text-gray-900 placeholder-gray-500 cursor-pointer"
                        aria-label="Search characters"
                        autocomplete="off"
                        readonly
                    />
                    
                    <button 
                        type="button" 
                        id="filters-toggle"
                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-[#EEE3FF] text-[#6B46C1] hover:bg-[#DDD0F5] transition-colors duration-200"
                        aria-label="Open filters"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </button>
                </div>
                
                <input type="hidden" name="status" value="' . Helpers::escape($filters['status'] ?? '') . '" id="filter-status-hidden">
                <input type="hidden" name="species" value="' . Helpers::escape($filters['species'] ?? '') . '" id="filter-species-hidden">
                <input type="hidden" name="gender" value="' . Helpers::escape($filters['gender'] ?? '') . '" id="filter-gender-hidden">
                
                <!-- POPOVER INSIDE THE SEARCH FORM -->
                ' . SearchFilters::render($filters) . '
            </form>
        </div>';
    }
}