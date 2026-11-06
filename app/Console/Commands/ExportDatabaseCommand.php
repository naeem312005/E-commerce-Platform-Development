<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export-sql {file=ecommerce.sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export database schema and records to a standalone SQL file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filename = $this->argument('file');
        $filePath = base_path($filename);

        $tables = [
            'users',
            'categories',
            'subcategories',
            'products',
            'product_images',
            'product_variants',
            'migrations',
        ];

        $sql = "-- ============================================================\n";
        $sql .= "-- E-commerce Platform Database Export\n";
        $sql .= "-- Candidate: Md Naimul Hasan Naeem\n";
        $sql .= "-- Bangla Puzzle Limited - Mid-Level Laravel Developer Assignment\n";
        $sql .= '-- Generated: '.date('Y-m-d H:i:s')."\n";
        $sql .= "-- ============================================================\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
        $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $sql .= "SET time_zone = '+00:00';\n\n";

        foreach ($tables as $table) {
            $this->info("Exporting table: {$table}");

            // Drop table
            $sql .= "-- ------------------------------------------------------------\n";
            $sql .= "-- Table structure for `{$table}`\n";
            $sql .= "-- ------------------------------------------------------------\n";
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

            // Create table
            $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
            $sql .= $createTable[0]->{'Create Table'}.";\n\n";

            // Table rows
            $rows = DB::table($table)->get();
            if ($rows->count() > 0) {
                $sql .= "-- Dumping data for table `{$table}`\n";
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_map(fn ($col) => "`{$col}`", array_keys($rowArray));
                    $values = array_map(function ($val) {
                        if (is_null($val)) {
                            return 'NULL';
                        }

                        return "'".addslashes((string) $val)."'";
                    }, array_values($rowArray));

                    $sql .= "INSERT INTO `{$table}` (".implode(', ', $columns).') VALUES ('.implode(', ', $values).");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        File::put($filePath, $sql);
        File::put(base_path('database/'.$filename), $sql);

        $this->info("Database exported successfully to: {$filePath}");
        $this->info("Secondary copy created at: database/{$filename}");

        return self::SUCCESS;
    }
}
