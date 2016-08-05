<?php
function block_helloblock_images() {
    return array(html_writer::tag('img', '', array('alt' => get_string('red', 'block_helloblock'), 'src' => "pix/pic_red.png")),
                html_writer::tag('img', '', array('alt' => get_string('blue', 'block_helloblock'), 'src' => "pix/pic_blue.png")),
                html_writer::tag('img', '', array('alt' => get_string('green', 'block_helloblock'), 'src' => "pix/pic_green.png")));
}

function block_helloblock_print_page($helloblock, $return = false) {
    global $OUTPUT, $COURSE;

    $display        = $OUTPUT->heading($helloblock->pagetitle);

    $display       .= $OUTPUT->box_start();
    if($helloblock->displaydate) {
        $display   .= html_writer::start_tag('div', array('class' => 'helloblock displaydate'));
        $display   .= userdate($helloblock->displaydate);
        $display   .= html_writer::end_tag('div');
    }
    $display       .= clean_text($helloblock->displaytext);
    //close the box
    $display       .= $OUTPUT->box_end();

    if ($helloblock->displaypicture) {
        $display .= $OUTPUT->box_start();
        $images = block_helloblock_images();
        $display .= $images[$helloblock->picture];
        $display .= html_writer::start_tag('p');
        $display .= clean_text($helloblock->description);
        $display .= html_writer::end_tag('p');
        $display .= $OUTPUT->box_end();
    }

    if($return) {
        return $display;
    } else {
        echo $display;
    }
}