<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            'name' => 'UI',
            'slug' => 'ui',
        ]);
        DB::table('categories')->insert([
            'name' => 'UX',
            'slug' => 'ux',
        ]);
        DB::table('categories')->insert([
            'name' => 'Front End',
            'slug' => 'front-end',
        ]);
        DB::table('categories')->insert([
            'name' => 'Back End',
            'slug' => 'back-end',
        ]);
    }
}
