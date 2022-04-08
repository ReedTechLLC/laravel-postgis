<?php

namespace ReedTechLLC\LaravelPostgis\Geometries;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

abstract class PointCollection implements IteratorAggregate, Arrayable, ArrayAccess, Countable, JsonSerializable
{
	/**
	 * @var Point[]
	 */
	protected $points;

	/**
	 * @param Point[] $points
	 */
	public function __construct(array $points)
	{
		if (count($points) < 2) {
			throw new InvalidArgumentException('$points must contain at least two entries');
		}

		$validated = array_filter($points, function ($value) {
			return $value instanceof Point;
		});

		if (count($points) !== count($validated)) {
			throw new InvalidArgumentException('$points must be an array of Points');
		}
		$this->points = $points;
	}

	public function getPoints()
	{
		return $this->points;
	}

	public function toArray()
	{
		return $this->points;
	}

	public function getIterator(): Traversable
	{
		return new ArrayIterator($this->points);
	}

	public function prependPoint(Point $point)
	{
		array_unshift($this->points, $point);
	}

	public function appendPoint(Point $point)
	{
		$this->points[] = $point;
	}

	public function insertPoint($index, Point $point)
	{
		if (count($this->points) - 1 < $index) {
			throw new InvalidArgumentException('$index is greater than the size of the array');
		}

		array_splice($this->points, $offset, 0, [$point]);
	}

	public function offsetExists(mixed $offset): bool
	{
		return isset($this->points[$offset]);
	}

	public function offsetGet(mixed $offset): mixed
	{
		return $this->offsetExists($offset) ? $this->points[$offset] : null;
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		if (!($value instanceof Point)) {
			throw new InvalidArgumentException('$value must be an instance of Point');
		}

		if (is_null($offset)) {
			$this->appendPoint($value);
		} else {
			$this->points[$offset] = $value;
		}
	}

	public function offsetUnset(mixed $offset): void
	{
		unset($this->points[$offset]);
	}

	public function count(): int
	{
		return count($this->points);
	}

	public function toPairList()
	{
		return implode(',', array_map(function (Point $point) {
			return $point->toPair();
		}, $this->points));
	}
}
