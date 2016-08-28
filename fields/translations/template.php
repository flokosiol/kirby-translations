<ul class="cf">
  <?php foreach ($site->languages() as $language): ?>
    <li class="<?php echo $field->cssClasses($language) ?>">
      <a id="language-tab-<?php echo $language->code() ?>" class="language-tab" href="?language=<?php echo $language->code() ?>">
        <?php echo str::upper($language->code()) ?>
        <i class="fa fa-<?php echo $field->statusIcon($language); ?>"></i>
      </a>
      <a class="translations-delete" href="#" data-url="<?php echo url('translations.ajax.delete/') ?>" data-id="<?php echo $page->id() ?>" data-language="<?php echo $language->code() ?>">
        <i class="delete fa fa-trash"></i>
      </a>
    </li>
  <?php endforeach ?>
</ul>