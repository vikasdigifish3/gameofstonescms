<?php

namespace App\Console\Commands;

use App\Models\Content;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateMediaToImageKit extends Command
{
    protected $signature = 'media:migrate-to-imagekit';
    protected $description = 'Migrate media files from local storage to ImageKit.io';

    public function handle()
    {
        Log::info('Starting media migration to ImageKit.');


        // Fetch only entries with local storage paths for thumb and banner
        $mediaEntries = Content::where('thumb_url', 'like', '/storage%')
            ->orWhere('banner_url', 'like', '/storage%')
            ->get();
        Log::info("Total media entries to migrate: " . $mediaEntries->count());
        foreach ($mediaEntries as $media) {

            $fileTypesWithAttributes = [
                'thumb' => 'thumb_url',
                'banner' => 'banner_url',
//                'file' => 'file_path',
            ];

            foreach ($fileTypesWithAttributes as $type => $filePathAttribute) {
                $fileIDAttribute = $type . '_file_id';
                $originalFilePath = $media->{$filePathAttribute};
                if (!$originalFilePath) {
                    Log::info("Skipping {$type} for media ID {$media->id} as no path is set.");
                    continue;
                }

                // This assumes $originalFilePath is something like '/storage/games/action/zombie-buster/thumb.jpg'
                $filePath = Str::replaceFirst('/storage/', '', $originalFilePath); // Removes '/storage/'
                $fullPath = storage_path('app/public/' . $filePath); // Prepends the full server path

                if (!file_exists($fullPath)) {
                    Log::warning("File does not exist in public storage: {$fullPath} for media ID {$media->id}");
                    continue;
                }


                Log::info("Processing {$type} for media ID {$media->id}: {$originalFilePath} (adjusted to {$filePath})");

                $folderPath = $this->generateFolderPath($media);
                $file = new File($fullPath);
                $result = $this->uploadMediaToImageKit($file, $folderPath, $type);

                if ($result) {
                    $media->update([
                        $filePathAttribute => $result['url'],
                        $fileIDAttribute => $result['fileId'],
                    ]);

                    Log::info("Successfully migrated {$type} for media ID {$media->id} to {$result['url']}");
                } else {
                    Log::error("Failed to migrate {$type} for media ID {$media->id}");
                }


                sleep(10); // rate limiting
            }
        }

        Log::info('Media migration to ImageKit completed successfully.');
    }

    // Adjusted generateFolderPath logic for clarity
    protected function generateFolderPath($media)
    {
        $category = Category::find($media->category_id);
        $parentCategory = $category->parent()->first();

        $parentCategoryName = $parentCategory ? Str::slug($parentCategory->name) : 'default'; // Fallback to 'default' or a more suitable placeholder

        $categorySlug = Str::slug($category->name);
        $mediaSlug = Str::slug($media->name);

        $basePath = $parentCategoryName !== 'default' ? $parentCategoryName : 'mobilecafe/bollywood';

        return "/$basePath/$categorySlug/$mediaSlug";
    }

    protected function uploadMediaToImageKit($media, $folderPath, $fileType)
    {
        $client = new Client();
        $url = config('services.imagekit.upload_endpoint');
        $fileName = $fileType . '.' . time() . '.' . $media->getExtension();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(config('services.imagekit.private_key') . ':'),
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($media->getRealPath(), 'r'),
                        'filename' => $fileName,
                    ],
                    [
                        'name' => 'fileName',
                        'contents' => $fileName,
                    ],
                    [
                        'name' => 'folder',
                        'contents' => $folderPath,
                    ],
                ],

            ]);

            $body = json_decode($response->getBody(), true);

            return [
                'url' => $body['url'],
                'fileId' => $body['fileId'],
            ];
        } catch (GuzzleException $e) {
            Log::error("Failed to upload to ImageKit: " . $e->getMessage());
            return null;
        }
    }
}
