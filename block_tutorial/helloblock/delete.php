<?php

require_once('../../config.php');

// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);
 if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_helloblock', $courseid);
}

require_login($course);
require_capability('block/helloblock:managepages', context_course::instance($courseid));

if (! $helloblockpage = $DB->get_record('block_helloblock', array('id'=> $id))) {
    print_error('nopage', 'block_helloblock', '', $id);
}

$site = get_site();
$PAGE->set_url('/blocks/helloblock/view.php', array('id' => $id, 'courseid' => $courseid));
$heading = $site->fullname . ' :: ' . $course->shortname . ' :: ' . $helloblockpage->pagetitle;
$PAGE->set_heading($heading);
if (!$confirm) {
    $optionsno = new moodle_url('/course/view.php', array('id' => $courseid));
    $optionsyes = new moodle_url('/blocks/helloblock/delete.php', array('id' => $id, 'courseid' => $courseid, 'confirm' => 1, 'sesskey' => sesskey()));
    echo $OUTPUT->confirm(get_string('deletepage', 'block_helloblock', $helloblockpage->pagetitle), $optionsyes, $optionsno);
} else {
    if (confirm_sesskey()) {
        if (!$DB->delete_records('block_helloblock', array('id' => $id))) {
            print_error('deleteerror', 'block_helloblock');
        }
    } else {
        print_error('sessionerror', 'block_helloblock');
    }
    $url = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($url);
}
?>