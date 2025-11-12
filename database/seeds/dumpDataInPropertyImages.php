<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Property_images;

class DumpDataInPropertyImages extends Seeder
{
    public function run()
    {
        DB::table('property_images')->truncate();

        // Try to get existing property_location details
        $details = DB::table('property_location')->get();
        $images = [];

        // If property_location is empty, make dummy fake data
        if ($details->isEmpty()) {
            for ($i = 1; $i <= 10; $i++) {
                $images[] = [
                    'house_id'      => $i,
                    'user_id'       => rand(1, 5),
                    'img_name'      => "dummy_image_{$i}.jpg",
                    'primary_image' => "dummy_image_{$i}.jpg",
                    'image_caption' => "Sample Caption {$i}",
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
        } else {
            foreach ($details as $key => $detail) {
                /** @var object $detail */
                $name = time() . $key . '.jpg';
                $images[] = [
                    'house_id'      => $detail->house_id,
                    'user_id'       => $detail->user_id,
                    'img_name'      => $name,
                    'primary_image' => $name,
                    'image_caption' => 'Image Caption ' . $key,
                    'created_at'    => $detail->created_at,
                    'updated_at'    => $detail->created_at,
                ];
            }
        }

        // Insert data in batches
        foreach (array_chunk($images, 500) as $chunk) {
            Property_images::insert($chunk);
        }

        echo "✅ Dummy data inserted successfully into property_images table.\n";
    }

}
