<?php

require_once('../../config.php');
require_once('helloblock_form.php');

global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
 
// Next look for optional variables.
$id = optional_param('id', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_helloblock', $courseid);
}

require_login($course);

$PAGE->set_url('/blocks/helloblock/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_helloblock'));

$settingsnode = $PAGE->settingsnav->add(get_string('helloblocksettings', 'block_helloblock'));
$editurl = new moodle_url('/blocks/helloblock/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('editpage', 'block_helloblock'), $editurl);
$editnode->make_active();

$helloblock = new helloblock_form();

echo $OUTPUT->header();
$helloblock->display();
echo $OUTPUT->footer();

$helloblock->display();
?>