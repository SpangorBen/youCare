<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Volunteer Work', 'Fundraising Event', 'Community Outreach', 'Environmental Project', 'Healthcare Initiative'];

        foreach ($types as $type) {
            Type::create([
                'name' => $type,
            ]);
        }
    }
}
