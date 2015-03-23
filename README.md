Texy
====

Library for integration [FSHL](http://fshl.kukulich.cz/) into [Texy!](http://texy.info/) in [Nette](http://nette.org)

Based on:
- http://www.zeminem.cz/pouziti-texy-s-fshl
- https://filip-prochazka.com/blog/hratky-s-texy-na-blog

Require
- https://github.com/lookyman/nette-texy

Installation
------------

The best way to install Trejjam/Texy-fshl is using  [Composer](http://getcomposer.org/):

```sh
$ composer require trejjam/texy-fshl
```

Configuration
-------------

.neon
```yml
extensions:
	texyFshl: Trejjam\DI\TexyFshlExtension

texyFshl:
```

Usage
-----

Presenter
```php
function renderDefault() {
	$template = $this->template;
	$template->texy = '';
	
	$template->texy .= 'Colored block of code';
	//all possible blocks: code, cpp, python, php, neon, config, sh, texy, java, javascript, js, css, sql, html, htmlcb
	$template->texy .= '/--php';
    $template->texy .= 'function foo() {';
    $template->texy .= '	$hello=\'\';';
    $template->texy .= '	echo $helo;';
    $template->texy .= '}';
    $template->texy .= '\--';
    
    $template->texy .= 'Base block of code';
    $template->texy .= '/--code';
    $template->texy .= 'function foo() {';
    $template->texy .= '	$hello=\'\';';
    $template->texy .= '	echo $helo;';
    $template->texy .= '}';
    $template->texy .= '\--';
}
```

.latte
```latte
	{$texy|texy}
```
