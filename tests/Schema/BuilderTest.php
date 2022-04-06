<?php

namespace ReedTechLLC\LaravelPostgis\Tests\Schema;

use Mockery;
use ReedTechLLC\LaravelPostgis\PostgisConnection;
use ReedTechLLC\LaravelPostgis\Schema\Blueprint;
use ReedTechLLC\LaravelPostgis\Schema\Builder;
use ReedTechLLC\LaravelPostgis\Tests\BaseTestCase;

class BuilderTest extends BaseTestCase
{
    public function testReturnsCorrectBlueprint()
    {
        $connection = Mockery::mock(PostgisConnection::class);
        $connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

        $mock = Mockery::mock(Builder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
