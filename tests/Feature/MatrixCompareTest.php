<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MatrixCompareTest extends TestCase
{


    // TODO: 
    // 1. [x] If compare1 and compare2 same id, value must be 1
    // 2. [ ] Auto create opposite compare 
    // 3. [ ] Auto update opposite compare
    // 4. [ ] If compare1OutputId is exist

    /**
     * A basic feature test example.
     */
    public function bad_request_if_output_compare_same_and_value_not_1(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->post('/api/matrix-compare/store', [
            'variableInputId' => 1,
            'compare1VariableOutputId' => 2,
            'compare2VariableOutputId' => 2,
            'value' => 2
        ]);
        $response->assertBadRequest();
        $response->assertJson(['message' => 'If variable output compare is same, value must be 1']);
    }

    // public function auto_create_
}
