<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestRailwayConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'railway:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test koneksi ke Railway MySQL dan Supabase Storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Railway + Supabase Connection...');
        $this->newLine();

        // Test Railway Database
        $dbSuccess = $this->testRailwayDatabase();
        
        // Test Supabase Storage
        $storageSuccess = $this->testSupabaseStorage();
        
        // Display Configuration
        $this->displayConfiguration();

        // Summary
        $this->newLine();
        if ($dbSuccess && $storageSuccess) {
            $this->info('✅ Semua koneksi berhasil! Aplikasi siap digunakan.');
        } else {
            $this->error('❌ Ada koneksi yang gagal. Periksa konfigurasi di atas.');
        }

        return $dbSuccess && $storageSuccess ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Test Railway MySQL database connection
     */
    protected function testRailwayDatabase()
    {
        $this->info('🚂 Testing Railway MySQL Connection...');
        
        try {
            $connection = DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
            $driver = DB::connection()->getDriverName();
            
            $this->info("✅ Railway MySQL Connected!");
            $this->line("   Driver: {$driver}");
            $this->line("   Database: {$dbName}");
            $this->line("   Host: " . config('database.connections.mysql.host'));
            $this->line("   Port: " . config('database.connections.mysql.port'));
            
            // Test query - Get MySQL version
            $result = DB::select('SELECT VERSION() as version');
            if (!empty($result)) {
                $version = $result[0]->version;
                $this->line("   MySQL Version: {$version}");
            }
            
            // Check tables
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            $this->line("   Tables: {$tableCount} table(s)");
            
            if ($tableCount > 0) {
                $tableKey = 'Tables_in_' . $dbName;
                $sampleTables = array_slice(array_column($tables, $tableKey), 0, 5);
                $this->line("   Sample tables: " . implode(', ', $sampleTables));
            }
            
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error("❌ Railway MySQL Connection Failed!");
            $this->error("   Error: " . $e->getMessage());
            $this->newLine();
            
            $this->warn("💡 Troubleshooting:");
            $this->line("   1. Periksa kredensial Railway di file .env:");
            $this->line("      - DB_HOST (Railway MySQL host)");
            $this->line("      - DB_PORT (biasanya custom port seperti 24334)");
            $this->line("      - DB_DATABASE (biasanya 'railway')");
            $this->line("      - DB_USERNAME (biasanya 'root')");
            $this->line("      - DB_PASSWORD (dari Railway dashboard)");
            $this->line("   2. Pastikan DB_CONNECTION=mysql di .env");
            $this->line("   3. Cek koneksi internet Anda");
            $this->line("   4. Verifikasi kredensial di Railway dashboard");
            $this->newLine();
            
            return false;
        }
    }

    /**
     * Test Supabase storage connection
     */
    protected function testSupabaseStorage()
    {
        $this->info('💾 Testing Supabase Storage Connection...');
        
        try {
            $disk = Storage::disk('s3');
            
            // Try to list files in bucket
            $files = $disk->files();
            
            $this->info("✅ Supabase Storage Connected!");
            $this->line("   Disk: s3");
            $this->line("   Bucket: " . config('filesystems.disks.s3.bucket'));
            $this->line("   Endpoint: " . config('filesystems.disks.s3.endpoint'));
            $this->line("   Region: " . config('filesystems.disks.s3.region'));
            $this->line("   Files: " . count($files) . " file(s) in bucket");
            
            // Try to create a test file
            $testFileName = 'test-connection-' . time() . '.txt';
            $testContent = 'Test connection from Laravel at ' . now();
            
            $uploaded = $disk->put($testFileName, $testContent);
            
            if ($uploaded) {
                $this->line("   ✓ Upload test: SUCCESS");
                
                // Get URL
                $url = $disk->url($testFileName);
                $this->line("   ✓ File URL: {$url}");
                
                // Delete test file
                $disk->delete($testFileName);
                $this->line("   ✓ Delete test: SUCCESS");
            }
            
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error("❌ Supabase Storage Connection Failed!");
            $this->error("   Error: " . $e->getMessage());
            $this->newLine();
            
            $this->warn("💡 Troubleshooting:");
            $this->line("   1. Periksa kredensial Supabase di file .env:");
            $this->line("      - AWS_ACCESS_KEY_ID");
            $this->line("      - AWS_SECRET_ACCESS_KEY");
            $this->line("      - AWS_ENDPOINT");
            $this->line("      - AWS_BUCKET");
            $this->line("   2. Pastikan bucket 'produk' sudah dibuat di Supabase");
            $this->line("   3. Periksa bucket policy (public/private)");
            $this->line("   4. Cek di Supabase Dashboard → Storage");
            $this->newLine();
            
            return false;
        }
    }

    /**
     * Display current configuration
     */
    protected function displayConfiguration()
    {
        $this->info('⚙️  Current Configuration:');
        $this->newLine();
        
        $this->table(
            ['Setting', 'Value'],
            [
                ['Environment', config('app.env')],
                ['App URL', config('app.url')],
                ['', ''],
                ['DB Connection', config('database.default')],
                ['DB Host', config('database.connections.mysql.host')],
                ['DB Port', config('database.connections.mysql.port')],
                ['DB Database', config('database.connections.mysql.database')],
                ['DB Username', config('database.connections.mysql.username')],
                ['', ''],
                ['Storage Disk', config('filesystems.default')],
                ['Storage Bucket', config('filesystems.disks.s3.bucket', 'not set')],
                ['Storage Endpoint', config('filesystems.disks.s3.endpoint', 'not set')],
                ['Storage Region', config('filesystems.disks.s3.region', 'not set')],
            ]
        );
        
        $this->newLine();
        $this->info('📝 Untuk panduan lengkap, lihat file RAILWAY_SUPABASE_SETUP.md');
    }
}
