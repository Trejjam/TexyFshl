<?php
/**
 * Created by PhpStorm.
 * User: Jan
 * Date: 25. 1. 2015
 * Time: 17:38
 */

namespace Trejjam\DI;

use Nette;

class TexyFshlExtension extends Nette\DI\CompilerExtension
{
	/**
	 * @var array
	 */
	protected $defaults = [
		'parentExtension' => ['texy'],
		'fshlHtmlOutput'  => 'Trejjam\TexyFshl\FshlHtmlOutput',
		'highlights'      => [],
	];

	/**
	 * @var array
	 */
	protected $highlights = [
		'block/code'       => TRUE,
		'block/cpp'        => 'FSHL\Lexer\Cpp',
		'block/python'     => 'FSHL\Lexer\Python',
		'block/php'        => 'FSHL\Lexer\Php',
		'block/neon'       => 'FSHL\Lexer\Neon',
		'block/config'     => TRUE, // @todo
		'block/sh'         => TRUE, // @todo
		'block/texy'       => 'FSHL\Lexer\Texy',
		'block/java'       => 'FSHL\Lexer\Java',
		'block/javascript' => 'FSHL\Lexer\Javascript',
		'block/js'         => 'FSHL\Lexer\Javascript',
		'block/css'        => 'FSHL\Lexer\Css',
		'block/sql'        => 'FSHL\Lexer\Sql',
		'block/html'       => 'FSHL\Lexer\Html',
		'block/htmlcb'     => 'FSHL\Lexer\Html',
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig(array_merge($this->defaults, ['highlights' => $this->highlights]));

		$this->validate($config, $this->defaults, $this->name);
		$this->validate($config['highlights'], $this->highlights, $this->name . '.highlights');

		$builder->addDefinition($this->prefix('htmlOutput'))
				->setClass('Trejjam\TexyFshl\FshlHtmlOutput');

		$builder->addDefinition($this->prefix('highlighter'))
				->setClass('FSHL\Highlighter');

		$configure = $builder->addDefinition($this->prefix('configure'))
							 ->setClass('Trejjam\TexyFshl\TexyFshlConfigure', [
								 $this->prefix('@highlighter'),
								 $config['highlights'],
							 ]);

		foreach ($config['parentExtension'] as $v) {
			$configure->addTag($v . '.configurator');
		}
	}

	private function validate(array $config, array $expected, $name) {
		if ($extra = array_diff_key($config, $expected)) {
			$extra = $name . '.' . implode(", $name.", array_keys($extra));
			throw new Nette\InvalidStateException("Unknown option $extra.");
		}
	}
}
