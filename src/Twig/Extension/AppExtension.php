<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AppExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('smart_strip_tags', [$this, 'smartStripTags']),
        ];
    }

    public function smartStripTags($text)
    {
        // Remplacer les fermetures de paragraphes et autres blocs par des espaces
        $text = str_replace(['</p>', '</h1>'], '. ', $text);
        // Retirer toutes les autres balises HTML
        return strip_tags($text);
    }
}
