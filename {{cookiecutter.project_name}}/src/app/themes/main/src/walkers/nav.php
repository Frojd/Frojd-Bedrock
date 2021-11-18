<?php
namespace App\Walkers\Nav;

/*
 * Support for more params than the default in Walker_Nav_Menu, includes:
 * - class, string, default "menu", (for defining classname to be used with BEM)
 * - toggle_children, boolean, default true, (for adding class to be able to toggle children in menu)
 * - show_content, boolean, default true, (for adding item content to menu)
 * - page_menu, boolean, default false, (when using warker for wp_list_pages)
 */

function start_el($treeType, $item, $depth = 0, $args = [], $id = 0) {
    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    if (is_array($args))
        $args = (object) $args;

    $isPageMenu = $treeType == 'page';

    $before = $args->before ?? '';
    $after = $args->after ?? '';
    $linkBefore = $args->link_before ?? '';
    $linkAfter = $args->link_after ?? '';

    // New params
    $toggleChildren = $args->toggle_children ?? true;
    $showContent = $args->show_content ?? true;
    $class = $args->class ?? '';

    $class = $class ?: ($args->menu_class ?? '');
    $class = $class ?: 'menu';

    $menuId = $args->menu_id ?? '';
    $themeLocation = $args->theme_location ?? '';
    $listItemId = $args->item_id ?? '';
    $listItemId = $listItemId ?: $menuId;
    $listItemId = $listItemId ?: $themeLocation;
    $listItemId = $listItemId ?: $class;
    $listItemId = "{$listItemId}-{$item->ID}";

    $modifiers = [];
    $hasChildren = false;
    if(is_array($item->classes)) {
        foreach($item->classes as $classname) {
            if(in_array($classname, ['current_page_item', 'current-menu-item'])) {
                $modifiers[] = 'current';
            } elseif(in_array($classname, ['current-page-ancestor', 'current-menu-ancestor'])) {
                $modifiers[] = 'ancestor';
            } elseif(in_array($classname, ['current-page-parent', 'current-menu-parent'])) {
                $modifiers[] = 'parent';
            } elseif($classname == 'menu-item-has-children') {
                $modifiers[] = 'has-children';
                $hasChildren = true;
            } elseif(
                !empty($classname) &&
                strpos($classname, 'menu-item') === false &&
                strpos($classname, 'page-item') === false &&
                strpos($classname, 'page_item') === false
            ) {
                $modifiers[] = $classname; // Any custom classes
            }
        }
    } else {
        if($item->ID == $id) {
            $modifiers[] = 'current';
        }
    }

    $hide = get_field('hide_in_menu', $item->object_id);
    $modifiers[] = $hide === true ? 'hidden' : 'visible';

    $classNames = array_map(function($m) use ($class) {
        return "{$class}__item--{$m}";
    }, array_unique(array_filter($modifiers)));
    $classNames = array_merge(["{$class}__item"], $classNames);
    $itemClasses = esc_attr(implode(' ', apply_filters('nav_menu_css_class', $classNames, $item)));

    $tag = $hasChildren && $toggleChildren ? 'button' : 'span';

    // link attributes
    $url = $item->url ?? '';
    if($isPageMenu && empty($url))
        $url = get_permalink($item->ID);
    $isLink = !empty($url);
    $linkModifiers = [
        $isLink ? 'is-link' : 'no-link',
        $hasChildren && $toggleChildren ? 'is-button' : '',
    ];
    $attributes = [];
    if($isLink) {
        $tag = 'a';
        $attributes = [
            'href' => $url,
            'rel' => $item->rel ?? '',
            'title' => $item->attr_title ?? '',
            'target' => $item->target ?? '',
        ];
    }

    if($isPageMenu)
        $attributes = apply_filters('page_menu_link_attributes', $attributes, $item, $depth, $args, $id);

    $linkClasses = \App\array_to_modifiers($linkModifiers, "{$class}__link");
    $linkClasses .= $hasChildren && $toggleChildren ? ' js-toggle-children' : '';
    $attributes['class'] = $linkClasses;

    $title = $item->title ?? '';
    $title = $title ?: ($item->post_title ?? '');
    $title = apply_filters('the_title', $title, $item->ID);
    $title = "<span class=\"{$class}__title\">{$title}</span>";

    $content = '';
    $itemContent = $item->description ?? '';
    if(!empty($itemContent) && $showContent) {
        $content = nl2br($itemContent);
        $content = "<span class=\"{$class}__textc\">{$content}</span>";
    }

    $linkContent = $linkBefore . $title . $content . $linkAfter;
    $link = sprintf('%1$s<%2$s%3$s>%4$s</%2$s>%5$s',
        $before,
        $tag,
        \App\array_to_attributes($attributes),
        $linkContent,
        $after,
    );

    $itemStart = "<li id=\"{$listItemId}\" class=\"{$itemClasses}\">";
    $itemContent = apply_filters('walker_nav_menu_start_el', $link, $item, $depth, $args);

    // build html
    return $indent . $itemStart . $itemContent;
}

class Nav extends \Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = []) {
        $indent = str_repeat("\t", $depth);
        $class = $args->class ?? '';
        $class = $class ?: ($args->menu_class ?? '');
        $class = $class ?: 'menu';
        $output .= "\n$indent<ul class=\"{$class}__children\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $output .= start_el($this->tree_type, $item, $depth, $args, $id);
    }
}

class Page extends \Walker_Page {
    public $tree_type = 'page';

    public $db_fields = array(
        'parent' => 'post_parent',
        'id'     => 'ID',
    );

    public function start_lvl(&$output, $depth = 0, $args = []) {
        $indent = str_repeat("\t", $depth);
        $class = $args['class'] ?? '';
        $class = $class ?: ($args['menu_class'] ?? '');
        $class = $class ?: 'menu';
        $output .= "\n$indent<ul class=\"{$class}__children\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $output .= start_el($this->tree_type, $item, $depth, $args, $id);
    }
}
