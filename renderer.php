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
class block_coursemanagerstatus_renderer extends plugin_renderer_base {

    // Form for updating a user status.
    public function update_form(moodle_url $formtarget) {
        global $USER;
        $content = html_writer::start_tag('form', array('method'=>get_string('form_method', 'block_coursemanagerstatus'),
                                          'action'=>$formtarget));
        $content .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'userid', 'id'=>'userid', 'value'=>$USER->id));
        $content .= html_writer::start_tag('div');
        $content .= html_writer::tag('label', '<b>'.get_string('label_update_userstatus', 'block_coursemanagerstatus').':</b>',
                                     array('for'=>'userstatus'));
        $content .= html_writer::start_tag('select', array('id'=>'userstatus', 'name'=>'userstatus'));
        $content .= html_writer::tag('option', get_string('select_option_1', 'block_coursemanagerstatus'),
                                     array('value'=>get_string('select_option_1', 'block_coursemanagerstatus')));
        $content .= html_writer::tag('option', get_string('select_option_2', 'block_coursemanagerstatus'),
                                     array('value'=>get_string('select_option_2', 'block_coursemanagerstatus')));
        $content .= html_writer::end_tag('select');
        $content .= html_writer::empty_tag('br', array());
        $content .= html_writer::empty_tag('br', array());
        $content .= html_writer::tag('label', '<b>'.get_string('comments', 'block_coursemanagerstatus').'</b>',
                                     array('for'=>'userstatuscomments'));
        $content .= html_writer::empty_tag('input', array('id'=>'userstatuscomments',
                                                          'type'=>'text',
                                                          'name'=>'userstatuscomments'));
        $content .= html_writer::empty_tag('br', array());
        $content .= html_writer::start_tag('center');
        $content .= html_writer::empty_tag('input', array('type'=>'submit',
                                                          'value'=>get_string('update', 'block_coursemanagerstatus')));
        $content .= html_writer::end_tag('center');
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('form');
        return $content;
    }

}