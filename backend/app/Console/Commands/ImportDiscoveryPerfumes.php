<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Imports the Fragrantica `fra_cleaned.csv` export into `discovery_perfumes`.
 *
 * The CSV ships with three quirks this command normalises:
 *   - Windows-1252 encoding (accented brand names) -> UTF-8
 *   - `;` field delimiter, with notes/accords comma-separated *inside* a field
 *   - European decimals on the rating ("1,42")
 *
 * It also humanises the slug-style name/brand, coalesces the five mainaccord
 * columns into one array, drops "unknown" perfumers, and derives a stable
 * `source_id` from the URL so re-runs upsert instead of duplicating.
 */
class ImportDiscoveryPerfumes extends Command
{
    protected $signature = 'discovery:import
                            {path? : Path to the fra_cleaned.csv export}
                            {--fresh : Empty discovery_perfumes before importing}';

    protected $description = 'Import the Fragrantica fra_cleaned.csv export into discovery_perfumes';

    /** fra_cleaned.csv ships as Windows-1252; everything downstream expects UTF-8. */
    private const SOURCE_ENCODING = 'Windows-1252';

    /** Rows per upsert — keeps memory flat across the ~24k-row file. */
    private const CHUNK = 500;

    /** Column count of a well-formed fra_cleaned.csv row. */
    private const COLUMNS = 18;

    public function handle(): int
    {
        // fra_cleaned.csv is committed under database/data/fragrances/, so it is
        // baked into the backend image — the importer runs in-container with no args.
        $path = $this->argument('path') ?? database_path('data/fragrances/fra_cleaned.csv');

        if (! is_readable($path)) {
            $this->error("CSV not found or unreadable: {$path}");
            $this->line('Expected database/data/fragrances/fra_cleaned.csv — or pass an explicit path.');

            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error("Could not open: {$path}");

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            DB::table('discovery_perfumes')->delete();
            $this->warn('discovery_perfumes emptied (--fresh).');
        }

        // Header row is discarded — column order is pinned to the known layout below.
        if (fgetcsv($handle, null, ';', '"', '') === false) {
            $this->error('CSV appears to be empty.');
            fclose($handle);

            return self::FAILURE;
        }

        $now = now();
        $buffer = [];
        $read = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar();
        $bar->start();

        while (($row = fgetcsv($handle, null, ';', '"', '')) !== false) {
            // A line that doesn't split into the expected columns is malformed — skip it.
            if (count($row) < self::COLUMNS) {
                $skipped++;
                continue;
            }

            [$url, $perfume, $brand, $country, $gender, $ratingValue, $ratingCount,
             $year, $top, $middle, $base, $perfumer1, $perfumer2,
             $acc1, $acc2, $acc3, $acc4, $acc5] = array_map(
                fn ($value) => $this->utf8(trim((string) $value)),
                array_slice($row, 0, self::COLUMNS),
            );

            $sourceId = $this->fragranticaId($url);
            if ($sourceId === null) {
                $skipped++;
                continue;
            }

            $buffer[] = [
                'source_id'    => $sourceId,
                'source_url'   => $url,
                'name'         => $this->humanize($perfume),
                'brand'        => $this->humanize($brand),
                'country'      => $country !== '' ? $country : null,
                'gender'       => $gender !== '' ? $gender : null,
                'rating'       => $this->decimal($ratingValue),
                'votes'        => is_numeric($ratingCount) ? (int) $ratingCount : null,
                'release_year' => $this->year($year),
                'notes_top'    => $this->listJson($top),
                'notes_middle' => $this->listJson($middle),
                'notes_base'   => $this->listJson($base),
                'accords'      => $this->listJson(implode(',', [$acc1, $acc2, $acc3, $acc4, $acc5])),
                'perfumers'    => $this->perfumersJson([$perfumer1, $perfumer2]),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
            $read++;

            if (count($buffer) >= self::CHUNK) {
                $this->flush($buffer);
                $bar->advance(count($buffer));
                $buffer = [];
            }
        }

        if ($buffer !== []) {
            $this->flush($buffer);
            $bar->advance(count($buffer));
        }

        $bar->finish();
        $this->newLine(2);
        fclose($handle);

        $total = DB::table('discovery_perfumes')->count();
        $this->info("Imported/updated {$read} rows ({$skipped} skipped). discovery_perfumes now holds {$total}.");

        return self::SUCCESS;
    }

    /**
     * Upsert on source_id so re-running the importer updates rows in place
     * rather than duplicating them.
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function flush(array $rows): void
    {
        DB::table('discovery_perfumes')->upsert(
            $rows,
            ['source_id'],
            ['source_url', 'name', 'brand', 'country', 'gender', 'rating', 'votes',
             'release_year', 'notes_top', 'notes_middle', 'notes_base', 'accords',
             'perfumers', 'updated_at'],
        );
    }

    /** fra_cleaned.csv is Windows-1252; normalise every field to UTF-8. */
    private function utf8(string $value): string
    {
        return mb_convert_encoding($value, 'UTF-8', self::SOURCE_ENCODING);
    }

    /** ".../accento-overdose-pride-edition-74630.html" -> "74630". */
    private function fragranticaId(string $url): ?string
    {
        return preg_match('/-(\d+)\.html/', $url, $matches) ? $matches[1] : null;
    }

    /** Slug -> display text: "jean-paul-gaultier" -> "Jean Paul Gaultier". */
    private function humanize(string $slug): string
    {
        return ucwords(trim(str_replace(['-', '_'], ' ', $slug)));
    }

    /** European decimal ("1,42") -> 1.42; blanks / junk -> null. */
    private function decimal(string $value): ?float
    {
        $value = str_replace(',', '.', $value);

        return is_numeric($value) ? (float) $value : null;
    }

    /** Year as a 4-digit int, or null for blanks / junk. */
    private function year(string $value): ?int
    {
        return preg_match('/^\d{4}$/', $value) ? (int) $value : null;
    }

    /**
     * Comma-separated note/accord string -> JSON array of trimmed, de-duped
     * values. Empty input -> null, so the column stays NULL rather than "[]".
     */
    private function listJson(string $value): ?string
    {
        $items = collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter(fn ($item) => $item !== '')
            ->unique()
            ->values();

        return $items->isEmpty() ? null : $items->toJson();
    }

    /**
     * Perfumer1/2 -> JSON array, dropping blanks and the "unknown" sentinel
     * the export uses for missing perfumers.
     *
     * @param  array<int, string>  $names
     */
    private function perfumersJson(array $names): ?string
    {
        $items = collect($names)
            ->map(fn ($name) => trim($name))
            ->filter(fn ($name) => $name !== '' && strtolower($name) !== 'unknown')
            ->unique()
            ->values();

        return $items->isEmpty() ? null : $items->toJson();
    }
}
