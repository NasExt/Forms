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

use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\IControl;
use Nette\Utils\Callback;
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
    /** Validators */
    const INTEGER = '\NasExt\Forms\Controls\RangeSlider::validateInteger';
    const RANGE = '\NasExt\Forms\Controls\RangeSlider::validateRange';
    const FLOAT = '\NasExt\Forms\Controls\RangeSlider::validateFloat';

    /** @var array|Range */
    protected $value;

    /** @var Range */
    protected $range;

    /** @var BaseControl */
    protected $inputPrototype;

    /** @var  array */
    protected $attributes = array();


    /**
     * @param null|string $label
     * @param Range $range
     */
    public function __construct($label = NULL, Range $range)
    {
        parent::__construct($label);
        $this->control->type = 'text';
        $this->range = $range;
        $this->setValue($range);
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
     * Loads HTTP data.
     * @return void
     */
    public function loadHttpData()
    {
        $defaults = $this->getValue();
        $this->setValue($this->getHttpData(Form::DATA_TEXT, '[]'));
        if ($this->value !== NULL) {
            foreach ($this->value as $value) {
                if (is_array($this->disabled) && isset($this->disabled[$value])) {
                    $this->value = NULL;
                    break;
                }
            }
        }
        if ($defaults && is_array($this->disabled)) {
            $this->setDefaultValue($defaults);
        }
    }


    /**
     * @param Range|array $value
     * @return RangeSlider  provides a fluent interface
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
     * Changes control's HTML attribute.
     * @param  string $name
     * @param  mixed $value
     * @return RangeSlider  provides a fluent interface
     */
    public function setAttribute($name, $value = TRUE)
    {
        $this->attributes[$name] = $value;
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

        $rangeMinContainer = Html::el('div');
        $rangeMinContainer->setAttribute('id', $this->htmlId . '-min-container');
        $rangeMinContainer->setAttribute('class', 'range-slider-min-container');
        $rangeMinContainer->addHtml($controls[0]);

        $rangeContainer = Html::el('div');
        $rangeContainer->setAttribute('id', $this->htmlId . '-range-container');
        $rangeContainer->setAttribute('class', 'range-container');

        $rangeSliderId = $this->htmlId . '-slider';
        $rangeSlider = Html::el('div');
        $rangeSlider->setAttribute('id', $rangeSliderId);
        $rangeSlider->setAttribute('class', 'range-slider');

        $rangeContainer->addHtml($rangeSlider);

        $rangeMaxContainer = Html::el('div');
        $rangeMaxContainer->setAttribute('id', $this->htmlId . '-max-container');
        $rangeMaxContainer->setAttribute('class', 'range-slider-max-container');
        $rangeMaxContainer->addHtml($controls[1]);


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

        $container = Html::el('div');
        $container->setAttribute('id', $this->htmlId . '-control');
        $container->setAttribute('class', 'range-slider-control');
        $container->setAttribute('data-init', Json::encode($dataSetup));
        $container->addAttributes($this->attributes);
        $container->addHtml($rangeMinContainer);
        $container->addHtml($rangeContainer);
        $container->addHtml($rangeMaxContainer);

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
            $validator = Validators::isNumeric(str_replace(array(' ', ','), array('', '.'), $value));

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
        Container::extensionMethod('addRangeSlider', Callback::closure(__CLASS__, 'addRangeSlider'));
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
