<?php

/**
 * Translations plugin
 *
 * @package   Kirby CMS
 * @author    Flo Kosiol <git@flokosiol.de>
 * @link      http://flokosiol.de
 * @version   0.3
 */

$kirby->set('field', 'translations', __DIR__ . DS . 'fields' . DS . 'translations');

// Routes

if (site()->user()) {
  kirby()->routes(array(
    array(
      'pattern' => 'translations.delete/(:all)',
      'action'  => function($uri) {
        if (s::get('lang') == get('lang') ) {
          $languageCode = esc(get('lang')); // maybe no need to escape
          $page = page($uri);
          $textfile = $page->textfile(NULL, $languageCode);
          f::remove($textfile);
          $back_url = '/panel/pages/' . $page->id() . '/edit';
          go($back_url);
        }
        go(site()->errorPage());
      }
    ),
  ));
}
