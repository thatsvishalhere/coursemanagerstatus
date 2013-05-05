<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir."/formslib.php");
class update_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $USER;
        $mform =& $this->_form;
        $mform->addElement('html','<div style="margin-left: -20%;">');
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_ACTION);
        $mform->addElement('static', 'updatestring', '',html_writer::start_tag('b').'Update Status:'.html_writer::end_tag('b'));
        $select = $mform->addElement('select', 'userstatus', '', array('Available'=>'Available', 'Away'=>'Away'));
        $mform->addElement('text', 'userstatuscomments', '', array('size'=>'15'));
        $mform->setType('userstatuscomments', PARAM_TEXT);
        $mform->setDefault('userstatuscomments', 'Comments....');
        $this->add_action_buttons(false, ' Update ');
        $mform->addElement('html','</div>');
    }
    function validation($data, $files) {
        return array();
    }
    public function returnhtml() {
        return $this->_form->tohtml();
    }
}