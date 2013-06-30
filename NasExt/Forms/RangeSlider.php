<?php

/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 20013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Forms\Controls;
use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\TextBase;
use Nette\Forms\IControl;
use Nette\Utils\Html;
use Nette\Utils\Json;
use Nette\Utils\Validators;

/**
 * RangeSlider Form control built on jQuery UI range slider.
 *
 * @author Dusan Hudak
 */

class RangeSlider extends BaseControl
{

	/** @var array|Range */
	protected $value;

	/** @var Range */
	protected $range;

	/** @var BaseControl */
	protected $inputPrototype;


	/**
	 * @param null|string $label
	 * @param Range $range
	 */
	public function __construct($label = NULL, Range $range)
	{
		parent::__construct($label);
		$this->control->type = 'text';
		$this->range = $range;
	}


	/**
	 * @param $min
	 * @param $max
	 */
	public function setRange($min, $max)
	{
		$this->range->setRange($min, $max);
	}


	/**
	 * @return Range
	 */
	public function getRange()
	{
		return $this->range;
	}


	/**
	 * @param Range|array $value
	 * @return $this|BaseControl
	 */
	public function setValue($value)
	{
		if ($value instanceof Range) {
			$this->value = $value;
		} else {
			list($min, $max) = $value;
			$this->value = new Range($min, $max);
		}
		return $this;
	}


	/**
	 * @param null|int $key
	 * @return Html
	 */
	public function getControl($key = NULL)
	{
		if ($key !== NULL) {
			return $this->getControlItem($key);
		}

		$controls = array();
		foreach ($this->range as $key => $val) {
			$controls[] = $this->getControlItem($key);
		}

		$rangeSliderId = $this->htmlId . '-range-slider';
		$rangeSlider = Html::el('div', array('id' => $rangeSliderId));

		if (!empty($this->value)) {
			$values = $this->value;
		} else {
			$values = $this->range;
		}
		$dataSetup = array(
			'rangeSliderId' => $rangeSliderId,
			'minId' => $controls[0]->id,
			'maxId' => $controls[1]->id,
			'minValue' => $values->getMin(),
			'maxValue' => $values->getMax(),
			'min' => $this->range->getMin(),
			'max' => $this->range->getMax(),
		);

		$container = Html::el('div', array(
			'id' => $this->htmlId . '-container',
			'class' => 'range-slider',
			'data-setup' => Json::encode($dataSetup)
		));

		$container->add($controls[0]);
		$container->add($rangeSlider);
		$container->add($controls[1]);
		return $container;
	}


	/**
	 * @param int $key
	 * @return Html
	 */
	public function getControlItem($key)
	{
		$control = clone $this->getInputPrototype();
		$control->id .= '-' . $key;

		if (!empty($this->value)) {
			$values = $this->value->getRange();
		} else {
			$values = $this->range->getRange();
		}

		$control->value = $values[$key];

		return $control;
	}


	/**
	 * Is control filled?
	 * @return bool
	 */
	public function isFilled()
	{
		if ((string)$this->value->getMin() == '' || (string)$this->value->getMax() == '') {
			return FALSE;
		}
		return TRUE;
	}


	/**
	 * Rangle validator: is a control's value number in specified range?
	 * @param \Nette\Forms\IControl $control
	 * @param array $range
	 * @return bool
	 */
	public static function validateRange(IControl $control, $range)
	{
		foreach ($control->getValue() as $value) {
			$validator = Validators::isInRange($value, $range);
			if ($validator == FALSE) {
				return FALSE;
			}
		}
		return TRUE;
	}


	/**
	 * Integer validator: is a control's value decimal number?
	 * @param  IControl $control
	 * @return bool
	 */
	public static function validateInteger(IControl $control)
	{
		foreach ($control->getValue() as $value) {
			$validator = Validators::isNumericInt($value);
			if ($validator == FALSE) {
				return FALSE;
			}
		}
		return TRUE;
	}


	/**
	 * Float validator: is a control's value float number?
	 * @param  IControl $control
	 * @return bool
	 */
	public static function validateFloat(IControl $control)
	{
		foreach ($control->getValue() as $value) {
			$validator = Validators::isNumeric(TextBase::filterFloat($value));
			if ($validator == FALSE) {
				return FALSE;
			}
		}
		return TRUE;
	}


	/**
	 * @return Html
	 */
	protected function getInputPrototype()
	{
		if ($this->inputPrototype) {
			return $this->inputPrototype;
		}
		return $this->inputPrototype = $this->createInputPrototype();
	}


	/**
	 * @return Html
	 */
	protected function createInputPrototype()
	{
		$control = parent::getControl();
		$control->name .= '[]';
		return $control;
	}


	/*     * ******************* registration ****************** */


	/**
	 * Adds addRangeSlider() method to \Nette\Forms\Form
	 */
	public static function register()
	{
		Container::extensionMethod('addRangeSlider', callback(__CLASS__, 'addRangeSlider'));
	}


	/**
	 * @param Container $container
	 * @param string $name
	 * @param null|string $label
	 * @param Range $range
	 * @return RangeSlider provides fluent interface
	 */
	public static function addRangeSlider(Container $container, $name, $label = NULL, Range $range)
	{
		$container[$name] = new self($label, $range);
		return $container[$name];
	}

}
