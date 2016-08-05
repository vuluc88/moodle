<?php
class block_helloblock extends block_base {
    public function init() {
        $this->title = get_string('helloblock', 'block_helloblock');
    }

    public function instance_allow_multiple() {
        return true;
    }

    function has_config() {
        return true;
    }

    /*public function hide_header() {
        return true;
    }*/

    public function html_attributes() {
        $attributes = parent::html_attributes(); // Get default values
        $attributes['class'] .= ' block_'. $this->name() .'_vuluc88'; // Append our class to class attribute
        return $attributes;
    }

    public function applicable_formats() {
        return array(
                    'site-index' => true,
                   'course-view' => true, 
            'course-view-social' => false,
                           'mod' => true, 
                      'mod-quiz' => false
        );
    }

    public function get_content() {
        if ($this->content !== null) {
          return $this->content;
        }

        global $COURSE, $DB, $PAGE;
        // Check to see if we are in editing mode
        $canmanage = $PAGE->user_is_editing($this->instance->id);

        $url = new moodle_url('/blocks/helloblock/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));

        $this->content         =  new stdClass;
        $this->content->text   = 'Hello there';
        $context = context_course::instance($COURSE->id);

        if (has_capability('block/helloblock:managepages', $context)) {
            $url = new moodle_url('/blocks/helloblock/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
            $this->content->footer = html_writer::link($url, get_string('addpage', 'block_helloblock'));
        } else {
            $this->content->footer = '';
        }

        // Check to see if we are in editing mode and that we can manage pages.
        $canmanage = has_capability('block/helloblock:managepages', $context) && $PAGE->user_is_editing($this->instance->id);
        $canview = has_capability('block/helloblock:viewpages', $context);

        if (! empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }

        if ($helloblockpages = $DB->get_records('block_helloblock', array('blockid' => $this->instance->id))) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($helloblockpages as $helloblockpage) {
                if ($canmanage) {
                    $editparam = array('blockid' => $this->instance->id, 
                          'courseid' => $COURSE->id, 
                          'id' => $helloblockpage->id);
                    $editurl = new moodle_url('/blocks/helloblock/view.php', $editparam);
                    $editpicurl = new moodle_url('/pix/t/edit.png');
                    $edit = html_writer::link($editurl, html_writer::tag('img', '', array('src' => $editpicurl, 'alt' => get_string('edit'))));
                    //delete
                    $deleteparam = array('id' => $helloblockpage->id, 'courseid' => $COURSE->id);
                    $deleteurl = new moodle_url('/blocks/helloblock/delete.php', $deleteparam);
                    $deletepicurl = new moodle_url('/pix/t/delete.png');
                    $delete = html_writer::link($deleteurl, html_writer::tag('img', '', array('src' => $deletepicurl, 'alt' => get_string('delete'))));
                } else {
                    $edit = '';
                    $delete = '';
                }
                $pageurl = new moodle_url('/blocks/helloblock/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $helloblockpage->id, 'viewpage' => true));
                $this->content->text .= html_writer::start_tag('li');
                if ($canview) {
                    $this->content->text .= html_writer::link($pageurl, $helloblockpage->pagetitle);
                } else {
                    $this->content->text .= html_writer::tag('div', $helloblockpage->pagetitle);
                }
                $this->content->text .= $edit;
                $this->content->text .= $delete;
                $this->content->text .= html_writer::end_tag('li');
            }
            $this->content->text .= html_writer::end_tag('ul');
        }

        return $this->content;
    }

    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_helloblock');
            } else {
                $this->title = $this->config->title;
            }
     
            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_helloblock');
            }    
        }
    }

    public function instance_config_save($data,$nolongerused =false) {
        if(get_config('helloblock', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text, '<b>');
        }
     
        // And now forward to the default implementation defined in the parent class
        return parent::instance_config_save($data,$nolongerused);
    }

    public function instance_delete() {
        global $DB;
        $DB->delete_records('block_helloblock', array('blockid' => $this->instance->id));
    }
}