<?php

namespace App\Elasticpress;

add_filter('ep_indexable_post_types', function($postTypes) {
    // All these post types should be indexed, but contact should be excluded from global search
    return [
        'page', 'news',
    ];
});

// Skip indexing of some posts, specifically if not supposed to be indexed with Yoast SEO
function _skip_post_indexing($skip, $postId) {
    $noindex = get_post_meta($postId, '_yoast_wpseo_meta-robots-noindex', true);

    if($noindex != 1) { // Is saved as '' or 2 for indexing, and 1 for no indexing
        return $skip;
    }

    // Make sure the indexed post is deleted from index as well
    if(class_exists('\ElasticPress\Indexables')) {
        $esPost = \ElasticPress\Indexables::factory()->get('post');
        $esPost->sync_manager->action_delete_post($postId);
    }

    return true;
}

// Remove posts from index if they shouldn't be indexed by yoast
add_filter('ep_post_index_kill', __NAMESPACE__ . '\\_skip_post_indexing', 10, 2);
add_filter('ep_post_sync_kill', __NAMESPACE__ . '\\_skip_post_indexing', 10, 2);

add_action('pre_get_posts', function($query) {
    if(is_admin() || !$query->is_main_query()) {
        return;
    }

    // Activate elastic press on contact archive page
    if(is_post_type_archive('contact')) {
        $search = get_query_var('sok');
        if(!empty($search)) {
            $query->set('s', get_query_var('sok'));
            $query->set('ep_integrate', true);
        }
    } else if(is_search()) {
        $query->set('post_type', ['page', 'news']);
        $query->set('ep_integrate', true);
    }
});

// Add meta fields that should be indexed, these fields are the searchable fields
// Any fields used for meta query in search should be added here
add_filter('ep_prepared_post_meta', function($meta) {
    $include = [
        'hero_title', 'hero_text',
    ];
    $newMeta = [];
    foreach($include as $field) {
        if(isset($meta[$field])) {
            $newMeta[$field] = $meta[$field];
        }
    }
    return $newMeta;
});
