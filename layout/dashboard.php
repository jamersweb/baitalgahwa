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
$isrtl = right_to_left();
$context['dashboardwelcome'] = get_string('dashboardwelcome', 'theme_baitalgahwa', fullname($USER));
$context['dashboard_featured_tag'] = $isrtl ? 'البرنامج المميز' : 'Featured programme';
$context['dashboard_overview_tab'] = $isrtl ? 'نظرة عامة' : 'Overview';
$context['dashboard_intro_heading'] = $isrtl ? 'مقدمة' : 'Introduction';
$context['dashboard_intro_fallback'] = $isrtl
    ? 'واصل تطوير مهاراتك عبر رحلة تعلم منسقة مستوحاة من بيت القهوة.'
    : 'Continue building your skills through a curated learning journey inspired by Bait Al Gahwa.';
$context['dashboard_announcement_title'] = $isrtl ? 'إعلان' : 'Announcement';
$context['dashboard_announcement_body'] = $isrtl
    ? 'افتح البرنامج المميز لمتابعة التعلّم والاطلاع على التحديثات والوصول إلى أحدث الأنشطة من مكان واحد.'
    : 'Open the featured programme to continue learning, review updates, and access the latest activities in one place.';
$context['dashboard_activity_heading'] = $isrtl ? 'الأنشطة' : 'Activities';
$context['dashboard_activity_intro'] = $isrtl
    ? 'تابع البرامج المتاحة حالياً داخل مساحة التعلّم الخاصة بك.'
    : 'Continue with the programmes currently available in your learning space.';
$context['dashboard_members_link'] = $isrtl ? 'عرض جميع الأعضاء' : 'View all members';
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
$context['config']['membersurl'] = (new \moodle_url('/my/courses.php'))->out(false);
$context['config']['coursetodo'] = (new \moodle_url('/my/courses.php'))->out(false);
$context['news_mail_subject'] = rawurlencode(get_string('dashboard_news_title', 'theme_baitalgahwa'));
$context['featuredcourse'] = [];
$context['has_featuredcourse'] = false;
$context['activity_courses'] = [];
$context['has_activity_courses'] = false;
$context['dashboard_intro_text'] = $context['dashboard_intro_fallback'];

if (!empty($context['mycourses'])) {
    $context['featuredcourse'] = $context['mycourses'][0];
    $context['has_featuredcourse'] = true;
    $context['activity_courses'] = array_slice($context['mycourses'], 0, 4);
    $context['has_activity_courses'] = !empty($context['activity_courses']);
    $context['config']['membersurl'] = (new \moodle_url('/user/index.php', ['id' => $context['featuredcourse']['id']]))->out(false);
    if (!empty($context['featuredcourse']['summary'])) {
        $context['dashboard_intro_text'] = $context['featuredcourse']['summary'];
    }
} else if (!empty($context['dashboard_progress'])) {
    $context['featuredcourse'] = $context['dashboard_progress'][0];
    $context['has_featuredcourse'] = true;
    $context['config']['membersurl'] = (new \moodle_url('/user/index.php', ['id' => $context['featuredcourse']['id']]))->out(false);
}

ob_start();
echo $OUTPUT->main_content();
$context['maincontent'] = ob_get_clean();
$context['hasmaincontent'] = trim($context['maincontent']) !== '';

echo $OUTPUT->render_from_template('theme_baitalgahwa/dashboard', $context);
