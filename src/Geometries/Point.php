<?php

namespace ReedTechLLC\LaravelPostgis\Geometries;

class Point extends Geometry
{
    protected $lat;
    protected $lng;
    protected $alt;
    protected $time;

    public function __construct($lat, $lng, $alt = null, $time = null)
    {
        $this->lat = (float)$lat;
        $this->lng = (float)$lng;
        $this->alt = isset($alt) ? (float)$alt : null;
        $this->time = isset($time) ? (float)$time :  null;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = (float)$lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = (float)$lng;
    }

    public function getAlt()
    {
        return $this->alt;
    }

    public function setAlt($alt)
    {
        $this->alt = (float)$alt;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = (float)$time;
    }

    public function is3d()
    {
        return isset($this->alt);
    }

    public function is4d()
    {
        return isset($this->time);
    }

    public function toPair()
    {
        $pair = self::stringifyFloat($this->getLng()) . ' ' . self::stringifyFloat($this->getLat());
        if ($this->is3d()) {
            $pair .= ' ' . self::stringifyFloat($this->getAlt());
            if ($this->is4d()) {
                $pair .= ' ' . self::stringifyFloat($this->getTime());
            }
        }
        return $pair;
    }

    private static function stringifyFloat($float)
    {
        // normalized output among locales
        return rtrim(rtrim(sprintf('%F', $float), '0'), '.');
    }

    public static function fromPair($pair)
    {
        $pair = preg_replace('/^[a-zA-Z\(\)]+/', '', trim($pair));
        $splits = explode(' ', trim($pair));
        $lng = $splits[0];
        $lat = $splits[1];
        if (count($splits) > 2) {
            $alt = $splits[2];
            if (count($splits) > 3) {
                $time = $splits[3];
            }
        }

        return new static((float)$lat, (float)$lng, isset($alt) ? (float)$alt : null, isset($time) ? (float)$time : null);
    }

    public function toWKT()
    {
        $wktType = 'POINT';
        if ($this->is3d()) {
            $wktType .= ' Z';
            if ($this->is4d()) {
                $wktType .= 'M';
            }
        }
        return sprintf('%s(%s)', $wktType, (string)$this);
    }

    public static function fromString($wktArgument)
    {
        return static::fromPair($wktArgument);
    }

    public function __toString()
    {
        return $this->toPair();
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\Point
     */
    public function jsonSerialize(): mixed
    {
        $position = [$this->getLng(), $this->getLat()];
        if ($this->is3d()) {
            $position[] = $this->getAlt();
            if ($this->is4d()) {
                $position[] = $this->getTime();
            }
        }
        return new \GeoJson\Geometry\Point($position);
    }
}
