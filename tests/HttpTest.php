<?php

namespace Nnjeim\Http\Tests;

use Nnjeim\Http\FetchServiceProvider;
use Orchestra\Testbench\TestCase;
use Nnjeim\Http\Fetch;

class HttpTest extends TestCase
{

    protected array $publicAPI = [
        'GET' => 'https://nnjeim.com/api/countries'
    ];

    /** @test */
    public function can_get_request()
    {

        ['response' => $response, 'status' => $status] = Fetch::get($this->publicAPI['GET']);

        $this->assertTrue($status == 200);
    }

    protected function getPackageProviders($app)
    {

        return [FetchServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Guzzle' => Fetch::class,
        ];
    }
}
