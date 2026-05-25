<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TestSupabaseUpload extends Command
{
    protected $signature = 'supabase:upload-test';
    protected $description = 'Test upload file ke Supabase Storage';

    public function handle()
    {
        $this->info('🧪 Testing Supabase Storage Upload...');
        $this->newLine();

        // Test 1: Check bucket exists
        $this->testBucketExists();

        // Test 2: Try upload using S3 API
        $this->testS3Upload();

        // Test 3: Try upload using Supabase REST API
        $this->testRestApiUpload();

        return Command::SUCCESS;
    }

    protected function testBucketExists()
    {
        $this->info('1️⃣ Checking if bucket "produk" exists...');

        try {
            $response = Http::withHeaders([
                'apikey' => config('supabase.key'),
                'Authorization' => 'Bearer ' . config('supabase.key'),
            ])->get('https://twbvqgjedeapqszljzox.supabase.co/storage/v1/bucket/produk');

            if ($response->successful()) {
                $this->info('   ✅ Bucket "produk" exists!');
                $bucket = $response->json();
                $this->line('   Name: ' . ($bucket['name'] ?? 'N/A'));
                $this->line('   Public: ' . ($bucket['public'] ? 'Yes' : 'No'));
            } else {
                $this->error('   ❌ Bucket "produk" not found!');
                $this->warn('   💡 Create bucket "produk" di Supabase Dashboard → Storage');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Error: ' . $e->getMessage());
        }

        $this->newLine();
    }

    protected function testS3Upload()
    {
        $this->info('2️⃣ Testing S3 API Upload...');

        try {
            $disk = Storage::disk('s3');
            $testContent = 'Test upload at ' . now();
            $testPath = 'test/test-' . time() . '.txt';

            $uploaded = $disk->put($testPath, $testContent);

            if ($uploaded) {
                $this->info('   ✅ S3 Upload SUCCESS!');
                $url = $disk->url($testPath);
                $this->line('   URL: ' . $url);

                // Delete test file
                $disk->delete($testPath);
                $this->line('   ✓ Test file cleaned up');
            } else {
                $this->error('   ❌ S3 Upload FAILED');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ S3 Upload Error: ' . $e->getMessage());
        }

        $this->newLine();
    }

    protected function testRestApiUpload()
    {
        $this->info('3️⃣ Testing Supabase REST API Upload...');

        try {
            $testContent = 'Test upload via REST API at ' . now();
            $testPath = 'test-' . time() . '.txt';

            $response = Http::withHeaders([
                'apikey' => config('supabase.service_key'),
                'Authorization' => 'Bearer ' . config('supabase.service_key'),
                'Content-Type' => 'text/plain',
            ])->withBody($testContent, 'text/plain')
              ->post("https://twbvqgjedeapqszljzox.supabase.co/storage/v1/object/produk/{$testPath}");

            if ($response->successful()) {
                $this->info('   ✅ REST API Upload SUCCESS!');
                $url = "https://twbvqgjedeapqszljzox.supabase.co/storage/v1/object/public/produk/{$testPath}";
                $this->line('   URL: ' . $url);

                // Delete test file
                Http::withHeaders([
                    'apikey' => config('supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('supabase.service_key'),
                ])->delete("https://twbvqgjedeapqszljzox.supabase.co/storage/v1/object/produk/{$testPath}");

                $this->line('   ✓ Test file cleaned up');
            } else {
                $this->error('   ❌ REST API Upload FAILED');
                $this->error('   Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error('   ❌ REST API Error: ' . $e->getMessage());
        }

        $this->newLine();
    }
}
