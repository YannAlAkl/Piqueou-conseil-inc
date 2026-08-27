<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'text'],
            ['name' => 'unique_choice'],
            ['name' => 'multiple_choice'],

        ];

        foreach ($types as $type) {
            QuestionType::updateOrCreate(
                ['name' => $type['name']]

            );
        }
    }
}
