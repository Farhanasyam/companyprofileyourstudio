<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database performance by adding indexes and analyzing tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database optimization...');
        
        // Add indexes for better performance
        $this->addIndexes();
        
        // Analyze tables for better query planning
        $this->analyzeTables();
        
        // Optimize tables
        $this->optimizeTables();
        
        $this->info('Database optimization completed successfully!');
    }
    
    /**
     * Add database indexes
     */
    private function addIndexes()
    {
        $this->info('Adding database indexes...');
        
        $indexes = [
            'events' => [
                ['is_active', 'start_date'],
                ['is_featured', 'is_active'],
                ['start_date', 'end_date']
            ],
            'products' => [
                ['is_active', 'is_featured'],
                ['category_id', 'is_active'],
                ['sort_order', 'is_active']
            ],
            'articles' => [
                ['status', 'published_at'],
                ['is_featured', 'status'],
                ['published_at', 'status']
            ],
            'categories' => [
                ['is_active', 'sort_order']
            ]
        ];
        
        foreach ($indexes as $table => $tableIndexes) {
            foreach ($tableIndexes as $index) {
                $indexName = $table . '_' . implode('_', $index) . '_index';
                
                try {
                    DB::statement("CREATE INDEX IF NOT EXISTS {$indexName} ON {$table} (" . implode(', ', $index) . ")");
                    $this->line("✓ Added index: {$indexName}");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to add index {$indexName}: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Analyze tables for better query planning
     */
    private function analyzeTables()
    {
        $this->info('Analyzing tables...');
        
        $tables = ['events', 'products', 'articles', 'categories', 'galleries', 'settings'];
        
        foreach ($tables as $table) {
            try {
                DB::statement("ANALYZE TABLE {$table}");
                $this->line("✓ Analyzed table: {$table}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to analyze table {$table}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Optimize tables
     */
    private function optimizeTables()
    {
        $this->info('Optimizing tables...');
        
        $tables = ['events', 'products', 'articles', 'categories', 'galleries', 'settings'];
        
        foreach ($tables as $table) {
            try {
                DB::statement("OPTIMIZE TABLE {$table}");
                $this->line("✓ Optimized table: {$table}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to optimize table {$table}: " . $e->getMessage());
            }
        }
    }
}
