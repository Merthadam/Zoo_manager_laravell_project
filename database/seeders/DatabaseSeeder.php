<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Enclosure;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $enclosures = Enclosure::factory(6)->create();

        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $availableEnclosureIds = $enclosures->pluck('id')->shuffle()->take(4)->toArray();

            $user->enclosures()->syncWithoutDetaching($availableEnclosureIds);
        }
    }
}
