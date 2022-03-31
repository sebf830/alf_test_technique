<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class displayPluralFilter extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluriel', [$this, 'pluriel']),
        ];
    }

    public function pluriel(int $count, string $singulier, ?string $pluriel = null)
    {
        $pluriel ??= $singulier . 's';
        $str = $count <= 1 ? $singulier : $pluriel;
        return "$count $str";
    }
}
