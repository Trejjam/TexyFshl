<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 26.1.15
 * Time: 2:30
 */

namespace Trejjam\TexyFshl;


use Nette,
	Trejjam,
	Texy,
	FSHL;

class TexyFshlConfigure implements Texy\ITexyConfigurator
{
	/**
	 * @var FSHL\Highlighter
	 */
	private $highlighter;
	/**
	 * @var array
	 */
	private $highlights;
	/**
	 * @var array
	 */
	private $texy;
	/**
	 * @var array
	 */
	private $references;

	function __construct(FSHL\Highlighter $highlighter, array $highlights, array $texy, array $references) {
		$this->highlighter = $highlighter;
		$this->highlights = $highlights;
		$this->texy = $texy;
		$this->references = $references;
	}

	public function configure(Texy $texy) {
		$texy->setOutputMode(Texy::HTML5);

		$this->setupTexy($texy, $this->texy);

		foreach ($this->references as $k => $v) {
			$texy->linkModule->addReference($k, new \TexyLink($v));
		}

		$texy->registerLinePattern(
			array($this, 'patternPhraseTrejjam'),
			'#(?<!\')\'(?!\s)((?:[^\r \']++|[ ])+)' . \TexyPatterns::MODIFIER . '?(?<!\s)\'\|(?!\s)((?:[^\r :]++|[ ])+)??(?::(' . \TexyPatterns::LINK_URL . '))??()#Uu',
			'phrase/span-trejjam'
		);

		$texy->addHandler('block', [$this, 'blockHandler']);
	}

	public function patternPhraseTrejjam(\TexyLineParser $parser, $matches, $phrase) {
		$editMatches = $matches;

		$editMatches[1] = $editMatches[3];
		$editMatches[3] = '[' . $editMatches[4] . ']';
		$editMatches[4] = $editMatches[5];
		unset($editMatches[4]);

		$el = $parser->getTexy()->phraseModule->patternPhrase($parser, $editMatches, 'phrase/span');

		$el->setText($matches[1]);

		return $el;
	}

	protected function setupTexy(&$target, $source) {
		if (is_array($source)) {
			foreach ($source as $k => $v) {
				if (isset($target->$k)) {
					if (is_array($target->$k)) {
						if (is_array($v)) {
							$target->$k = array_merge($target->$k, $v);
						}
						else {
							$target->$k = $v;
						}
					}
					else {
						$this->setupTexy($target->$k, $v);
					}
				}
			}
		}
		else {
			$target = $source;
		}
	}

	public function blockHandler(\TexyHandlerInvocation $invocation, $blockType, $content, $lang, $modifier) {
		if (isset($this->highlights[$blockType])) {
			list(, $lang) = explode('/', $blockType);
		}
		else {
			return $invocation->proceed($blockType, $content, $lang, $modifier);
		}
		/*
				$highlighter = new FSHL\Highlighter(
					new FSHL\Output\Html(),
					FSHL\Highlighter::OPTION_TAB_INDENT | FSHL\Highlighter::OPTION_LINE_COUNTER
				);
		*/

		$texy = $invocation->getTexy();
		$content = Texy::outdent($content);

		// zvýraznění syntaxe
		if (class_exists($lexerClass = $this->highlights[$blockType])) {
			$content = $this->highlighter->highlight($content, new $lexerClass());
		}
		else {
			$content = htmlspecialchars($content);
		}

		//$content = $texy->protect($content, Texy::CONTENT_BLOCK);

		$elPre = \TexyHtml::el('pre');
		if ($modifier) {
			$modifier->decorate($texy, $elPre);
		}
		$elPre->attrs['class'] = 'src-' . strtolower($lang) . ' prettyprint linenums';

		// čísla řádků
		$elOl = $elPre->create('ol', array('class' => 'linenums'));
		foreach (Nette\Utils\Strings::split($content, '~[\n\r]~') as $i => $line) {
			$elLi = $elOl->create('li', array('class' => 'L' . $i));
			$elLi->create('span', $texy->protect($line, Texy::CONTENT_BLOCK));
		}

		//$elCode = $elPre->create('code', $content);

		return $elPre;
	}
}