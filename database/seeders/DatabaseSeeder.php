<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $listVars = [
            'lastRegisteredIDSync',
            'lastLoggedIDSync',
            'lastScenesVisitIDSync',
            'lastClickEventsIDSync'
        ];

        foreach ($listVars as $var ) {
            $variable = new \App\Models\Variable;  
            $variable->name = $var;
            $variable->value = 0;
            $variable->save();
        }
    }
}
