<?php

namespace App\Components;

use App\Utils\Helpers;

class SearchBar
{
    public static function render(array $filters = []): string
    {
        $name = Helpers::escape($filters['name'] ?? '');
        
        return '
        <div class="mt-8">
            <form method="GET" action="" id="search-form">
                <div class="flex h-[52px] items-center rounded-lg bg-gray-100 px-5">
                    <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        name="name" 
                        value="' . $name . '" 
                        placeholder="Search or filter results" 
                        class="flex-1 bg-transparent px-3 outline-none text-sm text-gray-900 placeholder-gray-500"
                        aria-label="Search characters"
                    />
                </div>
            </form>
        </div>';
    }
}
