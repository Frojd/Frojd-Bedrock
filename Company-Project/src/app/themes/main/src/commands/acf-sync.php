<?php
namespace App\Commands\ACFSync;

/*
    // Clear all synced field groups in database

    acf-sync clear
*/

class ACFSyncCommand extends \WP_CLI_Command {
    /**
     * Run acf sync commands
     *
     * ## OPTIONS
     * [--dry-run]
     * : Only dryrun
     *
     * [--network]
     * : Run for all sites
     *
     * ## EXAMPLES
     *
     *     `wp acf-sync clear`
     *
     *     `wp acf-sync clear --network`
     *
     */
    public function clear($args, $assocArgs) {
        // Start by setting global variable for checking if wp cli
        global $is_wp_cli;
        $is_wp_cli = true;

        $dryRun = isset($assocArgs['dry-run']);
        $network = isset($assocArgs['network']);

        \WP_CLI::log("Clean up synced field groups");

        try {
            if($network && is_multisite()) {
                $sites = get_sites(['number' => 500]);
                foreach ( $sites as $site ) {
                    switch_to_blog($site->blog_id);

                    \WP_CLI::log("Migrate blog {$site->path}");
                    $this->clean_acf($dryRun);

                    restore_current_blog();
                }
            } else {
                $this->clean_acf($dryRun);
            }

            \WP_CLI::success("Clean up has finished");
        } catch(\Exception $e) {
            \WP_CLI::error("Clean up failed: " . $e->get_message());
        }
    }

    private function clean_acf($dryRun = false) {
        $query = new \WP_Query([
            'posts_per_page' => -1,
            'post_type' => 'acf-field-group',
        ]);

        \WP_CLI::log("Found {$query->found_posts} field groups to clear");

        foreach($query->posts as $post) {
            $postId = $post->ID;
            if($dryRun) {
                \WP_CLI::log("Found field group {$post->post_title}");
            } else {
                wp_delete_post($postId);
                \WP_CLI::log("Deleted field group {$post->post_title}");
            }
        }
    }
}

\WP_CLI::add_command('acf-sync', __NAMESPACE__ . '\\ACFSyncCommand');
