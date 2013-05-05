<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * This file contains the renderers for the coursemanagerstatus plugin.
 *
 * @package    block
 * @author     Vishal Raheja - thatsvishalhere@gmail.com
 * @copyright  2013 Vishal Raheja
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once('../config.php');
require_once('update_form.php');
class block_coursemanagerstatus_renderer extends plugin_renderer_base {
    // Get my status
    public function mystatus() {
        global $USER, $COURSE, $CFG, $PAGE, $DB;
        $courses=get_courses();
        $content = new stdClass();
        $content->text = NULL;
        $content->managercourseid = NULL;
        foreach ($courses as $course) {
            $context = context_course::instance($course->id);
            // To allow the users who have editing rights on any of the courses to update their status.
            if (has_capability('moodle/course:manageactivities', $context, $USER->id)) {
                $content->managercourseid = $course->id;
                $dbrecord = $DB->get_record('block_coursemanagerstatus', array('userid'=>"$USER->id"));
                $content->text.=html_writer::start_tag('b').get_string('current_status', 'block_coursemanagerstatus').html_writer::end_tag('b');
                if ($dbrecord) {
                    $content->text.= $dbrecord->status;
                    if ($dbrecord->comments!=null)
                        $content->text.= html_writer::empty_tag('br', array()).html_writer::start_tag('b').get_string('comments', 'block_coursemanagerstatus').html_writer::end_tag('b').
                            $dbrecord->comments;
                } else {
                    $content->text.= get_string('notset', 'block_coursemanagerstatus');
                }
                break;
            }
        }
        return $content;
    }
    // Get the course manager status.
    public function managerstatus() {
        global $COURSE, $CFG, $DB;
        $content=NULL;
        $context = context_course::instance($COURSE->id);
        $managing_users = get_users_by_capability($context, 'moodle/course:manageactivities');
        // For displaying the course manager status.
        foreach ($managing_users as $managing_user) {
            $dbrecord = $DB->get_record('block_coursemanagerstatus', array('userid'=>"$managing_user->id"));
            $content.=html_writer::start_tag('b').$managing_user->firstname.' '.$managing_user->lastname.html_writer::end_tag('b').':';
            if ($dbrecord) {
                $content.= $dbrecord->status;
                if($dbrecord->comments!=null)
                    $content.= html_writer::empty_tag('br', array()).html_writer::start_tag('b').get_string('comments', 'block_coursemanagerstatus').html_writer::end_tag('b').
                        $dbrecord->comments;
            } else {
                $content.= get_string('notset', 'block_coursemanagerstatus');
            }
        }
        return $content;
    }
    // Form for updating a user status.
    public function updateform(moodle_url $formtarget, $courseid_manager) {
        global $USER, $CFG;
        $form = new update_form($formtarget, array('courseid'=>$courseid_manager));
        return $form->returnhtml();
    }
}