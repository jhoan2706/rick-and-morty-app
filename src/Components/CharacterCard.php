<?php

namespace App\Services;

use App\Services\Helpers;

/** 
 * Character Card Component
 * 
 * This class is responsible for rendering a character card component in HTML. It takes character data as input and outputs a styled card using TailwindCSS classes. The card displays the character's image, name, species, status, and location.
 */

class CharacterCard
{

    /**
     * Render a character card
     * 
     * @param array $character The character data to render
     * @return string The HTML of the character card
     */
    public static function render(array $character): string
    {
        $status = $character['status'] ?? 'unknown';
        $species = Helpers::escape($character['species'] ?? 'Unknown');
        $name = Helpers::escape($character['name'] ?? 'Unknown');
        $location = Helpers::escape($character['location']['name'] ?? 'Unknown');
        $image = Helpers::escape($character['image'] ?? '');

        $statusClasses = Helpers::getStatusClasses($status);
        $statusDotColor = Helpers::getStatusDotColor($status);

        // Building the card with TailwindCSS classes matching Figma design
        return <<<HTML
        <article class="group relative bg-slate-800/80 backdrop-blur-sm rounded-2xl overflow-hidden 
                        border border-slate-700/50 hover:border-green-400/30 
                        transition-all duration-300 hover:shadow-lg hover:shadow-green-400/10 
                        hover:-translate-y-1 flex flex-col" 
                 role="article" 
                 aria-label="Character card for {$name}">
            
            <div class="relative overflow-hidden aspect-square">
                <img 
                    src="{$image}" 
                    alt="{$name}" 
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    loading="lazy"
                />
                
                <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-medium 
                            border backdrop-blur-md {$statusClasses}">
                    <span class="inline-block w-1.5 h-1.5 rounded-full {$statusDotColor} mr-1.5 animate-pulse"></span>
                    {$status}
                </div>
            </div>
            
            <div class="p-4 flex flex-col flex-grow">
                <h3 class="text-lg font-bold text-white mb-2 truncate group-hover:text-green-400 
                           transition-colors duration-200" 
                    title="{$name}">
                    {$name}
                </h3>
                
                <div class="space-y-2 mt-auto">
                    <div class="flex items-center text-sm text-slate-300">
                        <span class="mr-2 text-green-400">♥</span>
                        <span class="truncate">{$species}</span>
                    </div>
                    
                    <div class="flex items-start text-sm text-slate-400">
                        <span class="mr-2 mt-0.5 text-blue-400">⌂</span>
                        <span class="line-clamp-2" title="{$location}">{$location}</span>
                    </div>
                </div>
            </div>
        </article>
        HTML;
    }
}
