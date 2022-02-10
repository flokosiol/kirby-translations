<?php

namespace daandelange\Translations;

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App;
use I18n;
use daandelange\Translations\TranslationsHelper as Th;
use \Kirby\Toolkit\F;
use \Kirby\Toolkit\Str;

App::plugin('daandelange/translations', [
    'options' => [
        'header' => [
            'replaceKirbyLanguages' => true,
            'compactMode' => true,
            'delete' => true,
            'revert' => true,
        ],

    ],
    'sections' => [
        'translations' => [
            'props' => [
                'deletable' => function (bool $deletable = true) {
                    return $deletable;
                },
                'revertable' => function (bool $revertable = true) {
                    return $revertable;
                },
                'compactmode' => function (bool $compactmode = false) {
                    return $compactmode;
                },
                'label' => function (?string $label = null) {
                    return $label;
                }
            ],
            'computed' => [
                'id' => function () {
                    return $this->model()->id();
                },
                'translations' => function () {
                    return array_values(Th::getContentTranslationStatuses($this->model()));
                },
                'options' => function(){
                    return $this->kirby()->option('daandelange.translations');
                },
            ]
        ]
    ],
    'api' => [
        'routes' => function (\Kirby\Cms\App $kirby) {
            return [
                [
                    'pattern' => 'plugin-translations/delete',
                    'method'  => 'POST',
                    'action'  => function () use ($kirby) {
                        $id = get('id');
                        $languageCode = get('languageCode');

                        // Protect default lang
                        if ($kirby->defaultLanguage()->code() === $languageCode) {
                            return [
                                'code' => 403,
                                'text' => t('daandelange.translations.delete.nodefault', 'The default language content can not be deleted.'),
                            ];
                        }

                        // Parse id from $view.model
                        if(strpos($id, '/pages/')===0){
                            $id=substr($id, strlen('/pages/'));

                            // Fetch page object
                            if ($page = $kirby->page($id)) {
                                // Note: See /Cms/ModelWithContent::contentFile for a reference.
                                //$fileName = $page->contentFileName($languageCode) . '.' . $languageCode . '.' . $page->contentFileExtension();
                                //$filePath = $page->root() . DS .$fileName;
                                $filePath = $page->contentFile($languageCode, true);
                                if (F::exists($filePath)) {
                                    if (F::remove($filePath)) {
                                        return [
                                            'code' => 200,
                                            'text' => tt('daandelange.translations.delete.success', null, ['code'=>Str::upper($languageCode)]),
                                        ];
                                    }
                                    else {
                                        return [
                                            'code' => 500,
                                            'text' => tt('daandelange.translations.delete.error', null, ['code'=>Str::upper($languageCode)]),
                                        ];
                                    }
                                }
                                else {
                                    return [
                                        'code' => 200, // Already deleted = no error
                                        'text' => tt('daandelange.translations.page.notranslation', null, ['code'=>Str::upper($languageCode)]),
                                    ];
                                }
                            }
                        }
                        return [
                            'code' => 404, //'Page not found'
                            'text' => tt('daandelange.translations.page.notfound', null, ['page'=>$id]),
                        ];
                    }
                ],
                [
                    'pattern' => 'plugin-translations/revert',
                    'method'  => 'POST',
                    'action'  => function () use ($kirby) {
                        $id = get('id');
                        $languageCode = get('languageCode');

                        // Protect default lang
                        if ($kirby->defaultLanguage()->code() === $languageCode) {
                            return [
                                'code' => 403,
                                'text' => t('daandelange.translations.revert.nodefault', 'The default language content can not be reverted.'),
                            ];
                        }

                        // Parse id from $view.model
                        if(strpos($id, '/pages/')===0){
                            $id=substr($id, strlen('/pages/'));

                            $page = $kirby->page($id);
                            if ( $page && $page->exists() ) {
                                $data = $page->readContent($kirby->defaultLanguage()->code());
                                if ($page->save($data, $languageCode, true)) {
                                    return [
                                        'code' => 200,
                                        'text' => tt('daandelange.translations.revert.success', null, ['code'=>Str::upper($languageCode)]),
                                    ];
                                }
                                return [
                                    'code' => 500,
                                    'text' => tt('daandelange.translations.revert.error', null, ['code'=>Str::upper($languageCode)]),
                                ];
                            }
                        }
                        return [
                            'code' => 404,
                            'text' => tt('daandelange.translations.page.notfound', null, ['page'=>$id]),
                        ];

                        // Optional: return $this->next(); to let routing continue;
                    }
                ],
                [ // Fallback, minimal info (called when no model found)
                    'pattern' => 'plugin-translations/load-header',
                    'method'  => 'GET',
                    'action'  => function () use ($kirby) {
                        return [
                            'options' => $kirby->option('daandelange.translations'),
                            'translations' => null, // How to get the page object here? Not possible ?
                        ];
                    }
                ],
                [ // Virtual section for page models, see : /config/api/routes/pages.php
                    'pattern' => 'pages/(:any)/translations-info',
                    'method'  => 'GET',
                    'action'  => function (string $id) use ($kirby) {
                        if ($page = $this->page($id)) {
                            return [
                                'options' => $kirby->option('daandelange.translations'),
                                'translations' => array_values(Th::getContentTranslationStatuses($page)),
                            ];
                        }
                        return false;
                    }
                ],
                [ // Virtual section for file models, see : /config/api/routes/files.php
                    'pattern' => '(account|pages/[^/]+|site|users/[^/]+)'.'/files/(:any)/translations-info',
                    'method'  => 'GET',
                    'action'  => function (string $path, string $filename, string $sectionName) use ($kirby) {
                        if ($model = $this->file($path, $filename)->model()) {
                            return [
                                'options' => $kirby->option('daandelange.translations'),
                                'translations' => array_values(Th::getContentTranslationStatuses($model)),
                            ];
                        }
                        return false;
                    }
                ],
            ];
        },
    ],
    'translations' => [
        'en' => [
            'daandelange.translations.delete.confirm'       => 'Do you really want to delete the content of this language ({code})?',
            'daandelange.translations.delete.nodefault'     => 'The default language content can not be deleted.',
            'daandelange.translations.delete.error'         => 'The language {{code}} could not be deleted.',
            'daandelange.translations.delete.success'       => 'The language {{code}} has been successfully deleted.',
            'daandelange.translations.revert.confirm'       => 'Do you really want to revert the content to the current state of the default language?',
            'daandelange.translations.revert.nodefault'     => 'The default language content can not be reverted.',
            'daandelange.translations.revert.error'         => 'The language {{code}} could not be reverted.',
            'daandelange.translations.revert.success'       => 'The language {{code}} has been successfully reverted.',
            'daandelange.translations.page.notranslation'   => 'This page is not translated in {{code}} !',
            'daandelange.translations.page.notfound'        => 'The page {{page}} doesn\'t exist !',
            'daandelange.translations.loading'              => I18n::translate('loading', 'Loading', 'en').'...',
            'daandelange.translations.default'              => 'Default',
            'daandelange.translations.current'              => 'Current',
        ],
        'de' => [
            'daandelange.translations.delete.confirm' => 'Möchtest Du wirklich den Inhalt dieser Sprache ({code}) löschen?',
            'daandelange.translations.revert.confirm' => 'Möchtest Du wirklich den Inhalt auf den aktuellen Stand der Standardsprache zurücksetzen?',
            'daandelange.translations.loading'        => I18n::translate('loading', 'Loading', 'de').'...',
        ],
        'fr' => [
            'daandelange.translations.delete.confirm' => 'Voulez-vous vraiment supprimer l\'intégrité du contenu de cette traduction ({code}) ?',
            'daandelange.translations.revert.confirm' => 'Voulez-vous vraiment rétablir cette traduction en utilisant le contenu la langue par défaut ?',
            'daandelange.translations.loading'        => I18n::translate('loading', 'Loading', 'fr').'...',
        ]
    ],
]);
