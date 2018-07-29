<?php

/**
 * Translations field
 *
 * @package   Translations Plugin
 * @author    Flo Kosiol <git@flokosiol.de>
 * @link      http://flokosiol.de
 * @version   0.7
 */

class translationsField extends CheckboxField {

  public function __construct() {
    $this->icon = false;
    $this->deletable = true;
    $this->updatable = true;
    $this->uptodate = true;
  }

  public static $assets = array(
    'css' => array(
      'translations.css',
    ),
    'js' => array(
      'translations.js',
    ),
  );

  /**
   * Set field property and default value if required
   *
   * @param string $option
   * @param mixed  $value
   */
  public function __set($option, $value) {

    $this->$option = $value;

    switch ($option) {
      case 'uptodate':
        if (!is_bool($value))
          $this->uptodate = true;
        break;
      case 'deletable':
        if (!is_bool($value))
          $this->deletable = true;
        break;
    }
  }

  public function translate($string)
  {
      $translations = require __DIR__ . DS . 'languages.php';
      $custom_translations = c::get('translations.translation', []);
      $language = site()->user()->data()['language'];

      array_walk($translations, function (&$default, $lang, $custom) {
          if (!empty($custom) && array_key_exists($lang, $custom)) {
              $default = array_merge($default, $custom[$lang]);
          }
      }, $custom_translations);

      $language = (array_key_exists($language, $translations))
        ? $language
        : 'en';

      if (array_key_exists($string, $translations[$language])) {
          $string = $translations[$language][$string];
      }

      return $string;
  }

  public function text() {
    return l::get('translations.uptodate.text', $this->translate('lbl_is_up_to_date'));
  }

  public function readonly() {
    return false;
  }

  // Helper

  public function isTranslated($language) {
    $inventory = $this->page()->inventory();
    return isset($inventory['content'][$language->code()]) ? true : false;
  }


  public function isUpToDate($language) {
    // if uptodate is disabled always return '1' to display a green checkmark
    if (!$this->uptodate) {
      return '1';
    }

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

    if ($this->page()->site()->defaultLanguage()->code() == $language->code()) {
      $classes[] = 'default';
    }

    return implode(' ', $classes);
  }

  // Defines the panel language url argument, which changed with version 2.5.8
  public function languageArgument() {
    $version = intval(str_replace('.', '', panel()->version()));
    if ($version <= 257) {
      return 'language';
    }
    return '_language';
  }

  // Content

  public function content() {
    $wrapper = new Brick('div');
    $wrapper->addClass('field-translations-wrapper');

    $html = tpl::load( __DIR__ . DS . 'template.php', $data = array(
      'site' => $this->page()->site(),
      'page' => $this->page(),
      'field' => $this,
    ));

    $wrapper->append($html);

    // display checkbox if uptodate isn't disabled via blueprint
    if ($this->uptodate) {
      $wrapper->append($this->input());
    }

    return $wrapper;
  }

}