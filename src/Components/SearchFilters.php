<?php

namespace App\Components;

use App\Utils\Helpers;

class SearchFilters
{
    private const CHARACTER_OPTIONS = ['All', 'Starred', 'Others'];
    private const SPECIES_OPTIONS = ['All', 'Human', 'Alien', 'Humanoid', 'Poopybutthole', 'Mythological', 'Animal', 'Robot', 'Cronenberg', 'Disease', 'Unknown', 'Planet'];
    
    public static function render(array $currentFilters = []): string
    {
        $currentCharacter = $currentFilters['character'] ?? 'All';
        $currentSpecies = $currentFilters['species'] ?? 'All';
        
        $currentCharacter = htmlspecialchars($currentCharacter);
        $currentSpecies = htmlspecialchars($currentSpecies);
        
        $html = '
        <div id="filters-popup" class="hidden absolute left-0 top-[60px] z-50 w-full rounded-xl border border-[#ECECEC] bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.08)]">
            <!-- Character -->
            <div class="mb-5">
                <h3 class="mb-3 text-base font-semibold text-gray-700">Character</h3>
                <div class="flex gap-2">';
        
        foreach (self::CHARACTER_OPTIONS as $option) {
            $activeClass = ($option === $currentCharacter) 
                ? 'border-[#6B46C1] bg-[#EEE3FF] text-[#6B46C1]' 
                : 'border-[#E8E8E8] bg-white text-[#1E1E1E]';
            
            $html .= '
                    <button 
                        type="button" 
                        data-filter="character" 
                        data-value="' . $option . '"
                        class="flex h-11 flex-1 items-center justify-center rounded-xl border text-sm font-semibold transition hover:bg-gray-50 ' . $activeClass . '"
                    >' . $option . '</button>';
        }
        
        $html .= '
                </div>
            </div>
            
            <!-- Specie -->
            <div>
                <h3 class="mb-3 text-base font-semibold text-gray-700">Specie</h3>
                <div class="flex flex-wrap gap-2">';
        
        foreach (self::SPECIES_OPTIONS as $option) {
            $activeClass = ($option === $currentSpecies) 
                ? 'border-[#6B46C1] bg-[#EEE3FF] text-[#6B46C1]' 
                : 'border-[#E8E8E8] bg-white text-[#1E1E1E]';
            
            $html .= '
                    <button 
                        type="button" 
                        data-filter="species" 
                        data-value="' . $option . '"
                        class="flex h-11 flex-1 min-w-[75px] items-center justify-center rounded-xl border text-sm font-semibold transition hover:bg-gray-50 ' . $activeClass . '"
                    >' . $option . '</button>';
        }
        
        $html .= '
                </div>
            </div>
            
            <!-- Filter Button -->
            <button 
                type="button" 
                id="apply-filters"
                class="mt-5 h-11 w-full rounded-xl bg-[#6B46C1] text-sm font-medium text-white transition hover:bg-[#5B38B0]"
            >
                Filter
            </button>
        </div>';
        
        return $html;
    }
}