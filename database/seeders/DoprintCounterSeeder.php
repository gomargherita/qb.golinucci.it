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
                'year' => 2021, 
                'counter' => 568,  // 00568 https://stackoverflow.com/questions/66796921/store-integer-with-leading-0-if-set
                'prefix' => 'GV21PI',
                'postfix' => '-LB',
            ],
        );
    }
}
