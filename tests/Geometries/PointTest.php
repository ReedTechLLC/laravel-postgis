<?php

namespace ReedTechLLC\LaravelPostgis\Tests\Geometries;

use ReedTechLLC\LaravelPostgis\Geometries\Point;
use ReedTechLLC\LaravelPostgis\Tests\BaseTestCase;

class PointTest extends BaseTestCase
{
    public function testFromWKT()
    {
        $point = Point::fromWKT('POINT(1 2)');

        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(1, $point->getLng());
    }

    public function testFromWKT3d()
    {
        $point = Point::fromWKT('POINT(1 2 3)');

        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(1, $point->getLng());
        $this->assertEquals(3, $point->getAlt());
    }

    public function testFromWKT4d()
    {
        $point = Point::fromWKT('POINT(1 2 3 4)');

        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(1, $point->getLng());
        $this->assertEquals(3, $point->getAlt());
        $this->assertEquals(4, $point->getTime());
    }

    public function testToWKT()
    {
        $point = new Point(1, 2);

        $this->assertEquals('POINT(2 1)', $point->toWKT());
    }

    public function testToWKT3d()
    {
        $point = new Point(1, 2, 3);

        $this->assertEquals('POINT Z(2 1 3)', $point->toWKT());
    }

    public function testToWKT4d()
    {
        $point = new Point(1, 2, 3, 4);

        $this->assertEquals('POINT ZM(2 1 3 4)', $point->toWKT());
    }

    public function testGettersAndSetters()
    {
        $point = new Point(1, 2);
        $this->assertSame(1.0, $point->getLat());
        $this->assertSame(2.0, $point->getLng());

        $point->setLat('3');
        $point->setLng('4');

        $this->assertSame(3.0, $point->getLat());
        $this->assertSame(4.0, $point->getLng());
    }

    public function testGettersAndSetters3d()
    {
        $point = new Point(1, 2, 3);
        $this->assertSame(1.0, $point->getLat());
        $this->assertSame(2.0, $point->getLng());
        $this->assertSame(3.0, $point->getAlt());

        $point->setLat('3');
        $point->setLng('4');
        $point->setAlt('5');

        $this->assertSame(3.0, $point->getLat());
        $this->assertSame(4.0, $point->getLng());
        $this->assertSame(5.0, $point->getAlt());
    }

    public function testGettersAndSetters4d()
    {
        $point = new Point(1, 2, 3, 4);
        $this->assertSame(1.0, $point->getLat());
        $this->assertSame(2.0, $point->getLng());
        $this->assertSame(3.0, $point->getAlt());
        $this->assertSame(4.0, $point->getTime());

        $point->setLat('3');
        $point->setLng('4');
        $point->setAlt('5');
        $point->setTime('6');

        $this->assertSame(3.0, $point->getLat());
        $this->assertSame(4.0, $point->getLng());
        $this->assertSame(5.0, $point->getAlt());
        $this->assertSame(6.0, $point->getTime());
    }

    public function testPair()
    {
        $point = Point::fromPair('1.5 2');

        $this->assertSame(1.5, $point->getLng());
        $this->assertSame(2.0, $point->getLat());

        $this->assertSame('1.5 2', $point->toPair());
    }

    public function testPair3d()
    {
        $point = Point::fromPair('1.5 2 2.5');

        $this->assertSame(1.5, $point->getLng());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(2.5, $point->getAlt());

        $this->assertSame('1.5 2 2.5', $point->toPair());
    }

    public function testPair4d()
    {
        $point = Point::fromPair('1.5 2 2.5 3');

        $this->assertSame(1.5, $point->getLng());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(2.5, $point->getAlt());
        $this->assertSame(3.0, $point->getTime());

        $this->assertSame('1.5 2 2.5 3', $point->toPair());
    }

    public function testToString()
    {
        $point = Point::fromString('1.3 2');

        $this->assertSame(1.3, $point->getLng());
        $this->assertSame(2.0, $point->getLat());

        $this->assertEquals('1.3 2', (string)$point);
    }

    public function testToString3d()
    {
        $point = Point::fromString('1.3 2 2.3');

        $this->assertSame(1.3, $point->getLng());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(2.3, $point->getAlt());

        $this->assertEquals('1.3 2 2.3', (string)$point);
    }

    public function testToString4d()
    {
        $point = Point::fromString('1.3 2 2.3 3');

        $this->assertSame(1.3, $point->getLng());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(2.3, $point->getAlt());
        $this->assertSame(3.0, $point->getTime());

        $this->assertEquals('1.3 2 2.3 3', (string)$point);
    }

    public function testJsonSerialize()
    {
        $point = new Point(1.2, 3.4);

        $this->assertInstanceOf(\GeoJson\Geometry\Point::class, $point->jsonSerialize());
        $this->assertSame('{"type":"Point","coordinates":[3.4,1.2]}', json_encode($point));
    }

    public function testJsonSerialize3d()
    {
        $point = new Point(1.2, 3.4, 5.6);

        $this->assertInstanceOf(\GeoJson\Geometry\Point::class, $point->jsonSerialize());
        $this->assertSame('{"type":"Point","coordinates":[3.4,1.2,5.6]}', json_encode($point));
    }

    public function testJsonSerialize4d()
    {
        $point = new Point(1.2, 3.4, 5.6, 7.8);

        $this->assertInstanceOf(\GeoJson\Geometry\Point::class, $point->jsonSerialize());
        $this->assertSame('{"type":"Point","coordinates":[3.4,1.2,5.6,7.8]}', json_encode($point));
    }
}
