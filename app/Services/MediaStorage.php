<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaStorage
{
    public function putEvidence(UploadedFile $file): array
    {
        return $this->put($file, 'evidence');
    }

    public function putProductImage(UploadedFile $file): array
    {
        return $this->put($file, 'products');
    }

    public function putNewsImage(UploadedFile $file): array
    {
        return $this->put($file, 'news');
    }

    private function put(UploadedFile $file, string $directory): array
    {
        $disk = Storage::disk(config('filesystems.cloud', 's3'));
        $name = Str::uuid().'.'.$file->extension();
        $path = $file->storeAs($directory, $name, config('filesystems.cloud', 's3'));

        return [
            'path' => $path,
            'url' => $disk->url($path),
        ];
    }
}
