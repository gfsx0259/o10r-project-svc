<?php

declare(strict_types=1);

namespace App\Service;

final class ProjectCodeGenerator
{
    private array $adjectives = [
        "focused", "brave", "frosty", "hungry", "furious", "shy", "angry", "eager", "silly", "dreamy",
        "naughty", "hopeful", "clever", "happy", "grumpy", "bold", "crazy", "sad", "quirky", "zen"
    ];

    private array $nouns = [
        "archer", "panda", "wizard", "rider", "ninja", "tiger", "bearer", "monk", "pirate", "samurai",
        "cat", "dog", "robot", "ghost", "engine", "warrior", "explorer", "traveler", "beast", "ranger"
    ];

    public function getName(): string
    {
        $adjective = $this->adjectives[array_rand($this->adjectives)];
        $noun = $this->nouns[array_rand($this->nouns)];
        return $adjective . '-' . $noun;
    }
}
