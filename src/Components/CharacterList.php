<?php

namespace App\Components;

class CharacterList
{
    public static function render(array $characters, ?int $selectedId = null, array $starredIds = []): string
    {
        $starred = [];
        $regular = [];
        
        foreach ($characters as $c) {
            $id = (int)($c['id'] ?? 0);
            if (in_array($id, $starredIds)) {
                $starred[] = $c;
            } else {
                $regular[] = $c;
            }
        }
        
        $html = '<div class="flex-1 flex flex-col min-h-0 mt-10">';
        
        // Starred section
        if (!empty($starred)) {
            $html .= '<h2 class="px-10 text-xs font-semibold uppercase tracking-wider text-gray-500 flex-shrink-0">Starred Characters (' . count($starred) . ')</h2>';
            $html .= '<div class="mt-3 px-4 space-y-1 flex-shrink-0">';
            foreach ($starred as $character) {
                $id = (int)($character['id'] ?? 0);
                $isActive = ($id === $selectedId);
                $html .= CharacterListItem::render($character, $isActive, true);
            }
            $html .= '</div>';
        }
        
        // Characters section
        $html .= '<h2 class="px-10 text-xs font-semibold uppercase tracking-wider text-gray-500 flex-shrink-0 ' . (empty($starred) ? '' : 'mt-8') . '">Characters (' . count($regular) . ')</h2>';
        $html .= '<div class="mt-3 px-4 space-y-1 overflow-y-auto flex-1">';
        
        if (empty($regular) && empty($starred)) {
            $html .= '<p class="px-6 py-4 text-sm text-gray-400">No characters found</p>';
        } else {
            foreach ($regular as $character) {
                $id = (int)($character['id'] ?? 0);
                $isActive = ($id === $selectedId);
                $html .= CharacterListItem::render($character, $isActive, false);
            }
        }
        
        $html .= '</div></div>';
        
        return $html;
    }
}
