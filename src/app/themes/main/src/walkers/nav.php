<?php

namespace App\Walkers\Nav;

class Nav extends \Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $class = isset($args['menu_class']) ? $args['menu_class'] : 'menu';
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

        if(is_array($item->classes)) {
            $active = false;
            if(in_array('current_page_item', $item->classes) || in_array('current-menu-item', $item->classes)) {
                $classes[] = 'item--current';
                $active = true;
            }
            if(in_array('current-page-ancestor', $item->classes) || in_array('current-menu-ancestor', $item->classes)) {
                $classes[] = 'item--ancestor';
                $active = true;
            }
            if(in_array('current-page-parent', $item->classes) || in_array('current-menu-parent', $item->classes)) {
                $classes[] = 'item--parent';
                $active = true;
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
        $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"'  : '';
        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"'      : '';
        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"'         : '';
        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"'         : '';
        $attributes .= ' class="' . $class . '__link"';

        $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
            $args->before,
            $attributes,
            $args->link_before,
            apply_filters('the_title', $item->title, $item->ID),
            $args->link_after,
            $args->after
        );

        // build html
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

    }
}
