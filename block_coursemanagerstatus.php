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
 * This is the main file of the block coursemanagerstatus.
 *
 * @package    block
 * @author     Vishal Raheja - thatsvishalhere@gmail.com
 * @copyright  2013 Vishal Raheja
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class block_coursemanagerstatus extends block_base {
    public function init() {
        $this->title = get_string('coursemanagerstatus', 'block_coursemanagerstatus');
    }
    public function get_content() {
        global $USER, $COURSE, $CFG, $PAGE, $DB;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content =  new stdClass;
        $updateflag = false;
        // For showing the user status on the my page in moodle.
        if ($PAGE->pagetype=='my-index') {
            $courses=get_courses();
            foreach ($courses as $course) {
                $context = context_course::instance($course->id);
                // To allow the users who have editing rights on any of the courses to update their status.
                if (has_capability('moodle/course:manageactivities', $context, $USER->id)) {
                    $updateflag = true;	
                    $dbrecord = $DB->get_record('block_coursemanagerstatus', array('userid'=>"$USER->id"));
                    $this->content->text.='<b>'.get_string('current_status', 'block_coursemanagerstatus').'</b> ';
                    if ($dbrecord) {
                        $this->content->text.= $dbrecord->status.'<br />';
                        if ($dbrecord->comments!=null)
                            $this->content->text.= '<b>'.get_string('comments', 'block_coursemanagerstatus').'</b> '.
                                                   $dbrecord->comments.'<br />';
                    } else {
                        $this->content->text.= get_string('notset', 'block_coursemanagerstatus').'<br />';
                    }
                    $this->content->text.= '<br />';
                    break;
                }
            }
        } else {
            $context = context_course::instance($COURSE->id);
            $managing_users = get_users_by_capability($context, 'moodle/course:manageactivities');
            // For displaying the course manager status.
            foreach ($managing_users as $managing_user) {
                if($managing_user->id == $USER->id)
				{
					$updateflag=true;
				}
                $dbrecord = $DB->get_record('block_coursemanagerstatus', array('userid'=>"$managing_user->id"));
                $this->content->text=$this->content->text.'<b>'.$managing_user->firstname.' '.$managing_user->lastname.'</b>:';
                if ($dbrecord) {
                    $this->content->text.= $dbrecord->status.'<br />';
                    if($dbrecord->comments!=null)
                        $this->content->text.= '<b>'.get_string('comments', 'block_coursemanagerstatus').'</b> '.
                                               $dbrecord->comments.'<br />';
                } else {
                    $this->content->text.= get_string('notset', 'block_coursemanagerstatus').'<br />';
                }
                $this->content->text.= '<br />';
            }
        }
		if($updateflag===true)
		{
            $renderer = $this->page->get_renderer('block_coursemanagerstatus');
			$this->content->text.=$renderer->update_form(new moodle_url("/blocks/coursemanagerstatus/update_status.php"));
		}
        return $this->content;
    }
    public function specialization() {
        global $PAGE;
        // Different block titles for my index and course pages.
        if ($PAGE->pagetype=='my-index') {
            $this->title = get_string('title_my', 'block_coursemanagerstatus');
        } else {
            $this->title = get_string('title_courses', 'block_coursemanagerstatus');
        }
    }
    public function applicable_formats() {
        return array('my' => true, 'course-view' =>true);
    }
}