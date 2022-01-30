<?php

Kirby::plugin('flokosiol/translations', [
    'sections' => [
        'translations' => [
            'props' => [
                'deletable' => function (bool $deletable = true) {
                    return $deletable;
                },
                'revertable' => function (bool $revertable = true) {
                    return $revertable;
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
                'pattern' => 'flokosiol/translations/delete',
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

                    // $id is null, if translation field ist used in site.yml
                    $entity = page($id) ?? site();

                    if (isset($entity)) {
                        $fileName = $entity->contentFileName($languageCode) . '.' . $languageCode . '.' . $entity->contentFileExtension();
                        $filePath = $entity->root() . DS .$fileName;
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
                'pattern' => 'flokosiol/translations/revert',
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

                    // $id is null, if translation field ist used in site.yml
                    $entity = page($id) ?? site();

                    if (isset($entity)) {
                        $data = $entity->readContent($defaultLanguageCode);
                        if ($entity->save($data, $languageCode, true)) {
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
            'flokosiol.translations.delete.confirm' => 'Do you really want to delete the content of this language ({code})?',
            'flokosiol.translations.revert.confirm' => 'Do you really want to revert the content to the current state of the default language?',
        ],
        'de' => [
            'flokosiol.translations.delete.confirm' => 'Möchtest Du wirklich den Inhalt dieser Sprache ({code}) löschen?',
            'flokosiol.translations.revert.confirm' => 'Möchtest Du wirklich den Inhalt auf den aktuellen Stand der Standardsprache zurücksetzen?',
        ]
    ]
]);
