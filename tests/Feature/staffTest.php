<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class staffTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllStaffData(){

        $allStaff = $this->json('GET', 'api/admin/staff/all');
        $allStaff->assertStatus(200);

    }





}
