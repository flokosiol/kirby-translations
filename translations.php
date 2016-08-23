<?php

/**
 * Translations field
 *
 * @package   Kirby CMS
 * @author    Flo Kosiol <git@flokosiol.de>
 * @link      http://flokosiol.de
 * @version   0.2
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
    if ($this->isTranslated($language)) {
      return 'check';
    }
    return 'times';
  }

  public function cssClasses($language) {
    $classes = array();

    if ($this->isTranslated($language)) {
      $classes[] = 'translated';
      
      if ($this->isUpToDate($language)) {
        $classes[] = 'uptodate';
      }
    }
    else {
      $classes[] = 'untranslated';
    }

    if ($this->page()->site()->language() == $language) {
      $classes[] = 'active';
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
    $wrapper = new Brick('div');
    $wrapper->addClass('field-translations-wrapper');

    $html = tpl::load( __DIR__ . DS . 'template.php', $data = array(
      'site' => $this->page()->site(),
      'page' => $this->page(),
      'field' => $this,
    ));

    $wrapper->append($html);
    $wrapper->append($this->input());

    return $wrapper;
  }

}