<?php

/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * @license    MIT
 * @link       https://github.com/NasExt
 * @author     Dusan Hudak
 */

namespace NasExt\Forms\Controls;


class Range implements \IteratorAggregate
{
	/** @var array */
	protected $range = array();


	/**
	 * @param $min
	 * @param $max
	 */
	public function __construct($min, $max)
	{
		$this->setRange($min, $max);
		return $this;
	}


	/**
	 * @param int $min
	 * @param int $max
	 * @return $this
	 */
	public function setRange($min, $max)
	{
		$this->range = array($min, $max);
		return $this;
	}


	/**
	 * @return array
	 */
	public function getRange()
	{
		return $this->range;
	}


	/**
	 * @param int $min
	 * @return $this
	 */
	public function setMin($min)
	{
		$this->range[0] = $min;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getMin()
	{
		return $this->range[0];
	}

	/**
	 * @param int $max
	 * @return $this
	 */
	public function setMax($max)
	{
		$this->range[1] = $max;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getMax()
	{
		return $this->range[1];
	}


	/**
	 * @return \ArrayIterator|\Traversable
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->range);
	}

}
