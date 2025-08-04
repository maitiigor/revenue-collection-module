<?php

namespace Maitiigor\Payroll\Tests\Feature;

use Maitiigor\Payroll\Tests\TestCase;
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
