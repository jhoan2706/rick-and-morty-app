<?php

namespace App\Components;

class CharacterList
{
    public static function render(array $characters, ?int $selectedId = null, array $starredIds = [], array $filters = []): string
    {
        $totalResults = count($characters);
        
        // Calcular filtros activos
        $activeFilters = 0;
        if (!empty($filters['status'])) $activeFilters++;
        if (!empty($filters['species'])) $activeFilters++;
        if (!empty($filters['gender'])) $activeFilters++;
        
        $html = '<div class="flex-1 flex flex-col min-h-0 mt-10">';
        
        // === BOX DE SUMMARY ARRIBA DE STARRED ===
        $html .= '
        <div class="mx-4 mb-2 rounded-lg bg-white shadow-sm" style="padding: 1.5rem 2.5rem !important;">
            <div style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important;">
                <span class="text-sm font-semibold" style="flex-shrink: 0; color: #2563EB;">
                    <span id="total-characters-count">' . $totalResults . '</span> Results
                </span>
                <span id="active-filters-badge" style="display: flex; flex-direction: row; justify-content: center; align-items: center; padding: 2px 12px; width: auto; height: 24px; background: rgba(99, 216, 56, 0.2); border-radius: 12px; flex: none; order: 1; flex-grow: 0; color: #3B8520 !important; font-weight: 600 !important; font-size: 13px; font-family: sans-serif;">
                        ' . $activeFilters . ' ' . ($activeFilters === 1 ? 'Filter' : 'Filters') . '
                </span>
            </div>
        </div>';
        
        // === STARRED SECTION ===
        $html .= '<div id="starred-section" style="display:none">';
        $html .= '<h2 class="px-10 pt-6 text-xs font-semibold uppercase tracking-wider text-gray-500 flex-shrink-0">Starred Characters (<span id="starred-count">0</span>)</h2>';
        $html .= '<div id="starred-list" class="mt-3 px-4 space-y-1 flex-shrink-0"></div>';
        $html .= '</div>';
        
        // === CHARACTERS SECTION ===
        $html .= '<h2 class="px-10 pt-6 mt-6 text-xs font-semibold uppercase tracking-wider text-gray-500 flex-shrink-0">Characters (<span id="characters-count">' . count($characters) . '</span>)</h2>';
        $html .= '<div class="mt-3 px-4 space-y-1 overflow-y-auto flex-1" id="characters-list" data-page="1">';
        
        if (empty($characters)) {
            $html .= '<p class="px-6 py-4 text-sm text-gray-400">No characters found</p>';
        } else {
            foreach ($characters as $character) {
                $charId = (int)($character['id'] ?? 0);
                $isActive = ($selectedId && $charId === $selectedId);
                $isStarred = in_array($charId, $starredIds);
                $html .= CharacterListItem::render($character, $isActive, $isStarred);
            }
        }
        
        $html .= '</div></div>';
        return $html;
    }
}