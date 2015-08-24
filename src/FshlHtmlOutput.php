<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 26.1.15
 * Time: 0:32
 */

namespace Trejjam\TexyFshl;


use Nette,
	FSHL;

class FshlHtmlOutput implements FSHL\Output
{
	private $lastClass = NULL;

	public function template($part, $class) {
		$output = '';

		if ($this->lastClass !== $class) {
			if (NULL !== $this->lastClass) $output .= '</span>';
			if (NULL !== $class) $output .= '<span class="' . $class . '">';
			$this->lastClass = $class;
		}

		$part = htmlspecialchars($part, ENT_COMPAT, 'UTF-8');
		if ($this->lastClass && strpos($part, "\n") !== FALSE) {
			$endline = "</span>\n" . '<span class="' . $this->lastClass . '">';
			$part = str_replace("\n", $endline, $part);
		}

		return $output . $part;
	}
	public function keyword($part, $class) {
		$output = '';

		if ($this->lastClass !== $class) {
			if (NULL !== $this->lastClass) $output .= '</span>';
			if (NULL !== $class) $output .= '<span class="' . $class . '">';
			$this->lastClass = $class;
		}

		return $output . htmlspecialchars($part, ENT_COMPAT, 'UTF-8');
	}
}
