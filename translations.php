<?php

/**
 * Translations field
 *
 * @package   Kirby CMS
 * @author    Flo Kosiol <git@flokosiol.de>
 * @link      http://flokosiol.de
 * @version   0.1
 */

class translationsField extends CheckboxField {
	
	public static $assets = array(
	  'css' => array(
	    'translations.css',
	  ),
	);

	// Helper

	public function isTranslated($language) {
		$inventory = $this->page()->inventory();
		return isset($inventory['content'][$language->code()]) ? TRUE : FALSE;
	}

	public function isUpToDate($language) {
		$name = $this->name();
		return $this->page()->content($language->code())->$name()->value();
	}
	
	public function statusIcon($language) {
		if ($this->isUptoDate($language)) {
			return 'check';
		}

		if ($this->isTranslated($language)) {
			return 'check';
			// return 'circle-o';
		}
		return 'times';
	}

	public function cssClasses($language) {
		$classes = array();

		if ($this->isTranslated($language)) {
			$classes[] = 'translated';
		}
		else {
			$classes[] = 'untranslated';
		}

		if ($this->page()->site()->language() == $language) {
			$classes[] = 'active';
		}

		if ($this->isUpToDate($language)) {
			$classes[] = 'uptodate';
		}

		return implode(' ', $classes);
	}

	// Field setup

	public function text() {
		return 'Translation is up to date';
	}

	public function readonly() {
		return false;
	}

	public function content() {
		$html = tpl::load( __DIR__ . DS . 'template.php', $data = array(
			'site' => $this->page()->site(),
			'page' => $this->page(),
			'field' => $this,
		));
		return $html . $this->input();
	}

}