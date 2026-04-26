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
 * English language strings: Bait Al Gahwa theme.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Bait Al Gahwa';
$string['choosereadme'] = 'Child theme of Boost: warm coffeehouse-inspired design (Bait Al Gahwa), RTL-friendly, production-ready. Set the site default home page to Dashboard (Site administration, Appearance, Navigation) so users land on /my/ after login.';

$string['configtitle'] = 'Bait Al Gahwa settings';
$string['settings_branding'] = 'Branding & colours';
$string['settings_hero'] = 'Front page hero';
$string['settings_footer'] = 'Footer';

$string['logo'] = 'Logo';
$string['logodesc'] = 'Upload a logo to show in the header (PNG or SVG with transparent background recommended; replace the default in pix/logo.svg for build pipelines).';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'Optional .ico or small PNG. You can also place favicon.ico in the theme pix folder.';

$string['primarycolor'] = 'Primary brand colour';
$string['primarycolordesc'] = 'Main brand colour (coffee brown in the design reference).';
$string['secondarycolor'] = 'Secondary colour';
$string['secondarycolordesc'] = 'Supporting wood / earth tone.';
$string['accentcolor'] = 'Accent colour';
$string['accentcolordesc'] = 'Highlights, buttons, and key UI accents (e.g. gold/amber from the gahwa palette).';

$string['herotitle'] = 'Hero title';
$string['herotitledesc'] = 'Headline for the home page hero.';
$string['herosubtitle'] = 'Hero subtitle';
$string['herosubtitledesc'] = 'Short supporting line under the title.';
$string['herosubtitledefault'] = 'A warm place to learn, share, and grow — inspired by the majlis and the art of gahwa.';
$string['heroimage'] = 'Hero image';
$string['heroimagedesc'] = 'Full-width background for the hero. Optional; uses a deep gradient if empty.';
$string['heroprimary'] = 'Primary CTA text';
$string['heroprimaryurl'] = 'Primary CTA link';
$string['herosecondary'] = 'Secondary CTA text';
$string['herosecondaryurl'] = 'Secondary CTA link';
$string['explore'] = 'Explore courses';
$string['login'] = 'Log in';
$string['gotocourse'] = 'View course';
$string['progress'] = 'Progress';
$string['featured'] = 'Featured courses';
$string['nocourses'] = 'No published courses to show yet.';
$string['whybait'] = 'Why Bait Al Gahwa?';
$string['dashboardwelcome'] = 'Welcome back, {$a}';
$string['statslearners'] = 'Learners';
$string['statscourses'] = 'Courses';
$string['footerabout'] = 'About text';
$string['footeraboutdefault'] = 'A learning space rooted in coffeehouse culture: hospitality, focus, and community.';
$string['footeremail'] = 'Contact email';
$string['footerphone'] = 'Phone';
$string['footeraddress'] = 'Address';
$string['footerfacebook'] = 'Facebook URL';
$string['footerinstagram'] = 'Instagram URL';
$string['footerlinkedin'] = 'LinkedIn URL';
$string['footertwitter'] = 'X (Twitter) URL';
$string['footeryoutube'] = 'YouTube URL';
$string['footerquick'] = 'Quick links';
$string['footercontact'] = 'Contact';
$string['footercopy'] = 'All rights reserved.';

$string['customcss'] = 'Custom SCSS / CSS (advanced)';
$string['customcssdesc'] = 'Appended to the end of the compiled theme stylesheet. Use for small adjustments; prefer theme files when possible.';

$string['settings_courseactions'] = 'Course management & cards';
$string['managecourseurloverride'] = '“Manage course” URL override (optional)';
$string['managecourseurloverridedesc'] = 'Leave empty to use the standard link: /course/management.php?category=[category id]. Some organisations use a custom workflow; set a full URL or a site path (e.g. /local/…/manage.php) so the My courses toolbar and participants strip match your process. After deploy, purge all caches.';
$string['instructorchipline'] = 'Instructor chip: third line meaning';
$string['instructorchiplinedesc'] = 'Default: show the course end date when set, otherwise “open enrolment”. “Last access” shows the learner’s last access time only if your site exposes the timeaccess field on course cards (not included in stock Moodle 4.5’s my overview payload) — implement via a local plugin and complete a privacy review before using it in production.';
$string['instructorchipline_end'] = 'Course end date (or open enrolment)';
$string['instructorchipline_lastaccess'] = 'Last access (only when timeaccess is available)';

$string['mycourse_instructor_thirdline_courseend'] = 'Course end date';
$string['mycourse_instructor_thirdline_lastaccess'] = 'Your last access to this course';

$string['privacy:metadata'] = 'The Bait Al Gahwa theme does not store personal data. Uploaded logos and similar assets are stored in the standard Moodle file system.';

$string['region-side-pre'] = 'Right';
$string['region-side-post'] = 'Left';

