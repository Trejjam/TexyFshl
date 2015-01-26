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

	function __construct(FSHL\Highlighter $highlighter, array $highlights) {
		$this->highlighter = $highlighter;
		$this->highlights = $highlights;
	}

	public function configure(Texy $texy) {
		$texy->allowedTags = Texy::ALL;
		$texy->linkModule->root = '';
		$texy->tabWidth = 4;
		$texy->phraseModule->tags['phrase/strong'] = 'b';
		$texy->phraseModule->tags['phrase/em'] = 'i';
		$texy->phraseModule->tags['phrase/em-alt'] = 'i';

		$texy->headingModule->top = 2;
		$texy->headingModule->generateID = TRUE;

		$texy->dtd['pre'][1]['ol'] = 1;

		$texy->addHandler('block', [$this, 'blockHandler']);
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