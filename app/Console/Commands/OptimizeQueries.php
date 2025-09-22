<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeQueries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queries:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database queries for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting query optimization...');
        
        // Analyze slow queries
        $this->analyzeSlowQueries();
        
        // Optimize table structures
        $this->optimizeTableStructures();
        
        // Add missing indexes
        $this->addMissingIndexes();
        
        // Optimize query patterns
        $this->optimizeQueryPatterns();
        
        $this->info('Query optimization completed successfully!');
    }
    
    /**
     * Analyze slow queries
     */
    private function analyzeSlowQueries()
    {
        $this->info('Analyzing slow queries...');
        
        // Enable slow query log
        DB::statement("SET GLOBAL slow_query_log = 'ON'");
        DB::statement("SET GLOBAL long_query_time = 1");
        
        $this->line('✓ Slow query logging enabled');
    }
    
    /**
     * Optimize table structures
     */
    private function optimizeTableStructures()
    {
        $this->info('Optimizing table structures...');
        
        $tables = ['events', 'products', 'articles', 'categories', 'galleries', 'settings'];
        
        foreach ($tables as $table) {
            try {
                // Check table structure
                $columns = Schema::getColumnListing($table);
                
                // Optimize table
                DB::statement("OPTIMIZE TABLE {$table}");
                $this->line("✓ Optimized table: {$table}");
                
                // Analyze table
                DB::statement("ANALYZE TABLE {$table}");
                $this->line("✓ Analyzed table: {$table}");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to optimize table {$table}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Add missing indexes
     */
    private function addMissingIndexes()
    {
        $this->info('Adding missing indexes...');
        
        $indexes = [
            'events' => [
                'is_active_start_date' => ['is_active', 'start_date'],
                'is_featured_active' => ['is_featured', 'is_active'],
                'start_end_date' => ['start_date', 'end_date']
            ],
            'products' => [
                'active_featured' => ['is_active', 'is_featured'],
                'category_active' => ['category_id', 'is_active'],
                'sort_active' => ['sort_order', 'is_active']
            ],
            'articles' => [
                'status_published' => ['status', 'published_at'],
                'featured_status' => ['is_featured', 'status'],
                'published_status' => ['published_at', 'status']
            ],
            'categories' => [
                'active_sort' => ['is_active', 'sort_order']
            ]
        ];
        
        foreach ($indexes as $table => $tableIndexes) {
            foreach ($tableIndexes as $indexName => $columns) {
                try {
                    $columnList = implode(', ', $columns);
                    DB::statement("CREATE INDEX IF NOT EXISTS {$indexName} ON {$table} ({$columnList})");
                    $this->line("✓ Added index: {$indexName} on {$table}");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to add index {$indexName}: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Optimize query patterns
     */
    private function optimizeQueryPatterns()
    {
        $this->info('Optimizing query patterns...');
        
        // Set optimal MySQL settings
        $mysqlSettings = [
            'SET GLOBAL innodb_buffer_pool_size = 128M',
            'SET GLOBAL query_cache_size = 32M',
            'SET GLOBAL query_cache_type = 1',
            'SET GLOBAL tmp_table_size = 32M',
            'SET GLOBAL max_heap_table_size = 32M'
        ];
        
        foreach ($mysqlSettings as $setting) {
            try {
                DB::statement($setting);
                $this->line("✓ Applied setting: {$setting}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to apply setting: {$setting}");
            }
        }
    }
}