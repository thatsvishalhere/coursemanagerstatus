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
 * This file processes the form input for the coursemanagerstatus block.
 *
 * @package    block
 * @author     Vishal Raheja - thatsvishalhere@gmail.com
 * @copyright  2013 Vishal Raheja
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
global $CFG, $USER, $DB;
require_once('../../config.php');
require_login();

// If the data is submitted update the table.
if ($data = data_submitted()) {

    // Get the records related to the user.
    $dbrecord = $DB->get_record('block_coursemanagerstatus', array('userid'=>"$USER->id"));
    // Create a new record for inserting/updating the table.
    $record = new stdClass();
    $record->userid = $USER->id;
    $record->status = $data->userstatus;
    $record->comments= $data->userstatuscomments;
    // If no records are present, insert a record.
    if (!$dbrecord) {
        // Insert a new record in the block table.
        $DB->insert_record('block_coursemanagerstatus', $record);
    } else {
        $record->id = $dbrecord->id;
		
        // Update the record in the block table.
        $DB->update_record('block_coursemanagerstatus', $record);       
    }
    redirect(get_referer(false));
}

// Redirect to main page if data is not being submitted.
redirect(new moodle_url('/'));