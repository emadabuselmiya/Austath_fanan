<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ActivationCode;
use Illuminate\Support\Facades\DB;

class ActivationCodeSeeder extends Seeder
{
    
    public function run()
    {
        set_time_limit(600);
        $codesToGenerate = 50000; // Number of codes to generate
        $chunkSize = 1000; // Define chunk size
        $codes = [];

        // Generate unique codes
        while (count($codes) < $codesToGenerate) {
            $code = Str::upper(Str::random(8)); // Generate an 8-character alphanumeric code
            if (!in_array($code, $codes)) {
                $codes[] = $code; // Ensure unique code
            }
        }

        // Split into chunks and insert in batches
        $chunks = array_chunk($codes, $chunkSize);
        
        foreach ($chunks as $chunk) {
            $data = array_map(function ($code) {
                return [
                    'code' => $code,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $chunk);

            // Insert each chunk into the database
            DB::table('activation_codes')->insert($data);
        }

        \Log::info("Inserted all activation codes in chunks.");
    }
}
