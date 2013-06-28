<?php

/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * @license    MIT
 * @link       https://github.com/NasExt
 * @author     Dusan Hudak
 */

namespace NasExt\Forms\Controls;


use Nette\Diagnostics\Debugger;
use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\TextBase;
use Nette\Forms\IControl;
use Nette\Utils\Html;
use Nette\Utils\Json;
use Nette\Utils\Validators;

class Rangeslider extends BaseControl
{

	/** @var array */
	protected $value = array();

	/** @var array */
	protected $range = array();

	/** @var BaseControl */
	protected $inputPrototype;


	/**
	 * @param null|string $label
	 * @param array $range
	 * @throws InvalidArgumentException
	 */
	public function __construct($label = NULL, array $range)
	{
		if (count($range) > 2) {
			throw new InvalidArgumentException('The array $range can contain a maximum two values​​, max and min.');
		}

		parent::__construct($label);
		$this->control->type = 'text';
		$this->range = $range;
	}


	/**
	 * @param array $range
	 */
	public function setRange($range)
	{
		$this->range = $range;
	}


	/**
	 * @return array
	 */
	public function getRange()
	{
		return $this->range;
	}


	/**
	 * @return array|mixed
	 */
	public function getValueRange()
	{
		if (!empty($this->value)) {
			return $this->value;
		}
		return $this->range;
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

		$values = $this->getValueRange();
		$dataSetup = array(
			'rangeSliderId' => $rangeSliderId,
			'minId' => $controls[0]->id,
			'maxId' => $controls[1]->id,
			'minValue' => $values[0],
			'maxValue' => $values[1],
			'min' => $this->range[0],
			'max' => $this->range[1],
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
		$values = $this->getValueRange();
		$control->value = $values[$key];

		return $control;
	}


	/**
	 * Is control filled?
	 * @return bool
	 */
	public function isFilled()
	{
		if (empty($this->value[0]) || empty($this->value[1])) {
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
	 * Adds addRangeslider() method to \Nette\Forms\Form
	 */
	public static function register()
	{
		Container::extensionMethod('addRangeslider', callback(__CLASS__, 'addRangeslider'));
	}


	/**
	 * @param Container $container
	 * @param string $name
	 * @param null|string $label
	 * @param array $range
	 * @return Rangeslider provides fluent interface
	 */
	public static function addRangeslider(Container $container, $name, $label = NULL, array $range)
	{
		$container[$name] = new self($label, $range);
		return $container[$name];
	}

}

/**
 * The exception that is thrown when a set range more as two arguments.
 */
class InvalidArgumentException extends \RuntimeException
{
}
