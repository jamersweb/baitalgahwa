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
 * Public Training Programmes page for Bait Al Gahwa.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$PAGE->set_url(new \moodle_url('/theme/baitalgahwa/training.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_pagelayout('base');
$PAGE->set_title(format_string($SITE->fullname) . ': Training Programmes');
$PAGE->set_heading(format_string($SITE->fullname));

$context = theme_baitalgahwa_bootstrap_drawer_template_context();
$context['training_hero_url'] = theme_baitalgahwa_get_theme_image_url('hero-workshop-bg');
$context['training_fallback_course_url'] = theme_baitalgahwa_get_theme_image_url('course-activity-layout');
$courses = theme_baitalgahwa_get_featured_courses(6);
$trainingimages = [
    theme_baitalgahwa_get_theme_image_url('gahwa-beans-pan'),
    theme_baitalgahwa_get_theme_image_url('gahwa-screen'),
    theme_baitalgahwa_get_theme_image_url('gahwa-presenter'),
    theme_baitalgahwa_get_theme_image_url('gahwa-beans-bag'),
];
foreach ($courses as $index => $course) {
    $courses[$index]['imageurl'] = $trainingimages[$index % count($trainingimages)];
    if (!empty($course['id'])) {
        $courses[$index]['detailurl'] = (new \moodle_url('/theme/baitalgahwa/programme.php', ['id' => $course['id']]))->out(false);
    }
}
if (!empty($courses)) {
    $i = 0;
    $n = count($courses);
    while (count($courses) < 6) {
        $courses[] = array_merge($courses[$i % $n]);
        $i++;
    }
}
$context['training_courses'] = $courses;
$context['has_training_courses'] = !empty($courses);

echo $OUTPUT->render_from_template('theme_baitalgahwa/training', $context);
