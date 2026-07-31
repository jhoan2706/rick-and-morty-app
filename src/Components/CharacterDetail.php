<?php

namespace App\Components;

use App\Utils\Helpers;

class CharacterDetail
{
    public static function render(?array $character = null, bool $isMobile = false): string
    {
        if (!$character) {
            return '
            <div class="flex-1 bg-white flex items-center justify-center h-full w-full">
                <div class="text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    <p class="text-gray-400 text-lg">Select a character to see details</p>
                </div>
            </div>';
        }
        
        $name = Helpers::escape($character['name'] ?? 'Unknown');
        $species = Helpers::escape($character['species'] ?? 'Unknown');
        $status = Helpers::escape($character['status'] ?? 'Unknown');
        $gender = Helpers::escape($character['gender'] ?? 'Unknown');
        $origin = Helpers::escape($character['origin']['name'] ?? 'Unknown');
        $location = Helpers::escape($character['location']['name'] ?? 'Unknown');
        $image = Helpers::escape($character['image'] ?? '');
        $id = (int)($character['id'] ?? 0);
        
        $statusColors = [
            'Alive' => 'text-secondary-600 bg-green-50',
            'Dead' => 'text-red-600 bg-red-50',
            'unknown' => 'text-gray-600 bg-gray-100',
        ];
        $statusColor = $statusColors[$status] ?? 'text-gray-600 bg-gray-100';
        
        $html = '<main class="flex-1 bg-white overflow-y-auto h-full">';

        if ($isMobile) {
            $html .= '<div class="pt-10" style="padding-left: 30px !important;">';
        } else {
            $html .= '<div class="pt-10" style="padding-left: 100px !important;">';
        }
        
        $html .= '<div class="flex items-end">';
        $html .= '<img src="' . $image . '" alt="' . $name . '" class="h-[75px] w-[75px] rounded-full object-cover" />';
        $html .= '<button class="ml-[-18px] w-8 h-8 rounded-full bg-white shadow flex items-center justify-center hover:shadow-md transition-shadow favorite-btn" data-id="' . $id . '" aria-label="Toggle favorite">';
        $html .= '<svg class="w-4 h-4 heart-icon text-gray-400 hover:text-secondary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>';
        $html .= '</svg></button>';
        $html .= '</div>';
        
        $html .= '<h2 class="mt-2 text-[38px] font-bold text-gray-900">' . $name . '</h2>';
        
        $html .= '<div class="mt-12 space-y-8">';
        
        $html .= '<div class="border-b border-gray-200 pb-6">';
        $html .= '<p class="font-semibold text-gray-900">Specie</p>';
        $html .= '<p class="text-gray-500 mt-1">' . $species . '</p>';
        $html .= '</div>';
        
        $html .= '<div class="border-b border-gray-200 pb-6">';
        $html .= '<p class="font-semibold text-gray-900">Status</p>';
        $html .= '<p class="text-gray-500 mt-1"><span class="inline-block px-3 py-1 rounded-full text-xs font-medium ' . $statusColor . '">' . $status . '</span></p>';
        $html .= '</div>';
        
        $html .= '<div class="border-b border-gray-200 pb-6">';
        $html .= '<p class="font-semibold text-gray-900">Gender</p>';
        $html .= '<p class="text-gray-500 mt-1">' . $gender . '</p>';
        $html .= '</div>';
        
        $html .= '<div class="border-b border-gray-200 pb-6">';
        $html .= '<p class="font-semibold text-gray-900">Origin</p>';
        $html .= '<p class="text-gray-500 mt-1">' . $origin . '</p>';
        $html .= '</div>';
        
        $html .= '<div class="pb-6">';
        $html .= '<p class="font-semibold text-gray-900">Last known location</p>';
        $html .= '<p class="text-gray-500 mt-1">' . $location . '</p>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</main>';
        
        return $html;
    }
}