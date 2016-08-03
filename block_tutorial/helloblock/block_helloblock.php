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
                       'all' => true,
               'course-view' => true, 
        'course-view-social' => false
        );
    }

    public function get_content() {
        if ($this->content !== null) {
          return $this->content;
        }

        global $COURSE;
        $url = new moodle_url('/blocks/helloblock/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));

        $this->content         =  new stdClass;
        $this->content->text   = 'Hello there';
        $this->content->footer = html_writer::link($url, get_string('addpage', 'block_helloblock'));
        if (! empty($this->config->text)) {
            $this->content->text = $this->config->text;
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
}