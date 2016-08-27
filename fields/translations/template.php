<ul class="cf">
  <?php foreach ($site->languages() as $language): ?>
    <li class="<?php echo $field->cssClasses($language) ?>">
      <a class="language-tab" href="?language=<?php echo $language->code() ?>">
        <?php echo str::upper($language->code()) ?>
        <i class="fa fa-<?php echo $field->statusIcon($language); ?>"></i>
      </a>
      <a href="<?php echo url('/translations.delete/' . $page->uri() . '?lang=' . $language->code()) ?>" onclick="return confirm('Delete this translation?')">
        <i class="delete fa fa-trash"></i>
      </a>
    </li>
  <?php endforeach ?>
</ul>

<?php #var_dump($page->textfile(NULL, 'de')); ?>
<?php # $field->deleteTranslation($site->language()->code()) ?>