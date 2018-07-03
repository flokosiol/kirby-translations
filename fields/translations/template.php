<ul class="cf">
  <?php foreach ($site->languages() as $language): ?>
    <li class="<?php echo $field->cssClasses($language) ?>">
      <a id="language-tab-<?php echo $language->code() ?>" class="language-tab" href="?<?php echo $field->languageArgument() ?>=<?php echo $language->code() ?>">
        <?php echo str::upper($language->code()) ?>
        <i class="fa fa-<?php echo $field->statusIcon($language); ?>"></i>
      </a>

	<?php if ($field->updatable): ?>
		<span class="update">
	          <a class="translations-update" href="#">
	            <i class="fa fa-refresh"></i>
	          </a>
	          <span class="translations-update-confirm">
	            <a class="translations-update-confirm-btn btn btn-rounded btn-submit btn-negative" href="<?php echo panel()->urls()->index() ?>/plugin-translations-update/<?php echo $language->code() ?>/<?php echo $page->id() ?>"><?= $field->translate('sync') . ' ' . str::upper($language->code()) . ' ' . $field->translate('with') . ' ' .  str::upper($site->defaultLanguage()->code()) ?></a>
	            <a class="translations-update-cancel-btn btn btn-rounded btn-submit" href="#"><?= $field->translate('btn_cancel') ?></a>
	            <p class="translations-update-alert"><?= $field->translate('alert_update') ?></p>
	          </span>
	    </span>
	<?php endif; ?>

      <?php if ($field->deletable): ?>
        <span class="delete">
          <a class="translations-delete" href="#">
            <i class="fa fa-trash"></i>
          </a>
          <span class="translations-delete-confirm">
            <a class="translations-delete-confirm-btn btn btn-rounded btn-submit btn-negative" href="<?php echo panel()->urls()->index() ?>/plugin-translations-delete/<?php echo $language->code() ?>/<?php echo $page->id() ?>"><?= $field->translate('btn_delete') ?> <?php echo str::upper($language->code()) ?></a>
            <a class="translations-delete-cancel-btn btn btn-rounded btn-submit" href="#"><?= $field->translate('btn_cancel') ?></a>
          </span>
        </span>
      <?php endif; ?>
    </li>
  <?php endforeach ?>
</ul>