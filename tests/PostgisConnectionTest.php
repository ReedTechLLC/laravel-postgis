<?php

namespace ReedTechLLC\LaravelPostgis\Tests;

use ReedTechLLC\LaravelPostgis\PostgisConnection;
use ReedTechLLC\LaravelPostgis\Schema\Builder;
use ReedTechLLC\LaravelPostgis\Tests\Stubs\PDOStub;

class PostgisConnectionTest extends BaseTestCase
{
    private $postgisConnection;

    protected function setUp(): void
    {
        $pgConfig = ['driver' => 'pgsql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $this->postgisConnection = new PostgisConnection(new PDOStub(), 'database', 'prefix', $pgConfig);
    }

    public function testGetSchemaBuilder()
    {
        $builder = $this->postgisConnection->getSchemaBuilder();

        $this->assertInstanceOf(Builder::class, $builder);
    }
}
