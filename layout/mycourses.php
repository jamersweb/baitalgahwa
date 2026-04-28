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
 * My courses: drawer layout with catalogue toolbar (Manage / Create).
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $USER, $OUTPUT;

$userid = (int) $USER->id;
$context = theme_baitalgahwa_bootstrap_drawer_template_context();
$context = array_merge($context, theme_baitalgahwa_get_learning_page_context($userid));
$context = array_merge($context, theme_baitalgahwa_get_mycourses_toolbar_context());
$context['config']['calurl'] = (new \moodle_url('/calendar/view.php'))->out(false);
$context['config']['membersurl'] = (new \moodle_url('/my/courses.php'))->out(false);
$context['config']['coursetodo'] = (new \moodle_url('/my/courses.php'))->out(false);
if (!empty($context['featuredcourse'])) {
    $context['config']['membersurl'] = (new \moodle_url('/user/index.php', ['id' => $context['featuredcourse']['id']]))->out(false);
}

ob_start();
echo $OUTPUT->main_content();
$context['maincontent'] = ob_get_clean();
$context['hasmaincontent'] = trim($context['maincontent']) !== '';

echo $OUTPUT->render_from_template('theme_baitalgahwa/mycourses', $context);
