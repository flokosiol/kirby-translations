<?php

namespace daandelange\Translations;

use Kirby\Cms\App;

@include_once __DIR__ . '/vendor/autoload.php';

App::plugin('daandelange/translations', [
    'sections' => [
        'translations' => [
            'props' => [
                'deletable' => function (bool $deletable = true) {
                    return $deletable;
                },
                'revertable' => function (bool $revertable = true) {
                    return $revertable;
                },
                'portaled' => function (bool $portaled = false) {
                    return $portaled;
                },
                'compactmode' => function (bool $compactmode = false) {
                    return $compactmode;
                }
            ],
            'computed' => [
                'id' => function () {
                    return $this->model()->id();
                },
                'translations' => function () {
                    $translatedContent = [];
                    if ($translations = $this->model()->translations()) {
                        foreach ($translations as $translation) {
                            if ($translation->exists() && $translation->code() != kirby()->defaultLanguage()->code()) {
                                $translatedContent[] = $translation->code();
                            }
                        }
                    }
                    return $translatedContent;
                }
            ]
        ]
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'daandelange/translations/delete',
                'method'  => 'POST',
                'action'  => function () {
                    $id = get('id');
                    $languageCode = get('languageCode');

                    if (kirby()->defaultLanguage()->code() == $languageCode) {
                        return [
                            'code' => 403,
                            'text' => 'Default language textfile can not be deleted.'
                        ];
                    }

                    if ($page = page($id)) {
                        $fileName = $page->contentFileName($languageCode) . '.' . $languageCode . '.' . $page->contentFileExtension();
                        $filePath = $page->root() . DS .$fileName;
                        if (F::exists($filePath)) {
                            if (F::remove($filePath)) {
                                return [
                                    'code' => 200,
                                    'text' => 'Textfile ' . str::upper($languageCode) . ' deleted.'
                                ];
                            }
                            else {
                                return [
                                    'code' => 500,
                                    'text' => 'Textfile ' . $filePath . ' could not be removed.'
                                ];
                            }
                        }
                        else {
                            return [
                                'code' => 404,
                                'text' => 'Textfile ' . $filePath . ' does not exist.'
                            ];
                        }
                    }
                    return [
                        'code' => 404,
                        'text' => 'Page not found'
                    ];
                }
            ],
            [
                'pattern' => 'daandelange/translations/revert',
                'method'  => 'POST',
                'action'  => function () {
                    $id = get('id');
                    $languageCode = get('languageCode');
                    $defaultLanguageCode = kirby()->defaultLanguage()->code();

                    if ($languageCode == $defaultLanguageCode) {
                        return [
                            'code' => 403,
                            'text' => 'Default language textfile can not be deleted.'
                        ];
                    }

                    if ($page = page($id)) {
                        $data = $page->readContent($defaultLanguageCode);
                        if ($page->save($data, $languageCode, true)) {
                            return [
                                'code' => 200,
                                'text' => $languageCode . ' reverted.'
                            ];
                        }
                    }
                    return [
                        'code' => 500,
                        'text' => $languageCode . ' could not be reverted.'
                    ];
                }
            ]
        ]
    ],
    'translations' => [
        'en' => [
            'daandelange.translations.delete.confirm' => 'Do you really want to delete the content of this language ({code})?',
            'daandelange.translations.revert.confirm' => 'Do you really want to revert the content to the current state of the default language?',
        ],
        'de' => [
            'daandelange.translations.delete.confirm' => 'Möchtest Du wirklich den Inhalt dieser Sprache ({code}) löschen?',
            'daandelange.translations.revert.confirm' => 'Möchtest Du wirklich den Inhalt auf den aktuellen Stand der Standardsprache zurücksetzen?',
        ]
    ]
]);
