<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = public_path('csv/permissions.csv');

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            Permission::create($record);
        }
    }
}
