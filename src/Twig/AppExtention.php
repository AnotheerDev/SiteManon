<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Entity\User; 

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('username_or_deleted', [$this, 'getUsernameOrDeleted']),
        ];
    }

    public function getUsernameOrDeleted(?User $user): string
    {
        return $user ? $user->getNickname() : 'Utilisateur supprimé';
    }
}
