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
 * Shows a tabulated view of all the available activity preset in a given course.
 *
 * @package    mod_wizard
 * @copyright  2024 Daniel Thies <dethies@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_wizard\output\course_presets_page;

require_once("../../config.php");

$id = required_param('id', PARAM_INT); // Course Id.

// Access + permissions.
$course = get_course($id);
require_course_login($course, false);

$context = context_course::instance($course->id);
if (!has_capability('mod/wizard:configure', $context)) {
    throw new \moodle_exception('nopermissions', 'error');
}

// Page setup.
global $PAGE, $OUTPUT;
$pagetitle = get_string('coursepresets', 'mod_wizard');
$pageurl = new moodle_url('/mod/wizard/coursepresets.php', ['id' => $course->id]);
$PAGE->set_pagelayout('incourse');
$PAGE->set_context($context);
$PAGE->set_url($pageurl);
$PAGE->set_title($pagetitle);
$PAGE->set_heading(format_string($course->fullname, true, ['context' => $context]));
$PAGE->add_body_class('limitedwidth');

// Display.
echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

$coursepresetspage = new course_presets_page($course->id);
echo $OUTPUT->render($coursepresetspage);
$PAGE->requires->js_call_amd('mod_wizard/presets_list', 'init');

echo $OUTPUT->footer();
