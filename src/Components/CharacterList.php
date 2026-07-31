<?php

namespace App\Components;

class CharacterList
{
    public static function render(array $characters, ?int $selectedId = null, array $starredIds = []): string
    {
        $html = '<div class="flex-1 flex flex-col min-h-0 mt-10">';
        
        // STARRED section (filled by JS from localStorage)
        $html .= '<div id="starred-section" style="display:none">';
        $html .= '<h2 class="px-10 pt-6 text-xs font-semibold uppercase tracking-wider text-gray-500 flex-shrink-0">Starred Characters (<span id="starred-count">0</span>)</h2>';
        $html .= '<div id="starred-list" class="mt-3 px-4 space-y-1 flex-shrink-0"></div>';
        $html .= '</div>';
        
        // CHARACTERS section
        $html .= '<h2 class="px-10 pt-6 mt-6 text-xs font-semibold uppercase tracking-wider text-gray-500 flex-shrink-0">Characters (<span id="characters-count">' . count($characters) . '</span>)</h2>';
        $html .= '<div class="mt-3 px-4 space-y-1 overflow-y-auto flex-1" id="characters-list" data-page="1">';
        
        if (empty($characters)) {
            $html .= '<p class="px-6 py-4 text-sm text-gray-400">No characters found</p>';
        } else {
            foreach ($characters as $character) {
                $html .= CharacterListItem::render($character, false, false);
            }
        }
        
        $html .= '</div></div>';
        return $html;
    }
}