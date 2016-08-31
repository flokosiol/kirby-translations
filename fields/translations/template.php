<ul class="cf">
  <?php foreach ($site->languages() as $language): ?>
    <li class="<?php echo $field->cssClasses($language) ?>">
      <a id="language-tab-<?php echo $language->code() ?>" class="language-tab" href="?language=<?php echo $language->code() ?>">
        <?php echo str::upper($language->code()) ?>
        <i class="fa fa-<?php echo $field->statusIcon($language); ?>"></i>
      </as>
      <span class="delete">
        <a class="translations-delete" href="#">
          <i class="fa fa-trash"></i>
        </a>
        <span class="translations-delete-confirm">
          <a class="translations-delete-confirm-btn btn btn-rounded btn-submit btn-negative" href="#" data-url="<?php echo url('translations.ajax.delete/') ?>" data-id="<?php echo $page->id() ?>" data-language="<?php echo $language->code() ?>">Delete <?php echo str::upper($language->code()) ?></a>
          <a class="translations-delete-cancel-btn btn btn-rounded btn-submit" href="#">Cancel</a>
          
        </span>
      </span>
    </li>
  <?php endforeach ?>
</ul>