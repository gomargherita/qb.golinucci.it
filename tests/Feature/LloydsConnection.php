<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\LloydsDoprintService;

class LloydsConnection extends TestCase
{
    /**
     * Connection to Lloyds
     *
     * @return void
     */
    public function test_lloyds_connection()
    {
        $doprint = LloydsDoprintService::handShake();
        $this->assertTrue(true);
    }
}
