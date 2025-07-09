<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category1 = Category::factory()->create([
            'name'=>'مضادات حيوية ',
        ]);
        $category2 = Category::factory()->create([
            'name'=>'مسكنات',
        ]);
        $category3 = Category::factory()->create([
            'name'=>' فيتامينات',
        ]);
        $category4 = Category::factory()->create([
            'name'=>'أدوية السعال والبرد',
        ]);
        $category5 = Category::factory()->create([
            'name'=>'أدوية الجهاز الهضمي',
        ]);
        $category6 = Category::factory()->create([
            'name'=>'أدوية القلب وضغط الدم',
        ]);
        $category7 = Category::factory()->create([
            'name'=>'مستحضرات طبية عامة',
        ]);
    }
}
