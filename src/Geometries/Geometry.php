<?php

namespace ReedTechLLC\LaravelPostgis\Geometries;

use GeoIO\WKB\Parser\Parser;
use ReedTechLLC\LaravelPostgis\Exceptions\UnknownWKTTypeException;

abstract class Geometry implements GeometryInterface, \JsonSerializable
{
    protected static $wkb_types = [
        1 => Point::class,
        2 => LineString::class,
        3 => Polygon::class,
        4 => MultiPoint::class,
        5 => MultiLineString::class,
        6 => MultiPolygon::class,
        7 => GeometryCollection::class
    ];

    public static function getWKTArgument($value)
    {
        $left = strpos($value, '(');
        $right = strrpos($value, ')');

        return substr($value, $left + 1, $right - $left - 1);
    }

    public static function getWKTClass($value)
    {
        $left = strpos($value, '(');
        $type = trim(substr($value, 0, $left));

        switch (strtoupper($type)) {
            case 'POINT':
            case 'POINTZ':
            case 'POINT Z':
            case 'POINT ZM':
            case 'POINT Z M':
                return Point::class;
            case 'LINESTRING':
            case 'LINESTRINGZ':
            case 'LINESTRING Z':
            case 'LINESTRING ZM':
            case 'LINESTRING Z M':
                return LineString::class;
            case 'POLYGON':
            case 'POLYGONZ':
            case 'POLYGON Z':
                return Polygon::class;
            case 'MULTIPOINT':
            case 'MULTIPOINTZ':
            case 'MULTIPOINT Z':
            case 'MULTIPOINT ZM':
            case 'MULTIPOINT Z M':
                return MultiPoint::class;
            case 'MULTILINESTRING':
            case 'MULTILINESTRINGZ':
            case 'MULTILINESTRING Z':
            case 'MULTILINESTRING ZM':
            case 'MULTILINESTRING Z M':
                return MultiLineString::class;
            case 'MULTIPOLYGON':
            case 'MULTIPOLYGONZ':
            case 'MULTIPOLYGON Z':
                return MultiPolygon::class;
            case 'GEOMETRYCOLLECTION':
                return GeometryCollection::class;
            default:
                throw new UnknownWKTTypeException('Type was ' . $type);
        }
    }

    public static function fromWKB($wkb)
    {
        $parser = new Parser(new Factory());

        return $parser->parse($wkb);
    }

    public static function fromWKT($wkt)
    {
        $wktArgument = static::getWKTArgument($wkt);

        return static::fromString($wktArgument);
    }
}
