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
 * My dashboard: Bait Al Gahwa layout (stats, programmes, progress, widgets) + Moodle “My” content.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $USER, $CFG, $OUTPUT;

$userid = (int) $USER->id;
$context = theme_baitalgahwa_bootstrap_drawer_template_context();
$context['dashboardwelcome'] = get_string('dashboardwelcome', 'theme_baitalgahwa', fullname($USER));
$context['mycourses'] = theme_baitalgahwa_get_featured_courses(8);
$context['has_mycourses'] = !empty($context['mycourses']);
$context['dashboard_stats'] = theme_baitalgahwa_get_dashboard_stats($userid);
$context['dashboard_progress'] = theme_baitalgahwa_get_dashboard_progress_rows($userid, 10);
$context['has_dashboard_progress'] = !empty($context['dashboard_progress']);
$context['dashboard_donut'] = theme_baitalgahwa_get_dashboard_donut($userid);
$context['dashboard_quiz_bars'] = theme_baitalgahwa_get_dashboard_quiz_bars($userid);
$context['dashboard_calendar'] = theme_baitalgahwa_get_dashboard_calendar();
$context['dashboard_members'] = theme_baitalgahwa_get_dashboard_recent_users(8, $userid);
$context['has_dashboard_members'] = !empty($context['dashboard_members']);
$context['config']['calurl'] = (new \moodle_url('/calendar/view.php'))->out(false);
$context['config']['coursetodo'] = (new \moodle_url('/my/courses.php'))->out(false);
$context['news_mail_subject'] = rawurlencode(get_string('dashboard_news_title', 'theme_baitalgahwa'));

echo $OUTPUT->render_from_template('theme_baitalgahwa/dashboard', $context);
