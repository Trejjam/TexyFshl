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
