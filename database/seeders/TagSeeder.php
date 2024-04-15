<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tags')->insert([
            'name' => 'PHP',
            'slug' => 'php',
        ]);
        DB::table('tags')->insert([
            'name' => 'JS',
            'slug' => 'js',
        ]);
        DB::table('tags')->insert([
            'name' => 'Photoshop',
            'slug' => 'photoshop',
        ]);
    }
}
