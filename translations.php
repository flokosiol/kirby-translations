<?php

/**
 * Translations field
 *
 * @package   Kirby CMS
 * @author    Flo Kosiol <git@flokosiol.de>
 * @link      http://flokosiol.de
 * @version   0.1
 */

class translationsField extends BaseField {
	
	public static $assets = array(
	  'css' => array(
	    'translations.css',
	  ),
	);

	public function isTranslated($language) {
		$inventory = $this->page()->inventory();
		return isset($inventory['content'][$language->code()]) ? TRUE : FALSE;
	}

	public function content() {
		$html = tpl::load( __DIR__ . DS . 'template.php', $data = array(
			'site' => $this->page()->site(),
			'page' => $this->page(),
			'field' => $this,
		));
		return $html;
	}

}