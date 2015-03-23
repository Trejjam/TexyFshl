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

Presenter:
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

default.latte:
```latte
    <div class="texy">
	    {$texy|texy}
	</div>
```

LESS:
```less
.texy {
    @import "bootstrap/reset.less";
    @import "bootstrap/code.less";

    .prettyprint {
        padding: 8px;
        background-color: #f7f7f9;
        border: 1px solid #e1e1e8;
        //line-height: 0 !important;
    }
    .prettyprint.linenums {
        -webkit-box-shadow: inset 45px 0 0 #fbfbfc, inset 46px 0 0 #ececf0;
        -moz-box-shadow: inset 45px 0 0 #fbfbfc, inset 46px 0 0 #ececf0;
        box-shadow: inset 45px 0 0 #fbfbfc, inset 46px 0 0 #ececf0;
    }
    ol.linenums {
        margin: 0 0 0 43px; /* IE indents via margin-left */
        padding-left: 0px;
    }
    ol.linenums li {
        padding-left: 6px;
        color: #bebec5;
        line-height: 20px;
        text-shadow: 0 1px 0 #fff;
    }
    ol.linenums li > span {
        color: black;
    }

    a {
        .font(Arial, 16px, @base);

        &:hover {
            text-decoration: underline;
        }
    }
    p {
        .font(Arial, 16px, #000);
    }
    li {
        .font(Arial, 16px, #000);
    }

    @import (less) "/vendor/kukulich/fshl/style.css";
}
```
