<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

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
        $diskName = $this->diskName();
        $name = Str::uuid().'.'.$file->extension();
        $path = $this->store($file, $directory, $name, $diskName);

        if ((! is_string($path) || $path === '') && $diskName !== 'public') {
            $diskName = 'public';
            $path = $this->store($file, $directory, $name, $diskName);
        }

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Cannot upload file to configured storage disk.');
        }

        $disk = Storage::disk($diskName);

        return [
            'path' => $path,
            'url' => $diskName === 'public' ? Storage::url($path) : $disk->url($path),
        ];
    }

    private function diskName(): string
    {
        $disk = config('filesystems.cloud', config('filesystems.default', 'public'));

        if ($disk !== 's3') {
            return $disk;
        }

        return filled(config('filesystems.disks.s3.bucket')) ? 's3' : 'public';
    }

    private function store(UploadedFile $file, string $directory, string $name, string $disk): string|false
    {
        try {
            return $file->storeAs($directory, $name, $disk);
        } catch (Throwable) {
            return false;
        }
    }
}
