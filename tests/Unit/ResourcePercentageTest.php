<?php

namespace Tests\Unit;

use App\Models\Resource;
use PHPUnit\Framework\TestCase;

class ResourcePercentageTest extends TestCase
{
    public function test_deployed_percentage_zero_total()
    {
        $r = new Resource();
        $r->total_quantity = 0;
        $r->deployed_quantity = 5;

        $this->assertEquals(0, $r->deployedPercentage());
    }

    public function test_deployed_percentage_normal()
    {
        $r = new Resource();
        $r->total_quantity = 20;
        $r->deployed_quantity = 5;

        $this->assertEquals(25, $r->deployedPercentage());
    }
}
