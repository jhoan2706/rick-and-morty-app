<?php

namespace App\Services;

use App\Services\Helpers;

/**
 * Search Filters Component
 * 
 * Renders the search bar and filter dropdowns for the Rick and Morty character search. It allows users to filter characters by name, status, species, and gender. The component is designed to be responsive and accessible, with proper ARIA labels and keyboard navigation support.
 * 
 * Filters supported: name, status, species, gender
 */

class SearchFilters
{
    private const STATUS_OPTIONS = ['', 'alive', 'dead', 'unknown'];
    private const SPECIES_OPTIONS = ['', 'Human', 'Alien', 'Humanoid', 'Poopybutthole', 'Mythological', 'Animal', 'Robot', 'Cronenberg', 'Disease', 'Unknown', 'Planet'];
    private const GENDER_OPTIONS = ['', 'female', 'male', 'genderless', ' unknown'];


    /**
     * Render search filters form with current filter values.
     * 
     * @param array $currentFilters The current filter values (name
     * status, species, gender) to pre-fill the form inputs.
     * @return string The HTML of the search filters form.
     */
    public static function render(array $currentFilters = []): string
    {
        $name = Helpers::escape($currentFilters['name'] ?? '');
        $currentStatus = $currentFilters['status'] ?? '';
        $currentSpecies = $currentFilters['species'] ?? '';
        $currentGender = $currentFilters['gender'] ?? '';

        return <<<HTML
        <form method="GET" action="" class="w-full space-y-4" id="search-form">
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">🔍</span>
                <input 
                    type="text" 
                    name="name" 
                    value="{$name}" 
                    placeholder="Search characters by name..." 
                    class="w-full pl-12 pr-4 py-3 bg-slate-800/60 border border-slate-700/50 rounded-xl 
                           text-white placeholder-slate-400 focus:outline-none focus:ring-2 
                           focus:ring-green-400/50 focus:border-green-400/30 transition-all duration-200"
                    aria-label="Search characters by name"
                />
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-slate-400 mb-1.5">
                        Status
                    </label>
                    <select 
                        id="status-filter"
                        name="status" 
                        class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700/50 rounded-xl 
                               text-white cursor-pointer focus:outline-none focus:ring-2 
                               focus:ring-green-400/50 focus:border-green-400/30 transition-all duration-200"
                        aria-label="Filter by status"
                    >
                        <option value="">All Statuses</option>
                        <option value="alive" {$this->selected('alive',$currentStatus)}>🟢 Alive</option>
                        <option value="dead" {$this->selected('dead',$currentStatus)}>🔴 Dead</option>
                        <option value="unknown" {$this->selected('unknown',$currentStatus)}>⚪ Unknown</option>
                    </select>
                </div>
                
                <div>
                    <label for="species-filter" class="block text-sm font-medium text-slate-400 mb-1.5">
                        Species
                    </label>
                    <select 
                        id="species-filter"
                        name="species" 
                        class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700/50 rounded-xl 
                               text-white cursor-pointer focus:outline-none focus:ring-2 
                               focus:ring-green-400/50 focus:border-green-400/30 transition-all duration-200"
                        aria-label="Filter by species"
                    >
                        <option value="">All Species</option>
                        {$this->renderOptions(self::SPECIES_OPTIONS,$currentSpecies)}
                    </select>
                </div>
                
                <div class="sm:col-span-2 lg:col-span-1">
                    <label for="gender-filter" class="block text-sm font-medium text-slate-400 mb-1.5">
                        Gender
                    </label>
                    <select 
                        id="gender-filter"
                        name="gender" 
                        class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700/50 rounded-xl 
                               text-white cursor-pointer focus:outline-none focus:ring-2 
                               focus:ring-green-400/50 focus:border-green-400/30 transition-all duration-200"
                        aria-label="Filter by gender"
                    >
                        <option value="">All Genders</option>
                        <option value="female" {$this->selected('female',$currentGender)}>Female</option>
                        <option value="male" {$this->selected('male',$currentGender)}>Male</option>
                        <option value="genderless" {$this->selected('genderless',$currentGender)}>Genderless</option>
                        <option value="unknown" {$this->selected('unknown',$currentGender)}>Unknown</option>
                    </select>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-green-500/90 hover:bg-green-400 text-slate-900 font-semibold 
                           rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-green-400/20 
                           focus:outline-none focus:ring-2 focus:ring-green-400/50"
                >
                    Apply Filters
                </button>
                <a 
                    href="/" 
                    class="px-6 py-2.5 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 font-medium 
                           rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 
                           focus:ring-slate-400/50 inline-flex items-center"
                >
                    Clear Filters
                </a>
            </div>
        </form>
        HTML;
    }

    /**
     * Generate selected attribute for option elements based on current filter values.
     */
    private function selected(string $value, string $current): string
    {
        return $value === $current ? 'selected' : '';
    }

    /**
     * Render option elements for select inputs based on provided options and current filter values.
     */
    private function renderOptions(array $options, string $current): string
    {
        $html = '';
        foreach ($options as $option) {
            if ($option === '') continue; // Skip empty option, already handled in the select
            $selected = $this->selected(strtolower($option), strtolower($current));
            $escapedOption = Helpers::escape($option);
            $html .= "<option value=\"{$escapedOption}\" {$selected}>{$escapedOption}</option>";
        }
        return $html;
    }

}
