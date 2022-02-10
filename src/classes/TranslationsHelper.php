<?php
declare(strict_types=1); // Dont mix types

namespace daandelange\Translations;

//use Kirby\Cms\Page;
use Kirby\Cms\ModelWithContent;


class TranslationsHelper {

    // Parses translation statuses from a page
    // Returns an array with the translation status for a lang and null if the lang doesn't exist, or an array of all langs.
    public static function getContentTranslationStatuses(ModelWithContent $model, $langCode=null) : ?array {
        $translatedContent = [];
        $translations = $langCode ? [$model->translation($langCode)] : $model->translations();
        if ($translations) {
            foreach ($translations as $translation) {
                $translatedContent[$translation->code()]['code']=$translation->code();
                if ($translation && $translation->exists()){// && $translation->code() != $model->kirby()->defaultLanguage()->code()) {
                    //$translatedContent[] = $translation->code();
                    $translatedContent[$translation->code()]['file'] = true;
                }
                else {
                    $translatedContent[$translation->code()]['file'] = false;
                }
                $translatedContent[$translation->code()]['needsreview'] = false;//notyet
            }
        }
        if($langCode){
            return $translatedContent[$translation->code()]??null;
        }
        return $translatedContent;
    }
}
