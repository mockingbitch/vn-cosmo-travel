<?php

namespace App\ViewModels;

class SeoViewModel
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?string $canonical = null,
        public readonly ?string $image = null,
    ) {
    }
}

