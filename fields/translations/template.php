<ul class="cf">
  <?php foreach ($site->languages() as $language): ?>
    <li class="<?php echo $field->cssClasses($language) ?>">
      <a id="language-tab-<?php echo $language->code() ?>" class="language-tab" href="?language=<?php echo $language->code() ?>">
        <?php echo str::upper($language->code()) ?>
        <i class="fa fa-<?php echo $field->statusIcon($language); ?>"></i>
      </a>

      <?php if ($field->deletable): ?>
        <span class="delete">
          <a class="translations-delete" href="#">
            <i class="fa fa-trash"></i>
          </a>
          <span class="translations-delete-confirm">
            <a class="translations-delete-confirm-btn btn btn-rounded btn-submit btn-negative" href="<?php echo panel()->urls()->index() ?>/plugin.translations/<?php echo $language->code() ?>/<?php echo $page->id() ?>">Delete <?php echo str::upper($language->code()) ?></a>
            <a class="translations-delete-cancel-btn btn btn-rounded btn-submit" href="#">Cancel</a>
          </span>
        </span>
      <?php endif; ?>
    </li>
  <?php endforeach ?>
</ul>