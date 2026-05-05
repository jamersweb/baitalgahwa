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
$context = array_merge($context, theme_baitalgahwa_get_learning_page_context($userid));
$context['config']['calurl'] = (new \moodle_url('/calendar/view.php'))->out(false);
$context['config']['membersurl'] = (new \moodle_url('/my/courses.php'))->out(false);
$context['config']['coursetodo'] = (new \moodle_url('/my/courses.php'))->out(false);
$context['bag_home_hero_url'] = theme_baitalgahwa_get_theme_image_url('misc-reference');
$context['bag_home_coffee_url'] = theme_baitalgahwa_get_theme_image_url('auth-signup-hero');
$context['bag_home_activity_url'] = theme_baitalgahwa_get_theme_image_url('course-activity-layout');
$context['bag_home_member_url'] = theme_baitalgahwa_get_theme_image_url('member-card');
$context['dashboard_home_programmes'] = array_slice($context['dashboard_programmes'] ?? [], 0, 3);
$context['has_dashboard_home_programmes'] = !empty($context['dashboard_home_programmes']);
if (!empty($context['featuredcourse'])) {
    $context['config']['membersurl'] = (new \moodle_url('/user/index.php', ['id' => $context['featuredcourse']['id']]))->out(false);
}
$context['dashboard_news_mailto'] = '';
if (!empty($context['footeremail'])) {
    $context['dashboard_news_mailto'] = 'mailto:' . $context['footeremail'] . '?subject=' . $context['news_mail_subject'];
}

ob_start();
echo $OUTPUT->main_content();
$context['maincontent'] = ob_get_clean();
$context['hasmaincontent'] = trim($context['maincontent']) !== '';

echo $OUTPUT->render_from_template('theme_baitalgahwa/dashboard', $context);
