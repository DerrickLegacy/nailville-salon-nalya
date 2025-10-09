<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;


class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $services = [
            ['service_code' => 'HAIRCUT', 'name' => 'Hair Cut', 'category' => 'Hair', 'price' => 15000],
            ['service_code' => 'BRIDAL', 'name' => 'Bridal Package', 'category' => 'Package', 'price' => 200000],
            ['service_code' => 'BRAIDING', 'name' => 'Hair Styling / Braiding', 'category' => 'Hair', 'price' => 50000],
            ['service_code' => 'COLORING', 'name' => 'Hair Coloring / Treatment', 'category' => 'Hair', 'price' => 80000],
            ['service_code' => 'SHAMPOO', 'name' => 'Shampoo & Conditioning', 'category' => 'Hair', 'price' => 20000],
            ['service_code' => 'NAILS', 'name' => 'Nail Care (Manicure / Pedicure)', 'category' => 'Beauty', 'price' => 30000],
            ['service_code' => 'FACIAL', 'name' => 'Facial / Skin Care', 'category' => 'Beauty', 'price' => 40000],
            ['service_code' => 'MASSAGE', 'name' => 'Massage Therapy', 'category' => 'Spa', 'price' => 60000],
            ['service_code' => 'WAXING', 'name' => 'Waxing / Hair Removal', 'category' => 'Spa', 'price' => 35000],
            ['service_code' => 'MAKEUP', 'name' => 'Makeup Services', 'category' => 'Beauty', 'price' => 50000],
            ['service_code' => 'PACKAGES', 'name' => 'Service Packages (Combo Deals)', 'category' => 'Package', 'price' => 120000],
            ['service_code' => 'OTHER', 'name' => 'Other', 'category' => 'Misc', 'price' => 0],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
