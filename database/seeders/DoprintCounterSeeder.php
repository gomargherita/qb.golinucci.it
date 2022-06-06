<?php

namespace Database\Seeders;

use App\Models\DoprintCounter;
use Illuminate\Database\Seeder;

class DoprintCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // esempio GV21PI00565-LB
        DoprintCounter::create(
            [
                'product_id' => 1,
                'year' => 2022, 
                'counter' => 580,
                'prefix' => 'GV21PI',
                'postfix' => '-LB',
            ],
        );
    }
}
