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
 * Public programme detail page for Bait Al Gahwa.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$id = required_param('id', PARAM_INT);
$course = get_course($id);
if (!$course || (int) $course->id === (int) SITEID || empty($course->visible)) {
    throw new \moodle_exception('invalidcourseid');
}

$PAGE->set_url(new \moodle_url('/theme/baitalgahwa/programme.php', ['id' => $id]));
$PAGE->set_context(\context_system::instance());
$PAGE->set_pagelayout('base');
$PAGE->set_title(format_string($course->fullname) . ': Programme');
$PAGE->set_heading(format_string($SITE->fullname));

$context = theme_baitalgahwa_bootstrap_drawer_template_context();
$templatecourse = theme_baitalgahwa_format_course_for_template($course);
$programmeimages = [
    theme_baitalgahwa_get_theme_image_url('gahwa-beans-pan'),
    theme_baitalgahwa_get_theme_image_url('gahwa-screen'),
    theme_baitalgahwa_get_theme_image_url('gahwa-presenter'),
    theme_baitalgahwa_get_theme_image_url('gahwa-beans-bag'),
];
$templatecourse['imageurl'] = $programmeimages[((int) $course->id) % count($programmeimages)];
$start = !empty($course->startdate) ? (int) $course->startdate : 0;
$end = !empty($course->enddate) ? (int) $course->enddate : 0;
$days = 8;
if ($start > 0 && $end > $start) {
    $days = max(1, (int) ceil(($end - $start) / DAYSECS));
}

$context['programme'] = array_merge($templatecourse, [
    'enrolurl' => (new \moodle_url('/enrol/index.php', ['id' => $course->id]))->out(false),
    'learnurl' => (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
    'duration' => $days . ' Days',
    'languages' => 'Arabic',
    'location' => 'Abu Dhabi',
    'fee_line' => 'Fees are free',
    'seat_line' => 'Limited seats available',
]);
$context['programme_outcomes'] = [
    ['text' => 'Practical mastery of traditional Gahwa preparation'],
    ['text' => 'Full readiness to lead formal or interactive Gahwa experiences'],
    ['text' => 'Qualification to pursue roles like Gahwa Specialist, Host, or Certified Instructor'],
];

echo $OUTPUT->render_from_template('theme_baitalgahwa/programme', $context);
