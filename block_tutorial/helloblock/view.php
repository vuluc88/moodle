<?php

require_once('../../config.php');
require_once('helloblock_form.php');
require_once($CFG->dirroot.'/blocks/helloblock/lib.php');

global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
 
// Next look for optional variables.
$id = optional_param('id', 0, PARAM_INT);
$viewpage = optional_param('viewpage', false, PARAM_BOOL);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_helloblock', $courseid);
}

require_login($course);
require_capability('block/helloblock:managepages', context_course::instance($courseid));

//load css file within block
$PAGE->requires->css('/blocks/helloblock/styles.css');
$PAGE->set_url('/blocks/helloblock/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_helloblock'));

$settingsnode = $PAGE->settingsnav->add(get_string('helloblocksettings', 'block_helloblock'));
$editurl = new moodle_url('/blocks/helloblock/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('editpage', 'block_helloblock'), $editurl);
$editnode->make_active();

$helloblock = new helloblock_form();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['id'] = $id;
$helloblock->set_data($toform);

if ($helloblock->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $id));
    redirect($courseurl);
} else if ($fromform = $helloblock->get_data()) {
    // case update page
    if ($fromform->id != 0) {
        if (!$DB->update_record('block_helloblock', $fromform)) {
            print_error('updateerror', 'block_helloblock');
        }
    } else {
        if (!$DB->insert_record('block_helloblock', $fromform)) {
            print_error('inserterror', 'block_helloblock');
        }
    }
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl);
    //print_object($fromform);
} else {
    // form didn't validate or this is the first display
    $site = get_site();
    echo $OUTPUT->header();
    if ($id) {
        $helloblockpage = $DB->get_record('block_helloblock', array('id' => $id));
        if ($viewpage){
            block_helloblock_print_page($helloblockpage);
        } else {
            $helloblock->set_data($helloblockpage);
            $helloblock->display();
        }
    } else {
        $helloblock->display();
    }
    echo $OUTPUT->footer();
}
?>