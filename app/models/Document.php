<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/app.php';

class Document
{
    private const DOWNLOADS_DIRECTORY = __DIR__ . '/../../assets/downloads';

    public function getAvailableDownloads(): array
    {
        if (!is_dir(self::DOWNLOADS_DIRECTORY)) {
            return [];
        }

        $files = glob(self::DOWNLOADS_DIRECTORY . '/*.pdf') ?: [];
        $documents = [];

        foreach ($files as $file) {
            $fileName = basename($file);
            $documentTitle = pathinfo($fileName, PATHINFO_FILENAME);
            $documentTitle = str_replace(['-', '_'], ' ', $documentTitle);

            $documents[] = [
                'title' => $documentTitle,
                'file_name' => $fileName,
                'url' => APP_URL . '/assets/downloads/' . rawurlencode($fileName),
            ];
        }

        usort(
            $documents,
            static fn (array $first, array $second): int =>
                strcasecmp($first['title'], $second['title'])
        );

        return $documents;
    }
}