<div id="<?php echo $field->id(); ?>" class="field-translations-wrapper">
  <ul class="cf">
    <?php foreach ($site->languages() as $language): ?>
      <li class="<?php e($field->isTranslated($language),'translated','untranslated'); ?><?php e($site->language() == $language, ' active') ?>">
        <a href="?language=<?php echo $language->code() ?>">
          <?php echo str::upper($language->code()) ?>
          <i class="fa fa-<?php e($field->isTranslated($language),'check','times'); ?>"></i>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
</div>
