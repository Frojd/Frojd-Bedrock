<?php
namespace App\Commands\GFGDPR;

/**
 * Remove old gravity forms submissions
 */
class GFGDPRCommand extends \WP_CLI_Command {

    /**
     * Delete or anonymize old Gravity Forms entries.
     *
     * ## OPTIONS
     *
     * [--days=<days>]
     * : Number of days old entries should be. Default 730 (2 years).
     *
     * [--dry-run]
     * : Show how many entries would be affected without making changes.
     *
     * [--delete]
     * : Delete entries instead of only clearing personal data.
     *
     * ## EXAMPLES
     *
     *     wp gf-gdpr clear --days=730
     *     wp gf-gdpr clear --days=365 --dry-run
     *     wp gf-gdpr clear --days=365 --delete
     *
     * @when after_wp_load
     */
    public function clear($args, $assocArgs) {
        if (!\class_exists('GFAPI')) {
            \WP_CLI::error("Gravity Forms not found.");
            return;
        }

        $days = isset($assocArgs['days']) ? intval($assocArgs['days']) : 730;
        if ($days <= 0) {
            \WP_CLI::error("Please provide a valid number of days.");
            return;
        }

        $dryRun = isset($assocArgs['dry-run']);
        $delete = isset($assocArgs['delete']);

        $count = 0;
        $forms = \GFAPI::get_forms();
        $endDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        foreach ($forms as $form) {
            $perPage = 200;
            $page = 1;
            do {
                $entries = \GFAPI::get_entries(
                    $form['id'],
                    [
                        'start_date' => '',
                        'end_date'   => $endDate,
                    ],
                    null,
                    [
                        'offset' => ($page - 1) * $perPage,
                        'page_size' => $perPage,
                    ]
                );
                foreach ($entries as $entry) {
                    if ($dryRun) {
                        $count++;
                        continue;
                    }

                    if ($delete) {
                        \GFAPI::delete_entry($entry['id']);
                        $count++;
                    } else {
                        $entry = $this->anonymize($entry, $form);
                        \GFAPI::update_entry($entry);
                        $count++;
                    }
                }

                $fetched = count($entries);
                $page++;

            } while ($fetched === $perPage);
        }

        if ($dryRun) {
            \WP_CLI::success("Dry run: {$count} entries would be affected (older than {$days} days).");
        } elseif ($delete) {
            \WP_CLI::success("Deleted {$count} Gravity Forms entries older than {$days} days.");
        } else {
            \WP_CLI::success("Anonymized {$count} Gravity Forms entries older than {$days} days.");
        }
    }

    private function anonymize(array $entry, array $form): array {
        // Fields that hold no free-form personal data are kept as-is.
        $keepFields = ['checkbox', 'radio', 'select', 'date', 'hidden'];
        foreach ($form['fields'] as $field) {
            $type = $field->type;
            $id   = $field->id;
            if (in_array($type, $keepFields, true)) {
                continue;
            }

            if (isset($entry[$id])) {
                $entry[$id] = '';
            }

            if ($type === 'name') {
                foreach (['1','2','3','4'] as $sub) { // first, middle, last, suffix
                    $key = $id . '.' . $sub;
                    if (isset($entry[$key])) {
                        $entry[$key] = '';
                    }
                }
            }

            if ($type === 'address') {
                foreach (['1','2','3','4','5'] as $sub) { // street1, street2, city, state, zip
                    $key = $id . '.' . $sub;
                    if (isset($entry[$key])) {
                        $entry[$key] = '';
                    }
                }
            }
        }
        return $entry;
    }
}

\WP_CLI::add_command('gf-gdpr', __NAMESPACE__ . '\\GFGDPRCommand');
