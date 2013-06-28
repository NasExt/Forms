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
$form->addRangeslider('rangeSlider', 'Set range', array(10, 100));
```

Set Default Value:
````php
$form->addRangeslider('rangeSlider', 'Set range', array(10, 100))
	->setDefaultValue(array(15, 33));
```

Validation:
````php
$form->addRangeslider('rangeSlider', 'Set range', array(10, 100))
	->addRule($form::FILLED, 'You must set fields')
	->addRule($form::INTEGER, 'Fields must contain only numbers')
	->addRule($form::RANGE, 'You can set a value from %d to %d', array(10, 100));
```