$string['settings_auth'] = 'Login & sign up';
$string['auth_herotitle'] = 'Continue your learning journey';
$string['auth_herosub'] = 'Access world-class training programs and unlock your potential with Bait Al Gahwa\'s premium courses.';
$string['authherotitle'] = 'Login hero title';
$string['authherotitledesc'] = 'Headline on the photo column (login and sign-up pages). Leave empty to use the default string above.';
$string['authherosubtitle'] = 'Login hero subtitle';
$string['authherosubtitledesc'] = 'Supporting text under the headline. Leave empty to use the default.';
$string['loginbackgroundimage'] = 'Login / sign up hero image';
$string['loginbackgroundimagedesc'] = 'Optional background for the photo column. If empty, the site hero image is used when set.';

$string['divideror'] = 'Or';
$string['authpanel_welcome'] = 'Welcome back';
$string['authpanel_signup_title'] = 'Sign up with us';
$string['authpanel_login_sub'] = 'Kindly enter your details below to sign in to your account.';
$string['authpanel_signup_sub'] = 'Kindly fill in your details below to create an account.';
$string['authpanel_createaccount_line'] = 'Create your account';
$string['authpanel_signup_link'] = 'Sign up';
$string['authpanel_already'] = 'Already have an account?';
$string['authpanel_socialnav'] = 'Social links';

$string['course_new'] = 'New';

// My courses / course catalogue (block_myoverview + mycourses layout).
$string['mycourses_createcourse'] = 'Create course';
$string['mycourses_toolbar_aria'] = 'My courses actions';
$string['mycourse_available'] = 'Available';
$string['mycourse_dates_hint'] = 'Open for enrolment';
$string['mycourse_excerpt'] = 'A guided programme with clear outcomes and support along the way.';
$string['mycourse_instructor'] = 'Instructor: course team';
$string['mycourse_instructor_chip_aria'] = 'Course contact';
$string['mycourse_instructor_chip_name'] = 'Course facilitator';
$string['mycourse_instructor_chip_role'] = 'Member';
$string['mycourse_instructor_date_open'] = 'Open enrolment';

// Single course page (dashboard layout)
$string['course_enter'] = 'View course';
$string['course_viewpage'] = 'View course page';
$string['course_manage'] = 'Manage course';
$string['course_instructor_default'] = 'Course team';
$string['course_stats_region'] = 'Course statistics';
$string['course_stat_enrolled'] = 'Enrolled students';
$string['course_stat_completed'] = 'Students completed';
$string['course_stat_inprogress'] = 'In progress';
$string['course_stat_yettostart'] = 'Yet to start';
$string['course_stats_completion_off'] = 'Completion tracking is off for this course; only enrolment is shown.';
$string['dashboard_pagetitle'] = 'Dashboard';
$string['dashboard_stat_enrolled'] = 'Courses enrolled';
$string['dashboard_stat_inprogress'] = 'Courses in progress';
$string['dashboard_stat_completed'] = 'Courses completed';
$string['dashboard_stat_certs'] = 'Active certificates';
$string['dashboard_training_title'] = 'Our Training Programmes';
$string['dashboard_training_intro'] = 'Explore professional development courses designed to grow your skills and advance your career.';
$string['dashboard_progress_title'] = 'Course progress';
$string['dashboard_col_course'] = 'Course';
$string['dashboard_col_status'] = 'Status';
$string['dashboard_col_progress'] = 'Progress';
$string['table_action'] = 'Action';
$string['dashboard_status_done'] = 'Completed';
$string['dashboard_status_inprogress'] = 'In progress';
$string['dashboard_status_todo'] = 'Not started';
$string['dashboard_donut_title'] = 'Enrolled users by category';
$string['dashboard_donut_sub'] = 'Your enrollments';
$string['dashboard_others'] = 'Other';
$string['dashboard_fullcal'] = 'Full calendar';
$string['dashboard_quiz_title'] = 'Quiz attempts';
$string['dashboard_members_title'] = 'Latest members';
$string['dashboard_members_empty'] = 'No other learners visible in your courses yet.';
$string['dashboard_peer'] = 'Learner';
$string['dashboard_todo_title'] = 'To-do';
$string['dashboard_todo_body'] = 'Use the calendar and course overview to track upcoming work.';
$string['dashboard_todo_link'] = 'My courses';
$string['dashboard_news_title'] = 'LMS Newsletter';
$string['dashboard_news_intro'] = 'Questions or feedback? Reach out to the team.';
$string['dashboard_news_cta'] = 'Send';
$string['dashboard_news_admin'] = 'Set a contact email in the theme settings to enable the newsletter button.';
$string['dashboard_cal_s'] = 'S';
$string['dashboard_cal_m'] = 'M';
$string['dashboard_cal_tu'] = 'T';
$string['dashboard_cal_w'] = 'W';
$string['dashboard_cal_th'] = 'T';
$string['dashboard_cal_f'] = 'F';
$string['dashboard_cal_sa'] = 'S';
