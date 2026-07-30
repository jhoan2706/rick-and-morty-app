<?php

namespace App\Components;

use App\Utils\Helpers;

class CharacterListItem
{
    public static function render(array $character, bool $isActive = false, bool $isStarred = false): string
    {
        $name = Helpers::escape($character['name'] ?? 'Unknown');
        $species = Helpers::escape($character['species'] ?? 'Unknown');
        $image = Helpers::escape($character['image'] ?? '');
        $id = (int)($character['id'] ?? 0);
        $bg = $isActive ? 'bg-primary-100' : 'hover:bg-gray-50';
        
        $heartClass = $isStarred 
            ? 'text-secondary-600' 
            : 'text-gray-400';
        
        return '
        <a href="?id=' . $id . '" class="flex w-full items-center rounded-lg px-5 py-4 ' . $bg . ' transition-colors group">
            <img src="' . $image . '" alt="' . $name . '" class="h-8 w-8 rounded-full object-cover flex-shrink-0" loading="lazy" />
            <div class="ml-4 flex-1 text-left min-w-0">
                <p class="font-semibold text-gray-900 text-sm truncate">' . $name . '</p>
                <p class="text-gray-500 text-sm">' . $species . '</p>
            </div>
            <svg class="w-5 h-5 flex-shrink-0 ' . $heartClass . ' group-hover:text-secondary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </a>';
    }
}