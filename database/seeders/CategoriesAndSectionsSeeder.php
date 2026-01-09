<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Section;

class CategoriesAndSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Sections (Teams)
        $sections = [
            ['name' => 'Men Hair Team', 'description' => 'Services provided by the men\'s hair styling team'],
            ['name' => 'Women Hair Team', 'description' => 'Services provided by the women\'s hair styling team'],
            ['name' => 'Nail Team', 'description' => 'Services provided by the nail care specialists'],
            ['name' => 'Beauty Team', 'description' => 'Services provided by the beauty and skincare team'],
        ];

        foreach ($sections as $section) {
            Section::firstOrCreate(['name' => $section['name']], $section);
        }

        // Create Categories
        $categories = [
            ['name' => 'Hair Cut', 'description' => 'Hair cutting and styling services'],
            ['name' => 'Hair Color', 'description' => 'Hair coloring and highlighting services'],
            ['name' => 'Hair Treatment', 'description' => 'Hair care and treatment services'],
            ['name' => 'Nail Care', 'description' => 'Nail care and manicure services'],
            ['name' => 'Nail Art', 'description' => 'Nail art and design services'],
            ['name' => 'Facial', 'description' => 'Facial and skincare services'],
            ['name' => 'Makeup', 'description' => 'Makeup and beauty services'],
            ['name' => 'Massage', 'description' => 'Massage and relaxation services'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
