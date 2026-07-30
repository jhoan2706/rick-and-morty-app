<?php

namespace App\Components;

use App\Utils\Helpers;

class SearchFilters
{
    private const STATUS_OPTIONS = ['alive', 'dead', 'unknown'];
    private const SPECIES_OPTIONS = ['Human', 'Alien', 'Humanoid', 'Poopybutthole', 'Mythological', 'Animal', 'Robot', 'Cronenberg', 'Disease', 'Unknown', 'Planet'];
    private const GENDER_OPTIONS = ['female', 'male', 'genderless', 'unknown'];

    private const STATUS_ICONS = [
        'alive' => '🟢',
        'dead' => '🔴',
        'unknown' => '⚪',
    ];

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
                        {self::renderStatusOptions($currentStatus)}
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
                        {self::renderOptions(self::SPECIES_OPTIONS, $currentSpecies)}
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
                        {self::renderOptions(self::GENDER_OPTIONS, $currentGender)}
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

    private static function selected(string $value, string $current): string
    {
        return $value === $current ? 'selected' : '';
    }

    private static function renderOptions(array $options, string $current): string
    {
        $html = '';
        foreach ($options as $option) {
            $selected = self::selected(strtolower($option), strtolower($current));
            $escaped = Helpers::escape($option);
            $html .= "<option value=\"{$escaped}\" {$selected}>{$escaped}</option>";
        }
        return $html;
    }

    private static function renderStatusOptions(string $current): string
    {
        $html = '';
        foreach (self::STATUS_OPTIONS as $status) {
            $selected = self::selected($status, $current);
            $icon = self::STATUS_ICONS[$status] ?? '';
            $html .= "<option value=\"{$status}\" {$selected}>{$icon} " . ucfirst($status) . "</option>";
        }
        return $html;
    }
}