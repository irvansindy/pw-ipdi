<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\DialisisController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DialisisControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ],
        ]);

        $this->app['db']->connection()->getSchemaBuilder()->dropIfExists('master_dialises');
        $this->app['db']->connection()->getSchemaBuilder()->create('master_dialises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function test_it_can_create_and_fetch_dialisis_records(): void
    {
        $controller = new DialisisController();

        $request = Request::create('/dialisis', 'POST', ['name' => 'RS Test Dialisis']);
        $response = $controller->store($request);

        $this->assertEquals(200, $response->getStatusCode());

        $payload = json_decode($response->getContent(), true);
        $this->assertSame('RS Test Dialisis', $payload['data']['name']);
        $this->assertDatabaseHas('master_dialises', ['name' => 'RS Test Dialisis']);

        $fetchResponse = $controller->fetch();
        $this->assertEquals(200, $fetchResponse->getStatusCode());

        $fetchPayload = json_decode($fetchResponse->getContent(), true);
        $this->assertCount(1, $fetchPayload['data']);
    }
}
