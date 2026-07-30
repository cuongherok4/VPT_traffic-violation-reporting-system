<?php

namespace App\Services;

use App\Data\StoredMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaStorage
{
    public function putEvidence(UploadedFile $file): StoredMedia
    {
        return $this->put($file, 'evidence');
    }

    public function putProductImage(UploadedFile $file): StoredMedia
    {
        return $this->put($file, 'products');
    }

    public function putNewsImage(UploadedFile $file): StoredMedia
    {
        return $this->put($file, 'news');
    }

    public function delete(?StoredMedia $media): void
    {
        if ($media) {
            Storage::disk($media->disk)->delete($media->path);
        }
    }

    private function put(UploadedFile $file, string $directory): StoredMedia
    {
        $diskName = config('filesystems.cloud', 's3');
        $disk = Storage::disk($diskName);
        $name = Str::uuid().'.'.$file->extension();
        $path = $file->storeAs($directory, $name, $diskName);

        return new StoredMedia($path, $disk->url($path), $diskName);
    }
}
