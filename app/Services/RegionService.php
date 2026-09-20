<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RegionService
{
    private const CONFIG = [
        'provinces' => ['data-provinsi.csv', null, 1],
        'districts' => ['data-kabupaten.csv', 1, 2],
        'subdistricts' => ['data-kecamatan.csv', 1, 3],
        'villages' => ['data-kelurahan.csv', 1, 4],
    ];

    public function get(string $level, ?string $parent = null): array
    {
        abort_unless(isset(self::CONFIG[$level]), 404);
        $cacheKey = 'regions.' . $level . '.' . ($parent ?: 'root');

        return Cache::remember($cacheKey, now()->addDay(), function () use ($level, $parent) {
            [$file, $parentIndex, $nameIndex] = self::CONFIG[$level];
            $handle = fopen(database_path($file), 'r');
            if ($handle === false) return [];
            fgetcsv($handle);
            $rows = [];
            $parents = $parent ? explode('|', $parent) : [];

            while (($row = fgetcsv($handle)) !== false) {
                if ($level === 'provinces' && !in_array($row[0], ['33', '35'], true)) continue;
                if ($parents && $parentIndex !== null) {
                    foreach ($parents as $offset => $value) {
                        if (($row[$parentIndex + $offset] ?? null) !== $value) continue 2;
                    }
                }
                $rows[] = ['code' => $row[0], 'name' => $row[$nameIndex]];
            }
            fclose($handle);
            return $rows;
        });
    }
}
