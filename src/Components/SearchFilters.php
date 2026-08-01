<?php

namespace App\Components;

use App\Utils\Helpers;

class SearchFilters
{
    private const CHARACTER_OPTIONS = ['All', 'Starred', 'Others'];
    private const SPECIES_OPTIONS = ['All', 'Human', 'Alien'];
    private const STATUS_OPTIONS = ['All', 'Alive', 'Dead', 'unknown'];
    private const GENDER_OPTIONS = ['All', 'Male', 'Female', 'Genderless', 'unknown'];

    public static function render(array $currentFilters = []): string
    {
        $currentCharacter = $currentFilters['character'] ?? 'All';
        $currentSpecies = $currentFilters['species'] ?? 'All';
        $currentStatus = $currentFilters['status'] ?? 'All';
        $currentGender = $currentFilters['gender'] ?? 'All';

        $currentCharacter = htmlspecialchars($currentCharacter);
        $currentSpecies = htmlspecialchars($currentSpecies);
        $currentStatus = htmlspecialchars($currentStatus);
        $currentGender = htmlspecialchars($currentGender);

        $html = '
        <div id="filters-popup" class="hidden absolute left-0 top-[60px] z-50 w-full rounded-xl border border-[#ECECEC] bg-white p-6 shadow-[0_10px_30px_rgba(0,0,0,0.08)]">
        <!-- Character -->
        <div class="mb-5">
            <h3 class="mb-3 text-base font-semibold text-gray-700">Character</h3>
            <div class="flex gap-2">';

        foreach (self::CHARACTER_OPTIONS as $option) {
            $isActive = ($option === $currentCharacter);
            $activeClass = $isActive
                ? 'border-[#6B46C1] bg-[#EEE3FF] text-[#6B46C1]'
                : 'border-[#E8E8E8] bg-white text-[#1E1E1E]';

            $html .= '
                    <button 
                        type="button" 
                        data-filter="characterType" 
                        data-value="' . $option . '"
                        class="filter-option flex h-11 flex-1 items-center justify-center rounded-xl border text-sm font-semibold transition hover:bg-gray-50 ' . $activeClass . '"
                    >' . $option . '</button>';
        }

        $html .= '
                </div>
            </div>
            
            <!-- Species -->
            <div class="mb-5">
                <h3 class="mb-3 text-base font-semibold text-gray-700">Species</h3>
                <div class="flex gap-2">';

        foreach (self::SPECIES_OPTIONS as $option) {
            $isActive = ($option === $currentSpecies);
            $activeClass = $isActive
                ? 'border-[#6B46C1] bg-[#EEE3FF] text-[#6B46C1]'
                : 'border-[#E8E8E8] bg-white text-[#1E1E1E]';

            $html .= '
                    <button 
                        type="button" 
                        data-filter="species" 
                        data-value="' . $option . '"
                        class="filter-option flex h-11 flex-1 items-center justify-center rounded-xl border text-sm font-semibold transition hover:bg-gray-50 ' . $activeClass . '"
                    >' . $option . '</button>';
        }

        $html .= '
                </div>
            </div>

            <!-- Status -->
            <div class="mb-5">
                <h3 class="mb-3 text-base font-semibold text-gray-700">Status</h3>
                <div class="flex gap-2">';

        foreach (self::STATUS_OPTIONS as $option) {
            $isActive = ($option === $currentStatus);
            $activeClass = $isActive
                ? 'border-[#6B46C1] bg-[#EEE3FF] text-[#6B46C1]'
                : 'border-[#E8E8E8] bg-white text-[#1E1E1E]';

            $html .= '
                    <button 
                        type="button" 
                        data-filter="status" 
                        data-value="' . $option . '"
                        class="filter-option flex h-11 flex-1 items-center justify-center rounded-xl border text-sm font-semibold transition hover:bg-gray-50 ' . $activeClass . '"
                    >' . $option . '</button>';
        }

        $html .= '
                </div>
            </div>

            <!-- Gender -->
            <div>
                <h3 class="mb-3 text-base font-semibold text-gray-700">Gender</h3>
                <div class="flex gap-2">';

        foreach (self::GENDER_OPTIONS as $option) {
            $isActive = ($option === $currentGender);
            $activeClass = $isActive
                ? 'border-[#6B46C1] bg-[#EEE3FF] text-[#6B46C1]'
                : 'border-[#E8E8E8] bg-white text-[#1E1E1E]';

            $html .= '
                    <button 
                        type="button" 
                        data-filter="gender" 
                        data-value="' . $option . '"
                        class="filter-option flex h-11 flex-1 items-center justify-center rounded-xl border text-sm font-semibold transition hover:bg-gray-50 ' . $activeClass . '"
                    >' . $option . '</button>';
        }

        $html .= '
                </div>
            </div>
            
            <!-- Filter Button -->
            <button 
                type="button" 
                id="apply-filters"
                disabled
                class="mt-5 h-11 w-full rounded-xl bg-[#6B46C1] text-sm font-medium text-white opacity-50 transition cursor-not-allowed"
            >
                Filter
            </button>
        </div>';

        return $html;
    }
}