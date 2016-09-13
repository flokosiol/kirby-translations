<?php

/**
 * Translations plugin
 *
 * @package   Kirby CMS
 * @author    Flo Kosiol <git@flokosiol.de>
 * @link      http://flokosiol.de
 * @version   0.4
 */

$kirby->set('field', 'translations', __DIR__ . DS . 'fields' . DS . 'translations');


// Routes

if (site()->user()) {
  kirby()->routes(array(
    array(
      'pattern' => 'translations.ajax.delete',
      'action'  => function() {
        if (r::ajax()) {
          $id = get('id');
          $language = get('language');
          $page = page($id);

          f::remove($page->textfile(NULL, $language));

          return response::json(array(
            'success' => true,
            'language' => $language,
          ));
        }
        else {
          go(site()->errorPage());
        }
      }
    ),
  ));
}

