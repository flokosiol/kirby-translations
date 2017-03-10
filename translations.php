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
$kirby->set('widget', 'translations', __DIR__ . DS . 'widgets' . DS . 'translations');

// Routes

if (class_exists('Panel') && site()->user() && site()->user()->hasPanelAccess()) {
  panel()->routes(array(
    array(
      'pattern' => 'plugin.translations/(:any)/(:all)',
      'action' => function($language, $id) {
        $page = page($id);
        if (f::remove($page->textfile(NULL, $language))) {
          panel()->notify(strtoupper($language) . ' deleted');
        }
        else {
          panel()->alert('Translation could not be deleted');
        }
        panel()->redirect('pages/'. $id . '/edit');
      }
    )
  ));
}