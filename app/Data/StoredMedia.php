<?php

namespace App\Data;

readonly class StoredMedia
{
    public function __construct(
        public string $path,
        public string $url,
        public string $disk,
    ) {}

    public function toColumns(string $pathColumn, string $urlColumn): array
    {
        return [
            $pathColumn => $this->path,
            $urlColumn => $this->url,
        ];
    }
}
