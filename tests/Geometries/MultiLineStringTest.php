<?php

namespace ReedTechLLC\LaravelPostgis\Tests\Geometries;

use ReedTechLLC\LaravelPostgis\Geometries\LineString;
use ReedTechLLC\LaravelPostgis\Geometries\MultiLineString;
use ReedTechLLC\LaravelPostgis\Geometries\Point;
use ReedTechLLC\LaravelPostgis\Tests\BaseTestCase;

class MultiLineStringTest extends BaseTestCase
{
    public function testFromWKT()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING((1 1,2 2,2 3),(3 4,4 3,6 5))');
        $this->assertInstanceOf(MultiLineString::class, $multilinestring);

        $this->assertSame(2, $multilinestring->count());
    }

    public function testFromWKT3d()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING Z((1 1 1,2 2 2,2 3 4),(3 4 5,4 3 2,6 5 4))');
        $this->assertInstanceOf(MultiLineString::class, $multilinestring);

        $this->assertSame(2, $multilinestring->count());
    }

    public function testFromWKT4d()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING ZM((1 1 1 1,2 2 2 2,2 3 4 3),(3 4 5 6,4 3 2 7,6 5 4 8))');
        $this->assertInstanceOf(MultiLineString::class, $multilinestring);

        $this->assertSame(2, $multilinestring->count());
    }

    public function testToWKT()
    {
        $collection = new LineString(
            [
                new Point(1, 1),
                new Point(1, 2),
                new Point(2, 2),
                new Point(2, 1),
                new Point(1, 1)
            ]
        );

        $multilinestring = new MultiLineString([$collection]);

        $this->assertSame('MULTILINESTRING((1 1,2 1,2 2,1 2,1 1))', $multilinestring->toWKT());
    }

    public function testToWKT3d()
    {
        $collection = new LineString(
            [
                new Point(1, 1, 1),
                new Point(1, 2, 3),
                new Point(2, 2, 2),
                new Point(2, 1, 3),
                new Point(1, 1, 1)
            ]
        );

        $multilinestring = new MultiLineString([$collection]);

        $this->assertSame('MULTILINESTRING Z((1 1 1,2 1 3,2 2 2,1 2 3,1 1 1))', $multilinestring->toWKT());
    }

    public function testToWKT4d()
    {
        $collection = new LineString(
            [
                new Point(1, 1, 1, 1),
                new Point(1, 2, 3, 2),
                new Point(2, 2, 2, 3),
                new Point(2, 1, 3, 4),
                new Point(1, 1, 1, 5)
            ]
        );

        $multilinestring = new MultiLineString([$collection]);

        $this->assertSame('MULTILINESTRING ZM((1 1 1 1,2 1 3 2,2 2 2 3,1 2 3 4,1 1 1 5))', $multilinestring->toWKT());
    }

    public function testJsonSerialize()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING Z((1 1 1,2 2 2,2 3 4),(3 4 5,4 3 2,6 5 4))');

        $this->assertInstanceOf(\GeoJson\Geometry\MultiLineString::class, $multilinestring->jsonSerialize());
        $this->assertSame(
            '{"type":"MultiLineString","coordinates":[[[1,1,1],[2,2,2],[2,3,4]],[[3,4,5],[4,3,2],[6,5,4]]]}',
            json_encode($multilinestring)
        );
    }

    public function testJsonSerialize4d()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING ZM((1 1 1 1,2 2 2 2,2 3 4 3),(3 4 5 5,4 3 2 6,6 5 4 7))');

        $this->assertInstanceOf(\GeoJson\Geometry\MultiLineString::class, $multilinestring->jsonSerialize());
        $this->assertSame(
            '{"type":"MultiLineString","coordinates":[[[1,1,1,1],[2,2,2,2],[2,3,4,3]],[[3,4,5,5],[4,3,2,6],[6,5,4,7]]]}',
            json_encode($multilinestring)
        );
    }
}
