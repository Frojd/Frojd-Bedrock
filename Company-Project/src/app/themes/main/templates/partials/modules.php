<?php
$modules = App\get_flexible_content('modules');
if(empty($modules))
    return;
?>
<div class="modules">
    <div class="modules__list">
        <?php foreach($modules as $module) : ?>
            <?php
                $name = $module['name'] ?? '';
                $template = $module['template'] ?? 'partials/' . $name;
                $data = $module['data'] ?? [];

                $class = App\array_to_modifiers([
                    $name,
                ], 'modules__item');
            ?>
            <div class="<?= $class; ?>">
                <?php App\template_part($template, $data); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
