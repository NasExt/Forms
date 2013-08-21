## NasExt\Forms

#### License
MIT

#### Dependencies
[jQuery](http://code.jquery.com/jquery-1.9.1.js)
[jQuery UI](http://code.jquery.com/ui/1.10.3/jquery-ui.js)

List of components:
- **RangeSlider** - jquery range slider control

## Installation

The best way to install is using [Composer](http://getcomposer.org/):

```sh
"repositories": [
    {
        "type": "vcs",
        "url": "git://github.com/NasExt/Forms"
    }
],
"require": {
		"NasExt/Forms": "dev-master"
	}
```

## Documentation

Initialization in your `bootstrap.php`:

```php
\NasExt\Forms\Controls\RangeSlider::register();
```

How to use RangeSlider in form:
````php
$range = new Range(10, 100);
$form->addRangeslider('rangeSlider', 'Set range', $range);
```

Set Default Values:
````php
$range = new Range(10, 100);
$form->addRangeslider('rangeSlider', 'Set range', $range)
	->setDefaultValue(array(15, 33));
// or
$form->addRangeslider('rangeSlider', 'Set range', $range)
	->setDefaultValue(new Range(15, 33));
```

Get Values:
````php
$values = $form->getValues();
$values['rangeSlider']; // return Range object
// or
$values['rangeSlider']->getMin();
$values['rangeSlider']->getMax();
```

Validation:
````php
->addRule($form::FILLED, 'Please complete mandatory field')
->addRule(RangeSlider::INTEGER, 'Please enter a numeric value')
->addRule(RangeSlider::FLOAT, 'Please enter a numeric value')
->addRule(RangeSlider::RANGE, 'Please enter a value between %d and %d', array(10, 100))
// or
->addRule(RangeSlider::RANGE, 'Please enter a value between %d and %d', $range->getRange())
```

Custom init js:
````php
->setAttribute('data-custom-init', Nette\Utils\Json::encode(array('step'=>2)))
```
