<?php

namespace App\Walkers\Nav;

class Nav extends \Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $class = isset($args->menu_class) ? $args->menu_class : 'menu';
        $class = isset($args->class) ? $args->class : $class;
        $output .= "\n$indent<ul class=\"" . $class . "__children\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        if (is_array($args)) {
            $args = (object) $args;
        }

        $class = isset($args->menu_class) ? $args->menu_class : 'menu';
        $class = isset($args->class) ? $args->class : $class;

        $classes = array('item');

        $children = false;

        if(is_array($item->classes)) {
            if(in_array('current_page_item', $item->classes) || in_array('current-menu-item', $item->classes)) {
                $classes[] = 'item--current';
            }
            if(in_array('current-page-ancestor', $item->classes) || in_array('current-menu-ancestor', $item->classes)) {
                $classes[] = 'item--ancestor';
            }
            if(in_array('current-page-parent', $item->classes) || in_array('current-menu-parent', $item->classes)) {
                $classes[] = 'item--parent';
            }
            if(in_array('menu-item-has-children', $item->classes)) {
                $classes[] = 'item--has-children';
                $children = true;
            }
        }

        $hide = get_field('hide_in_menu', $item->object_id);
        if($hide) {
            $classes[] = 'item--hidden';
        } else {
            $classes[] = 'item--visible';
        }

        $classNames = preg_filter('/^/', $class . '__', $classes);
        $classNames = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter($classNames), $item)));

        // build html
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $classNames . '">';

        // link attributes
        $attributes = ' class="' . $class . '__link' . ($children ? ' js-toggle-children' : '') . '"';
        $tag = 'span';

        if($children) {
            $tag = 'a';
        }

        if(!empty($item->url)) {
            $tag = 'a';
            $attributes .= !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"'  : '';
            $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"'      : '';
            $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"'         : '';
            $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"'         : '';
        }

        $content = '<span class="' . $class . '__title">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';

        if($item->post_content && !empty($item->post_content)) {
            $content .= '<span class="' . $class . '__content">' . nl2br($item->post_content) . '</span>';
        }

        $item_output = sprintf('%1$s<%2$s%3$s>%4$s%5$s%6$s</%2$s>%7$s',
            $args->before,
            $tag,
            $attributes,
            $args->link_before,
            $content,
            $args->link_after,
            $args->after
        );

        // build html
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

    }
}
