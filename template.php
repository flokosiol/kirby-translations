<ul class="cf">
  <?php foreach ($site->languages() as $language): ?>
    <li class="<?php echo $field->cssClasses($language) ?>">
      <a href="<?php echo url('panel/pages/' . $page->uri() . '/edit?language=' . $language->code()) ?>">
        <?php echo str::upper($language->code()) ?>
        <i class="fa fa-<?php echo $field->statusIcon($language); ?>"></i>
      </a>
      <i class="delete fa fa-trash"></i>
    </li>
  <?php endforeach ?>
</ul>