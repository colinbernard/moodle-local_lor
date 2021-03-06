<?php

use local_lor\item\item;
use local_lor\page;

require_once(__DIR__ . '/../../../config.php');

$itemid  = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', false, PARAM_BOOL);

$item = item::get($itemid);

$page_url   = new moodle_url('/local/lor/item/delete.php', ['id' => $itemid]);
$return_url = new moodle_url('/local/lor/index.php');

page::set_up(
    $page_url,
    get_string('delete_title', 'local_lor'),
    get_string('delete_heading', 'local_lor', $item->name)
);

$PAGE->navbar->add(get_string('lor_page', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('delete_title', 'local_lor'), $page_url);

require_login();
require_capability('local/lor:manage', context_system::instance());

$renderer = page::get_renderer();

$form = item::get_form($item->type, $itemid);

if ($confirm) {
    if ( ! confirm_sesskey()) {
        print_error('error_sesskey', 'local_lor', $return_url);
    }

    if ( ! item::delete($itemid)) {
        print_error('delete_error', 'local_lor', $return_url);
    }

    redirect($return_url, get_string('delete_success', 'local_lor'));
} else {
    echo $renderer->header();

    $options = [
        'sesskey' => sesskey(),
        'id'      => $itemid,
        'confirm' => true,
    ];

    echo $renderer->confirm(
        get_string('delete_confirm', 'local_lor', format_string($item->name)),
        new moodle_url('/local/lor/item/delete.php', $options),
        $return_url
    );

    echo $renderer->footer();
}
