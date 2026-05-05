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
 * Bait Al Gahwa theme — helper functions, SCSS callbacks, plugin file serving.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Theme pix URL helper for bundled fallback assets.
 *
 * @param string $imagename Image key without extension.
 * @return string
 */
function theme_baitalgahwa_get_theme_image_url(string $imagename): string {
    $theme = \theme_config::load('baitalgahwa');
    return $theme->image_url($imagename, 'theme')->out(false);
}

/**
 * Returns a translated string, or a readable fallback while caches are stale.
 *
 * @param string $identifier
 * @param string $fallback
 * @param string $component
 * @return string
 */
function theme_baitalgahwa_string_or_default(string $identifier, string $fallback, string $component = 'theme_baitalgahwa'): string {
    $value = get_string($identifier, $component);
    if (preg_match('/^\[\[[^\]]+\]\]$/', (string) $value)) {
        return $fallback;
    }
    return $value;
}

/**
 * Like get_string with an extra parameter, but returns $fallback if Moodle would print [[placeholder]].
 *
 * @param string $identifier
 * @param string $fallback
 * @param mixed|null $param
 * @return string
 */
function theme_baitalgahwa_safe_lang(string $identifier, string $fallback, $param = null): string {
    if ($param === null) {
        return theme_baitalgahwa_string_or_default($identifier, $fallback);
    }
    $value = get_string($identifier, 'theme_baitalgahwa', $param);
    if (preg_match('/^\[\[[^\]]+\]\]$/', (string) $value)) {
        return $fallback;
    }
    return $value;
}

/**
 * Dashboard UI labels with safe fallbacks.
 *
 * @return array<string, string>
 */
function theme_baitalgahwa_get_dashboard_ui_strings(): array {
    return [
        'filtercategory' => theme_baitalgahwa_string_or_default('dashboard_filter_category', 'Select Category'),
        'filterallcategories' => theme_baitalgahwa_string_or_default('dashboard_filter_all_categories', 'All Categories'),
        'filterallcourses' => theme_baitalgahwa_string_or_default('dashboard_filter_all_courses', 'All Courses'),
        'filterallevents' => theme_baitalgahwa_string_or_default('dashboard_filter_all_events', 'All Events'),
        'filtersessions' => theme_baitalgahwa_string_or_default('dashboard_filter_sessions', 'Sessions'),
        'filterdeadlines' => theme_baitalgahwa_string_or_default('dashboard_filter_deadlines', 'Deadlines'),
        'newevent' => theme_baitalgahwa_string_or_default('dashboard_new_event', 'New Event'),
        'importexport' => theme_baitalgahwa_string_or_default('dashboard_import_export', 'Import Or Export Calendar'),
        'fullcalendar' => theme_baitalgahwa_string_or_default('dashboard_fullcal', 'Full Calendar'),
        'filterallquizzes' => theme_baitalgahwa_string_or_default('dashboard_filter_all_quizzes', 'All Quizzes'),
        'quizattempted' => theme_baitalgahwa_string_or_default('dashboard_quiz_attempted', 'Total Users Attempted Quiz'),
        'quiznotattempted' => theme_baitalgahwa_string_or_default('dashboard_quiz_not_attempted', 'Total Users Not Attempted Quiz'),
    ];
}

/**
 * Serves files from theme file areas.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_baitalgahwa_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []): bool {
    $fileareas = ['logo', 'favicon', 'heroimage', 'backgroundimage', 'loginbackgroundimage'];
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        send_file_not_found();
    }
    if (!in_array($filearea, $fileareas, true)) {
        send_file_not_found();
    }
    $theme = \theme_config::load('baitalgahwa');
    if (!array_key_exists('cacheability', $options)) {
        $options['cacheability'] = 'public';
    }
    return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
}

/**
 * Returns the main SCSS, starting from Boost and appending the child layer.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_baitalgahwa_get_main_scss_content($theme) {
    global $CFG;
    $parent = theme_config::load('boost');
    $scss = theme_boost_get_main_scss_content($parent);
    if (is_readable($CFG->dirroot . '/theme/baitalgahwa/scss/post.scss')) {
        $scss .= "\n" . file_get_contents($CFG->dirroot . '/theme/baitalgahwa/scss/post.scss');
    }
    return $scss;
}

/**
 * Prepend variables (after Boost) so the design tokens apply globally.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_baitalgahwa_get_pre_scss($theme) {
    global $CFG;
    $parent = theme_config::load('boost');
    $scss = theme_boost_get_pre_scss($parent);
    if (is_readable($CFG->dirroot . '/theme/baitalgahwa/scss/variables.scss')) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/baitalgahwa/scss/variables.scss');
    }
    $p = $theme->settings->primarycolor ?? null;
    $s = $theme->settings->secondarycolor ?? null;
    $a = $theme->settings->accentcolor ?? null;
    if (!empty($p)) {
        $scss .= "\n" . '$primary: ' . $p . ";\n";
    }
    if (!empty($s)) {
        $scss .= "\n" . '$secondary: ' . $s . ";\n";
    }
    if (!empty($a)) {
        $scss .= "\n" . '$bag-accent: ' . $a . ";\n";
    }
    if (is_readable($CFG->dirroot . '/theme/baitalgahwa/scss/pre.scss')) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/baitalgahwa/scss/pre.scss');
    }
    return $scss;
}

/**
 * Extra SCSS and admin “Custom CSS” after compilation.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_baitalgahwa_get_extra_scss($theme) {
    $parent = theme_config::load('boost');
    $content = theme_boost_get_extra_scss($parent);
    if (!empty($theme->settings->customcss)) {
        $content .= "\n" . $theme->settings->customcss;
    }
    return $content;
}

/**
 * Uses Boost pre-compiled fall-back when the SCSS pipeline requires it.
 *
 * @return string
 */
function theme_baitalgahwa_get_precompiled_css() {
    return theme_boost_get_precompiled_css();
}

/**
 * “Manage course” target URL. Default matches core: course/management.php?category=ID.
 * Sites with a custom workflow can set a full override in theme settings.
 *
 * @param int $categoryid
 * @return string
 */
function theme_baitalgahwa_get_manage_course_url(int $categoryid): string {
    $t = get_config('theme_baitalgahwa');
    if (!empty($t->managecourseurloverride) && is_string($t->managecourseurloverride)) {
        $o = trim($t->managecourseurloverride);
        if ($o !== '') {
            // Allow either a full URL or a path (wwwroot is prepended for relative /course/... paths).
            if (strpos($o, 'http:') === 0 || strpos($o, 'https:') === 0) {
                return $o;
            }
            global $CFG;
            if (strpos($o, '/') === 0) {
                return (new \moodle_url($o))->out(false);
            }
            return (new \moodle_url('/' . ltrim($o, '/')))->out(false);
        }
    }
    return (new \moodle_url('/course/management.php', ['category' => $categoryid]))->out(false);
}

/**
 * Instructor chip third line mode: course end (default) or last access (see lang string; often needs extra data).
 *
 * @return string 'end'|'lastaccess'
 */
function theme_baitalgahwa_get_instructor_chipline_mode(): string {
    $t = get_config('theme_baitalgahwa');
    if (!empty($t->instructorchipline) && (int) $t->instructorchipline === 1) {
        return 'lastaccess';
    }
    return 'end';
}

/**
 * Short hint for the chip date (used as title / data-bag-instructor-hint for semantics).
 *
 * @return string
 */
function theme_baitalgahwa_get_instructor_chipline_hint(): string {
    if (theme_baitalgahwa_get_instructor_chipline_mode() === 'lastaccess') {
        return get_string('mycourse_instructor_thirdline_lastaccess', 'theme_baitalgahwa');
    }
    return get_string('mycourse_instructor_thirdline_courseend', 'theme_baitalgahwa');
}

/**
 * User preferences (drawer states inherit Boost behaviour).
 *
 * @return array[]
 */
function theme_baitalgahwa_user_preferences(): array {
    return theme_boost_user_preferences();
}

/**
 * Exports footer-related context for templates (strings from theme settings).
 *
 * @return array
 */
function theme_baitalgahwa_get_footer_context(): array {
    $t = get_config('theme_baitalgahwa');
    return [
        'footerabout' => get_string('footeraboutdesign', 'theme_baitalgahwa'),
        'footeremail' => $t->footeremail ?? '',
        'footerphone' => $t->footerphone ?? '',
        'footeraddress' => $t->footeraddress ?? '',
        'facebook' => $t->footerfacebook ?? '',
        'instagram' => $t->footerinstagram ?? '',
        'linkedin' => $t->footerlinkedin ?? '',
        'youtube' => $t->footeryoutube ?? '',
        'twitter' => $t->footertwitter ?? '',
        'footerfacebookicon' => theme_baitalgahwa_get_theme_image_url('social_facebook'),
        'footerinstagramicon' => theme_baitalgahwa_get_theme_image_url('social_instagram'),
        'footerlinkedinicon' => theme_baitalgahwa_get_theme_image_url('social_linkedin'),
        'footertwittericon' => theme_baitalgahwa_get_theme_image_url('social_twitter'),
    ];
}

/**
 * Exports theme hero / CTA for front page templates.
 *
 * @return array
 */
function theme_baitalgahwa_get_hero_context(): array {
    $t = get_config('theme_baitalgahwa');
    $theme = \theme_config::load('baitalgahwa');
    $herourl = $theme->setting_file_url('heroimage', 'heroimage');
    $fallbackhero = theme_baitalgahwa_get_theme_image_url('auth-signup-hero');
    return [
        'herotitle' => !empty($t->herotitle) ? $t->herotitle : 'Bait Al Gahwa',
        'herosubtitle' => !empty($t->herosubtitle) ? $t->herosubtitle : get_string('herosubtitledefault', 'theme_baitalgahwa'),
        'heroprimary' => $t->heroprimarytext ?? get_string('explore', 'theme_baitalgahwa'),
        'heroprimaryurl' => $t->heroprimaryurl ?? '/course',
        'herosecondary' => $t->herosecondarytext ?? '',
        'herosecondaryurl' => $t->herosecondaryurl ?? '/login/index.php',
        'heroimageurl' => $herourl ? (string) $herourl : $fallbackhero,
    ];
}

/**
 * Hero copy and background for login / signup pages (split-screen left column).
 *
 * Login background image (if set) wins; otherwise falls back to the site hero image.
 *
 * @return array{herotitle: string, herosubtitle: string, heroimageurl: string}
 */
function theme_baitalgahwa_get_auth_page_hero_context(): array {
    $t = get_config('theme_baitalgahwa');
    $theme = \theme_config::load('baitalgahwa');
    $loginbg = $theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
    $herobg = $theme->setting_file_url('heroimage', 'heroimage');
    $title = !empty($t->authherotitle) ? $t->authherotitle : get_string('auth_herotitle', 'theme_baitalgahwa');
    $sub = !empty($t->authherosubtitle) ? $t->authherosubtitle : get_string('auth_herosub', 'theme_baitalgahwa');
    $img = '';
    if ($loginbg) {
        $img = (string) $loginbg;
    } else if ($herobg) {
        $img = (string) $herobg;
    }
    return [
        'herotitle' => $title,
        'herosubtitle' => $sub,
        'heroimageurl' => $img,
    ];
}

/**
 * Resolves auth page fallback image by page type when no uploaded image exists.
 *
 * @param bool $issignup
 * @return string
 */
function theme_baitalgahwa_get_auth_page_fallback_image(bool $issignup = false): string {
    return $issignup
        ? theme_baitalgahwa_get_theme_image_url('auth-signup-hero')
        : theme_baitalgahwa_get_theme_image_url('auth-signin-hero');
}

/**
 * Whether this course should use the Certified Gahwa Specialist programme hero asset.
 *
 * @param stdClass $course Raw course row (fullname as stored).
 * @return bool
 */
function theme_baitalgahwa_is_certified_gahwa_specialist_course($course): bool {
    $name = isset($course->fullname) ? (string) $course->fullname : '';
    if ($name === '') {
        return false;
    }
    $needle = 'certified gahwa specialist';
    return strpos(\core_text::strtolower($name), $needle) !== false;
}

/**
 * Resolves a course image URL for cards if an overview file exists.
 *
 * @param stdClass $course
 * @return string
 */
function theme_baitalgahwa_get_course_image_url($course): string {
    $fallback = theme_baitalgahwa_get_theme_image_url('course-activity-layout');
    if (empty($course->id)) {
        return '';
    }
    if (theme_baitalgahwa_is_certified_gahwa_specialist_course($course)) {
        return theme_baitalgahwa_get_theme_image_url('course-gahwa-specialist-hero');
    }
    $context = \context_course::instance($course->id);
    $fs = get_file_storage();
    $files = $fs->get_area_files(
        $context->id,
        'course',
        'overviewfiles',
        0,
        'filesize > 0',
        'filepath, filename',
        false
    );
    $images = [];
    foreach ($files as $file) {
        if (!$file || $file->is_directory()) {
            continue;
        }
        $mime = $file->get_mimetype();
        if (strpos((string) $mime, 'image/') !== 0) {
            continue;
        }
        $images[] = $file;
    }
    if (!$images) {
        return $fallback;
    }
    usort($images, static function (\stored_file $a, \stored_file $b): int {
        return $b->get_timemodified() <=> $a->get_timemodified();
    });
    $file = $images[0];
    return \moodle_url::make_pluginfile_url(
        $file->get_contextid(),
        $file->get_component(),
        $file->get_filearea(),
        $file->get_itemid(),
        $file->get_filepath(),
        $file->get_filename()
    )->out(false);
}

/**
 * Builds safe course data for the front page course grid.
 *
 * @param int $max Maximum courses.
 * @return array<int, array<string, mixed>>
 */
function theme_baitalgahwa_get_featured_courses(int $max = 8): array {
    global $DB, $CFG;
    require_once($CFG->libdir . '/modinfolib.php');
    $out = [];
    $seenids = [];

    $tryadd = static function ($course) use (&$out, &$seenids, $max): bool {
        $id = (int) $course->id;
        if ($id === (int) SITEID || isset($seenids[$id])) {
            return false;
        }
        if (count($out) >= $max) {
            return true;
        }
        $seenids[$id] = true;
        $out[] = theme_baitalgahwa_format_course_for_template($course);
        return count($out) >= $max;
    };

    if (isloggedin() && !isguestuser()) {
        $mycourses = enrol_get_my_courses(
            'summary, summaryformat, startdate, enddate, category, timecreated',
            'visible DESC, fullname ASC'
        );
        foreach ($mycourses as $c) {
            if ($tryadd($c)) {
                return $out;
            }
        }
    }
    if (count($out) >= $max) {
        return $out;
    }
    $fields = 'id, category, fullname, shortname, visible, summary, sortorder, summaryformat, startdate, enddate, timecreated';
    $sql = "id > :siteid AND visible = 1";
    // Fetch enough rows that we can still reach $max after skipping enrol overlap and hidden edge cases.
    $fetchlimit = max(48, $max * 8);
    $records = $DB->get_records_select('course', $sql, ['siteid' => SITEID], 'sortorder ASC', $fields, 0, $fetchlimit);
    foreach ($records as $c) {
        if ($tryadd($c)) {
            break;
        }
    }
    return $out;
}

/**
 * Single course shape for mustache.
 *
 * @param stdClass $c
 * @return array<string, mixed>
 */
function theme_baitalgahwa_format_course_for_template($c): array {
    $context = \context_course::instance($c->id);
    $summary = '';
    if (!empty($c->summary)) {
        $summary = format_text($c->summary, $c->summaryformat ?? FORMAT_HTML, ['context' => $context]);
    }
    if (mb_strlen(preg_replace('/\s+/', ' ', html_to_text($summary, 0))) > 0) {
        $short = shorten_text(html_to_text($summary, 0), 120);
    } else {
        $short = theme_baitalgahwa_string_or_default(
            'mycourse_excerpt',
            'A guided programme with clear outcomes and support along the way.'
        );
    }
    $catname = '';
    if (!empty($c->category)) {
        $cat = \core_course_category::get($c->category, IGNORE_MISSING, true);
        if ($cat) {
            $catname = $cat->get_formatted_name();
        }
    }
    $timecreated = isset($c->timecreated) ? (int) $c->timecreated : 0;
    $startdate = isset($c->startdate) ? (int) $c->startdate : 0;
    $enddate = isset($c->enddate) ? (int) $c->enddate : 0;
    $isnew = $timecreated > 0 && (time() - $timecreated) < 30 * DAYSECS;
    $starts = $startdate > 0 ? userdate($startdate, '%d/%m/%Y') : '';
    $ends = $enddate > 0 ? userdate($enddate, '%d/%m/%Y') : '';
    return [
        'id' => (int) $c->id,
        'url' => (new \moodle_url('/course/view.php', ['id' => $c->id]))->out(false),
        'fullname' => format_string($c->fullname, true, ['context' => $context]),
        'summary' => $short,
        'categoryname' => $catname,
        'programme_focus_gahwa' => theme_baitalgahwa_is_certified_gahwa_specialist_course($c),
        'imageurl' => theme_baitalgahwa_get_course_image_url($c),
        'startdate_display' => $starts,
        'enddate_display' => $ends,
        'isnew' => $isnew,
        'card_starts_line' => $starts !== ''
            ? theme_baitalgahwa_safe_lang('mycourse_card_starts', 'It starts on ' . $starts, $starts) : '',
        'card_ends_line' => $ends !== ''
            ? theme_baitalgahwa_safe_lang('mycourse_card_ends', 'It ends on ' . $ends, $ends) : '',
        'has_card_schedule' => ($starts !== '' || $ends !== ''),
        'instructor_host' => theme_baitalgahwa_safe_lang(
            'mycourse_card_instructor_host',
            'Gahwa specialist, Bait Al Gahwa host'
        ),
    ];
}

/**
 * Shared learning context used by the custom dashboard and My Courses screens.
 *
 * @param int $userid
 * @return array<string, mixed>
 */
function theme_baitalgahwa_get_learning_page_context(int $userid): array {
    global $USER;
    $context = [];
    $user = ((int) $USER->id === $userid) ? $USER : \core_user::get_user($userid);
    $context['dashboard_ui'] = theme_baitalgahwa_get_dashboard_ui_strings();
    $welcomename = fullname($user);
    $context['dashboardwelcome'] = theme_baitalgahwa_safe_lang(
        'dashboardwelcome',
        'Welcome back, ' . $welcomename,
        $welcomename
    );
    $context['dashboard_featured_tag'] = get_string('dashboard_featured_tag', 'theme_baitalgahwa');
    $context['dashboard_overview_tab'] = get_string('dashboard_overview_tab', 'theme_baitalgahwa');
    $context['dashboard_intro_heading'] = get_string('dashboard_intro_heading', 'theme_baitalgahwa');
    $context['dashboard_intro_fallback'] = get_string('dashboard_intro_fallback', 'theme_baitalgahwa');
    $context['dashboard_announcement_title'] = get_string('dashboard_announcement_title', 'theme_baitalgahwa');
    $context['dashboard_announcement_body'] = get_string('dashboard_announcement_body', 'theme_baitalgahwa');
    $context['dashboard_activity_heading'] = get_string('dashboard_activity_heading', 'theme_baitalgahwa');
    $context['dashboard_activity_intro'] = get_string('dashboard_activity_intro', 'theme_baitalgahwa');
    $context['dashboard_members_link'] = get_string('dashboard_members_link', 'theme_baitalgahwa');
    $context['dashboard_stats'] = theme_baitalgahwa_get_dashboard_stats($userid);
    $context['dashboard_progress'] = theme_baitalgahwa_get_dashboard_progress_rows($userid, 10);
    $context['has_dashboard_progress'] = !empty($context['dashboard_progress']);
    $context['dashboard_donut'] = theme_baitalgahwa_get_dashboard_donut($userid);
    $context['dashboard_members'] = theme_baitalgahwa_get_dashboard_recent_users(8, $userid);
    $context['has_dashboard_members'] = !empty($context['dashboard_members']);
    $context['news_mail_subject'] = rawurlencode(get_string('dashboard_news_title', 'theme_baitalgahwa'));
    $context['mycourses'] = theme_baitalgahwa_get_featured_courses(16);
    $context['has_mycourses'] = !empty($context['mycourses']);
    $programmes = array_slice($context['mycourses'], 0, 4);
    // Always show four tiles in the row when at least one programme exists (pad by cycling).
    if (!empty($programmes)) {
        $i = 0;
        $n = count($programmes);
        while (count($programmes) < 4) {
            $programmes[] = array_merge($programmes[$i % $n]);
            $i++;
        }
    }
    $context['dashboard_programmes'] = $programmes;
    $context['has_dashboard_programmes'] = !empty($context['dashboard_programmes']);
    $context['dashboard_category_options'] = [[
        'value' => 'all',
        'label' => $context['dashboard_ui']['filterallcategories'],
        'selected' => true,
        'count' => $context['dashboard_donut']['total'],
        'color' => '#8a5a2b',
    ]];
    foreach ($context['dashboard_donut']['segments'] as $segment) {
        $context['dashboard_category_options'][] = [
            'value' => clean_param(\core_text::strtolower($segment['label']), PARAM_ALPHANUMEXT),
            'label' => $segment['label'],
            'count' => $segment['count'],
            'color' => $segment['color'],
        ];
    }
    $context['dashboard_calendar'] = theme_baitalgahwa_get_dashboard_calendar($userid, 0, $context['mycourses'], $context['dashboard_ui']);
    $context['dashboard_calendar_course_options'] = $context['dashboard_calendar']['courseoptions'];
    $context['dashboard_calendar_type_options'] = $context['dashboard_calendar']['typeoptions'];
    $context['dashboard_calendar_events_json'] = json_encode($context['dashboard_calendar']['eventdata']);
    $quizwidget = theme_baitalgahwa_get_dashboard_quiz_widget($userid, $context['dashboard_ui']);
    $context['dashboard_quiz_bars'] = $quizwidget['bars'];
    $context['dashboard_quiz_course_options'] = $quizwidget['courseoptions'];
    $context['dashboard_quiz_type_options'] = $quizwidget['quizoptions'];
    $context['dashboard_quiz_chart_json'] = json_encode($quizwidget['series']);
    $context['featuredcourse'] = [];
    $context['has_featuredcourse'] = false;
    $context['activity_courses'] = [];
    $context['has_activity_courses'] = false;
    $context['dashboard_intro_text'] = $context['dashboard_intro_fallback'];
    $context['dashboard_todo_items'] = [];
    $context['has_dashboard_todo'] = false;
    $context['dashboard_news_options'] = [
        [
            'value' => 'questions',
            'label' => get_string('dashboard_news_option_questions', 'theme_baitalgahwa'),
            'selected' => true,
        ],
        [
            'value' => 'feedback',
            'label' => get_string('dashboard_news_option_feedback', 'theme_baitalgahwa'),
        ],
        [
            'value' => 'support',
            'label' => get_string('dashboard_news_option_support', 'theme_baitalgahwa'),
        ],
    ];
    foreach ($context['dashboard_progress'] as $row) {
        if ($row['statuskey'] === 'done') {
            continue;
        }
        $context['dashboard_todo_items'][] = $row;
        if (count($context['dashboard_todo_items']) >= 3) {
            break;
        }
    }
    if (empty($context['dashboard_todo_items'])) {
        $context['dashboard_todo_items'] = array_slice($context['dashboard_progress'], 0, 3);
    }
    $context['has_dashboard_todo'] = !empty($context['dashboard_todo_items']);
    $context['featuredcourse'] = $context['mycourses'][0] ?? [];
    $context['has_featuredcourse'] = !empty($context['featuredcourse']);
    if (!empty($context['mycourses'])) {
        $activity = array_slice($context['mycourses'], 0, 8);
        $i = 0;
        $n = count($activity);
        if ($n > 0) {
            while (count($activity) < 8) {
                $activity[] = array_merge($activity[$i % $n]);
                $i++;
            }
        }
        $context['activity_courses'] = $activity;
        $context['has_activity_courses'] = !empty($context['activity_courses']);
        if (!empty($context['featuredcourse']['summary'])) {
            $context['dashboard_intro_text'] = $context['featuredcourse']['summary'];
        }
    } else if (!empty($context['dashboard_progress'])) {
        $context['featuredcourse'] = $context['dashboard_progress'][0];
        $context['has_featuredcourse'] = true;
    }
    $context['dashboard_news_mailto'] = '';
    return $context;
}

/**
 * User dashboard: aggregate stats (enrolled, in progress, completed, certificates).
 *
 * @param int $userid
 * @return array{enrolled: int, inprogress: int, completed: int, certificates: int, enrolled_fmt: string, ...}
 */
function theme_baitalgahwa_get_dashboard_stats(int $userid): array {
    global $DB, $CFG;
    if ($userid < 1) {
        return [
            'enrolled' => 0,
            'inprogress' => 0,
            'completed' => 0,
            'certificates' => 0,
            'enrolled_fmt' => '00',
            'inprogress_fmt' => '00',
            'completed_fmt' => '00',
            'certificates_fmt' => '00',
        ];
    }
    require_once($CFG->libdir . '/enrollib.php');
    require_once($CFG->libdir . '/completionlib.php');

    $courses = enrol_get_my_courses(
        'id, fullname, shortname, enablecompletion, category, summary, summaryformat, timecreated, sortorder, visible, enddate',
        'visible DESC, fullname ASC'
    );
    $enrolled = 0;
    $completed = 0;
    foreach ($courses as $course) {
        if ((int) $course->id === (int) SITEID) {
            continue;
        }
        $enrolled++;
        $cinfo = new \completion_info($course);
        $progress = null;
        if ($cinfo->is_enabled() && $cinfo->is_tracked_user($userid) && class_exists(\core_completion\progress::class)) {
            $progress = \core_completion\progress::get_course_progress_percentage($course, $userid);
        }
        if ($progress === 100) {
            $completed++;
        }
    }
    $inprogress = max(0, $enrolled - $completed);

    $certs = 0;
    if ($DB->get_manager()->table_exists('customcert_issues')) {
        $certs = (int) $DB->count_records('customcert_issues', ['userid' => $userid]);
    } else if ($DB->get_manager()->table_exists('tool_certificate_issue')) {
        $certs = (int) $DB->count_records('tool_certificate_issue', ['userid' => $userid]);
    }

    $pad = static function (int $n): string {
        return $n < 10 ? '0' . (string) $n : (string) $n;
    };

    return [
        'enrolled' => $enrolled,
        'inprogress' => $inprogress,
        'completed' => $completed,
        'certificates' => $certs,
        'enrolled_fmt' => $pad($enrolled),
        'inprogress_fmt' => $pad($inprogress),
        'completed_fmt' => $pad($completed),
        'certificates_fmt' => $pad($certs),
    ];
}

/**
 * Table rows: course + completion % and status.
 *
 * @param int $userid
 * @param int $max
 * @return array<int, array<string, mixed>>
 */
function theme_baitalgahwa_get_dashboard_progress_rows(int $userid, int $max = 10): array {
    global $CFG, $DB;
    require_once($CFG->libdir . '/enrollib.php');
    require_once($CFG->libdir . '/completionlib.php');
    if ($userid < 1) {
        return [];
    }
    $courses = enrol_get_my_courses(
        'id, fullname, shortname, enablecompletion, category, summary, summaryformat, timecreated, sortorder, visible, enddate',
        'visible DESC, fullname ASC',
        0,
        $max
    );
    $rows = [];
    foreach ($courses as $course) {
        if ((int) $course->id === (int) SITEID) {
            continue;
        }
        if (count($rows) >= $max) {
            break;
        }
        $cinfo = new \completion_info($course);
        $progress = 0.0;
        if ($cinfo->is_enabled() && $cinfo->is_tracked_user($userid) && class_exists(\core_completion\progress::class)) {
            $p = \core_completion\progress::get_course_progress_percentage($course, $userid);
            $progress = $p === null ? 0.0 : (float) $p;
        }
        if ($progress >= 100) {
            $sk = 'done';
        } else if ($progress > 0) {
            $sk = 'inprogress';
        } else {
            $sk = 'todo';
        }
        $ctx = \context_course::instance($course->id);
        $img = theme_baitalgahwa_get_course_image_url($course);
        $rows[] = [
            'id' => (int) $course->id,
            'fullname' => format_string($course->fullname, true, ['context' => $ctx]),
            'url' => (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
            'imageurl' => $img,
            'progress' => (int) round($progress),
            'progressint' => (int) round($progress),
            'status' => get_string('dashboard_status_' . $sk, 'theme_baitalgahwa'),
            'statuskey' => $sk,
        ];
    }
    return $rows;
}

/**
 * Donut chart: share of enrollments by course category.
 *
 * @param int $userid
 * @return array{total: int, segments: array<int, array{label: string, pct: float, color: string}>, conic: string}
 */
function theme_baitalgahwa_get_dashboard_donut(int $userid): array {
    global $CFG;
    require_once($CFG->libdir . '/enrollib.php');
    if ($userid < 1) {
        return [
            'total' => 0,
            'centerlabel' => '0',
            'segments' => [],
            'conic' => 'conic-gradient(from 0.14turn at 50% 50%, #e8e0d8 0deg 360deg)',
        ];
    }
    $courses = enrol_get_my_courses('id, category', 'visible ASC', 0, 0);
    $bycat = [];
    foreach ($courses as $c) {
        if ((int) $c->id === (int) SITEID) {
            continue;
        }
        $catid = (int) $c->category;
        if (!isset($bycat[$catid])) {
            $bycat[$catid] = 0;
        }
        $bycat[$catid]++;
    }
    $total = array_sum($bycat);
    $colours = ['#6F4E37', '#8B6F47', '#C9A227', '#A67C52'];
    if ($total < 1) {
        return [
            'total' => 0,
            'centerlabel' => '0',
            'segments' => [],
            'conic' => 'conic-gradient(from 0.14turn at 50% 50%, #e8e0d8 0deg 360deg)',
        ];
    }
    arsort($bycat);
    $slices = [];
    $i = 0;
    $other = 0;
    foreach ($bycat as $catid => $count) {
        if ($i < 3) {
            $cat = $catid ? \core_course_category::get($catid, IGNORE_MISSING) : null;
            $label = $cat ? $cat->get_formatted_name() : (string) get_string('other', 'moodle');
            $slices[] = ['label' => $label, 'count' => $count, 'cat' => $catid];
            $i++;
        } else {
            $other += $count;
        }
    }
    if ($other > 0) {
        $slices[] = [
            'label' => get_string('dashboard_others', 'theme_baitalgahwa'),
            'count' => $other,
            'cat' => 0,
        ];
    }
    $running = 0.0;
    $parts = [];
    $segments = [];
    foreach ($slices as $idx => $sl) {
        $pct = 100.0 * $sl['count'] / $total;
        $from = $running;
        $running += $pct;
        $col = $colours[$idx % count($colours)];
        // Degree-based stops + explicit origin (reliable ring vs. thin/squeezed arcs in some layouts).
        $parts[] = $col . ' ' . round($from * 3.6, 3) . 'deg ' . round($running * 3.6, 3) . 'deg';
        $segments[] = [
            'label' => $sl['label'],
            'count' => $sl['count'],
            'color' => $col,
        ];
    }
    $conic = 'conic-gradient(from 0.14turn at 50% 50%, ' . implode(', ', $parts) . ')';
    return [
        'total' => $total,
        'centerlabel' => (string) $total,
        'segments' => $segments,
        'conic' => $conic,
    ];
}

/**
 * Last six month labels for dashboard charts.
 *
 * @param string|float|int|null $timezone
 * @return array<int, array{from: int, to: int, label: string}>
 */
function theme_baitalgahwa_get_dashboard_month_slots($timezone): array {
    $slots = [];
    for ($i = 5; $i >= 0; $i--) {
        $ts = strtotime('-' . $i . ' months');
        $from = (int) usergetmidnight($ts, $timezone);
        $to = (int) usergetmidnight(strtotime('+1 month', $from), $timezone);
        if ($i === 0) {
            $to = time() + 3600;
        }
        $slots[] = [
            'from' => $from,
            'to' => $to,
            'label' => userdate($from, '%b', $timezone),
        ];
    }
    return $slots;
}

/**
 * Normalises chart counts into the dashboard bar structure.
 *
 * @param array<int, int> $counts
 * @param array<int, string> $labels
 * @return array<int, array{h: int, t: string, pct: int}>
 */
function theme_baitalgahwa_get_dashboard_bar_series(array $counts, array $labels): array {
    $counts = array_values($counts);
    $labels = array_values($labels);
    $max = max(1, max($counts ?: [0]));
    $bars = [];
    foreach ($labels as $idx => $label) {
        $value = (int) ($counts[$idx] ?? 0);
        $bars[] = [
            'h' => $value,
            't' => $label,
            'pct' => (int) round(100 * $value / $max),
        ];
    }
    return $bars;
}

/**
 * Quiz widget options and data series.
 *
 * @param int $userid
 * @param array<string, string> $ui
 * @return array{bars: array, courseoptions: array, quizoptions: array, series: array}
 */
function theme_baitalgahwa_get_dashboard_quiz_widget(int $userid, array $ui): array {
    global $DB, $CFG, $USER;
    $slots = theme_baitalgahwa_get_dashboard_month_slots($USER->timezone);
    $labels = array_column($slots, 'label');
    $emptybars = theme_baitalgahwa_get_dashboard_bar_series(array_fill(0, count($labels), 0), $labels);
    $base = [
        'bars' => $emptybars,
        'courseoptions' => [[
            'value' => 'all',
            'label' => $ui['filterallcourses'],
            'selected' => true,
        ]],
        'quizoptions' => [[
            'value' => 'all',
            'label' => $ui['filterallquizzes'],
            'selected' => true,
        ]],
        'series' => [],
    ];
    if ($userid < 1 || !$DB->get_manager()->table_exists('quiz_attempts') || !$DB->get_manager()->table_exists('quiz')) {
        return $base;
    }
    require_once($CFG->libdir . '/enrollib.php');
    $courses = enrol_get_my_courses('id, fullname', 'fullname ASC', 0, 0);
    $courseids = [];
    $coursenames = [];
    foreach ($courses as $course) {
        if ((int) $course->id === (int) SITEID) {
            continue;
        }
        $courseids[] = (int) $course->id;
        $coursenames[(int) $course->id] = format_string($course->fullname, true, ['context' => \context_course::instance($course->id)]);
    }
    if (!$courseids) {
        return $base;
    }
    list($insql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_QM);
    $quizzes = $DB->get_records_sql(
        "SELECT id, course, name
           FROM {quiz}
          WHERE course {$insql}
       ORDER BY course ASC, name ASC",
        $params
    );
    if (!$quizzes) {
        return $base;
    }
    $series = [];
    $base['courseoptions'] = [[
        'value' => 'all',
        'label' => $ui['filterallcourses'],
        'selected' => true,
    ]];
    foreach ($coursenames as $courseid => $coursename) {
        $base['courseoptions'][] = [
            'value' => (string) $courseid,
            'label' => $coursename,
        ];
    }
    foreach ($quizzes as $quiz) {
        $base['quizoptions'][] = [
            'value' => (string) $quiz->id,
            'label' => format_string($quiz->name, true, ['context' => \context_course::instance($quiz->course)]),
        ];
    }
    foreach ($quizzes as $quiz) {
        $counts = array_fill(0, count($slots), 0);
        foreach ($slots as $index => $slot) {
            $counts[$index] = (int) $DB->count_records_select(
                'quiz_attempts',
                'userid = ? AND quiz = ? AND preview = 0 AND timefinish > 0 AND timefinish >= ? AND timefinish < ?',
                [$userid, $quiz->id, $slot['from'], $slot['to']]
            );
        }
        $series[] = [
            'courseid' => (string) $quiz->course,
            'quizid' => (string) $quiz->id,
            'counts' => $counts,
        ];
    }
    $allcounts = array_fill(0, count($slots), 0);
    foreach ($series as $row) {
        foreach ($row['counts'] as $index => $count) {
            $allcounts[$index] += (int) $count;
        }
    }
    $base['bars'] = theme_baitalgahwa_get_dashboard_bar_series($allcounts, $labels);
    $base['series'] = [
        'labels' => $labels,
        'rows' => $series,
    ];
    return $base;
}

/**
 * Dashboard calendar widget with optional event metadata.
 *
 * @param int $userid
 * @param int $time
 * @param array<int, array<string, mixed>> $mycourses
 * @param array<string, string> $ui
 * @return array<string, mixed>
 */
function theme_baitalgahwa_get_dashboard_calendar(int $userid = 0, int $time = 0, array $mycourses = [], array $ui = []): array {
    global $DB, $USER;
    if ($time < 1) {
        $time = time();
    }
    $a = usergetdate($time);
    $m = (int) $a['mon'];
    $y = (int) $a['year'];
    $first = make_timestamp($y, $m, 1, 0, 0, 0);
    $nextmonth = make_timestamp($m === 12 ? $y + 1 : $y, $m === 12 ? 1 : $m + 1, 1, 0, 0, 0);
    $dow = (int) userdate($first, '%w', $USER->timezone);
    $daysin = (int) cal_days_in_month(CAL_GREGORIAN, $m, $y);
    $todaya = usergetdate(time());
    $iscurrent = ($m === (int) $todaya['mon'] && $y === (int) $todaya['year']);
    $tod = (int) $todaya['mday'];
    $weeks = [];
    $d = 1 - $dow;
    for ($w = 0; $w < 6; $w++) {
        $row = [];
        for ($c = 0; $c < 7; $c++) {
            if ($d >= 1 && $d <= $daysin) {
                $istoday = $iscurrent && $d === $tod;
                $row[] = ['day' => $d, 'istoday' => $istoday, 'inmonth' => true];
            } else {
                $row[] = ['day' => 0, 'istoday' => false, 'inmonth' => false];
            }
            $d++;
        }
        $weeks[] = $row;
        if ($d > $daysin) {
            break;
        }
    }
    $courseoptions = [[
        'value' => 'all',
        'label' => $ui['filterallcourses'] ?? 'All Courses',
        'selected' => true,
    ]];
    $courseids = [];
    foreach ($mycourses as $course) {
        if (empty($course['id']) || empty($course['fullname'])) {
            continue;
        }
        $courseids[] = (int) $course['id'];
        $courseoptions[] = [
            'value' => (string) $course['id'],
            'label' => $course['fullname'],
        ];
    }
    $typeoptions = [[
        'value' => 'all',
        'label' => $ui['filterallevents'] ?? 'All Events',
        'selected' => true,
    ]];
    $eventdata = [];
    if ($userid > 0 && $courseids && $DB->get_manager()->table_exists('event')) {
        list($insql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_QM);
        $params[] = $first;
        $params[] = $nextmonth;
        $events = $DB->get_records_sql(
            "SELECT id, courseid, timestart, eventtype
               FROM {event}
              WHERE courseid {$insql}
                AND timestart >= ?
                AND timestart < ?
           ORDER BY timestart ASC",
            $params
        );
        $types = [];
        foreach ($events as $event) {
            $day = (int) userdate($event->timestart, '%e', $USER->timezone);
            $type = trim((string) $event->eventtype);
            if ($type === '') {
                $type = 'general';
            }
            $types[$type] = ucwords(str_replace('_', ' ', $type));
            $eventdata[] = [
                'courseid' => (string) $event->courseid,
                'day' => $day,
                'type' => $type,
            ];
        }
        foreach ($types as $value => $label) {
            $typeoptions[] = [
                'value' => $value,
                'label' => $label,
            ];
        }
    }
    return [
        'weeks' => $weeks,
        'title' => userdate($time, '%B %Y', $USER->timezone),
        'courseoptions' => $courseoptions,
        'typeoptions' => $typeoptions,
        'eventdata' => $eventdata,
    ];
}

/**
 * Localized course role label for dashboard member cards.
 *
 * @param \context_course $context
 * @param int $userid
 * @return string
 */
function theme_baitalgahwa_dashboard_member_role_label(\context_course $context, int $userid): string {
    global $DB;
    $assignments = get_user_roles($context, $userid, false);
    if (empty($assignments)) {
        return get_string('dashboard_peer', 'theme_baitalgahwa');
    }
    $ra = reset($assignments);
    $role = $DB->get_record('role', ['id' => $ra->roleid]);
    if (!$role) {
        return get_string('dashboard_peer', 'theme_baitalgahwa');
    }
    $name = role_get_name($role, $context);
    if (is_string($name) && $name !== '' && strpos($name, '[[') === false) {
        return $name;
    }
    return get_string('dashboard_peer', 'theme_baitalgahwa');
}

/**
 * Formatted “joined” date for dashboard member cards (first access, else account created).
 *
 * @param int $userid
 * @return string empty if unknown
 */
function theme_baitalgahwa_dashboard_member_joined_display(int $userid): string {
    $user = \core_user::get_user($userid);
    $ts = 0;
    if (!empty($user->firstaccess)) {
        $ts = (int) $user->firstaccess;
    } else if (!empty($user->timecreated)) {
        $ts = (int) $user->timecreated;
    }
    if ($ts < 1) {
        return '';
    }
    return userdate($ts, get_string('strftimedate', 'langconfig'));
}

/**
 * Course IDs scanned for dashboard “latest members”, aligned with the training programme grid:
 * the viewer’s enrolled visible courses first, then other visible catalogue courses (same idea as
 * {@see theme_baitalgahwa_get_featured_courses()} when the grid is filled from the catalogue).
 *
 * @param int $userid
 * @param int $maxcourses Performance cap on how many courses we scan.
 * @return array<int, int>
 */
function theme_baitalgahwa_get_dashboard_members_course_ids(int $userid, int $maxcourses = 48): array {
    global $DB, $CFG;
    $seen = [];
    $ids = [];
    $add = static function (int $id) use (&$seen, &$ids, $maxcourses): void {
        if ($id < 2 || $id === (int) SITEID || isset($seen[$id])) {
            return;
        }
        if (count($ids) >= $maxcourses) {
            return;
        }
        $seen[$id] = true;
        $ids[] = $id;
    };

    require_once($CFG->libdir . '/enrollib.php');
    if (isloggedin() && !isguestuser()) {
        foreach (enrol_get_my_courses('id, visible', 'visible DESC, sortorder ASC') as $c) {
            if (empty($c->visible)) {
                continue;
            }
            $add((int) $c->id);
            if (count($ids) >= $maxcourses) {
                return $ids;
            }
        }
    }

    $more = max(64, $maxcourses * 6);
    $records = $DB->get_records_select(
        'course',
        'id > :siteid AND visible = 1',
        ['siteid' => SITEID],
        'sortorder ASC',
        'id',
        0,
        $more
    );
    foreach ($records as $c) {
        $add((int) $c->id);
        if (count($ids) >= $maxcourses) {
            break;
        }
    }
    return $ids;
}

/**
 * Peers enrolled in the same visible courses we use for the programme strip (enrolled + catalogue).
 *
 * @param int $limit
 * @param int $userid
 * @return array<int, array{fullname: string, profileurl: string, role: string, avatar: string, joined_display: string}>
 */
function theme_baitalgahwa_get_dashboard_recent_users(int $limit = 8, int $userid = 0): array {
    global $CFG, $USER, $OUTPUT;
    if ($limit < 1) {
        return [];
    }
    if ($userid < 1) {
        $userid = (int) $USER->id;
    }
    require_once($CFG->libdir . '/enrollib.php');
    $courseids = theme_baitalgahwa_get_dashboard_members_course_ids($userid, 48);
    $out = [];
    $seen = [$userid => true];
    foreach ($courseids as $courseid) {
        if (count($out) >= $limit) {
            break;
        }
        $context = \context_course::instance($courseid);
        $enrolled = get_enrolled_users(
            $context,
            '',
            0,
            'u.id, u.firstname, u.lastname, u.picture, u.imagealt, u.deleted, u.suspended, u.timecreated',
            'u.timecreated DESC, u.lastname ASC, u.firstname ASC',
            0,
            80
        );
        foreach ($enrolled as $u) {
            if (!empty($u->deleted) || !empty($u->suspended)) {
                continue;
            }
            if (isset($seen[(int) $u->id])) {
                continue;
            }
            if ((int) $u->id === $userid) {
                continue;
            }
            if (isguestuser($u) || (int) $u->id < 2) {
                continue;
            }
            $seen[(int) $u->id] = true;
            $user = \core_user::get_user($u->id);
            $out[] = [
                'fullname' => fullname($user),
                'profileurl' => (new \moodle_url('/user/view.php', ['id' => $u->id, 'course' => $courseid]))->out(false),
                'role' => theme_baitalgahwa_dashboard_member_role_label($context, (int) $u->id),
                'joined_display' => theme_baitalgahwa_dashboard_member_joined_display((int) $u->id),
                'avatar' => $OUTPUT->user_picture($user, [
                    'size' => 50,
                    'link' => false,
                    'class' => 'rounded-circle',
                    'alttext' => false,
                ]),
            ];
            if (count($out) >= $limit) {
                return $out;
            }
        }
    }
    return $out;
}

/**
 * True when the page URL path ends with a Moodle script (works for subdirectory installs).
 *
 * @param string $path Value from moodle_url::get_path() (no query string).
 * @param string $script Relative script path, e.g. "course/view.php".
 */
function theme_baitalgahwa_url_path_ends_with_script(string $path, string $script): bool {
    $script = ltrim($script, '/');
    return (bool) preg_match('#(^|/)' . preg_quote($script, '#') . '$#', $path);
}

/**
 * Course enrolment hub (enrol/index.php), including sites served under a URL prefix.
 */
function theme_baitalgahwa_is_course_enrol_hub_page(string $path): bool {
    global $PAGE;
    if (theme_baitalgahwa_url_path_ends_with_script($path, 'enrol/index.php')) {
        return true;
    }
    if ($path !== '' && strpos($path, 'enrol/index.php') !== false) {
        return true;
    }
    try {
        $full = $PAGE->url->out_omit_querystring(false);
        if (strpos($full, 'enrol/index.php') !== false) {
            return true;
        }
    } catch (\Throwable $e) {
        // PAGE may be incomplete during rare bootstrap ordering.
    }
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($script !== '' && strpos($script, 'enrol/index.php') !== false) {
        return true;
    }
    return isset($PAGE->pagetype) && $PAGE->pagetype === 'enrol-index';
}

/**
 * Context for the Bait course strip (hero + stats) on course home and course settings.
 *
 * Shown on /course/view.php (section 0), /course/edit.php, /user/index.php (participants),
 * and course enrol pages under /enrol/* (e.g. enrolment options).
 *
 * @param bool $forceenrolhub Skip URL routing and treat this request as the course enrol hub (last resort for RemUI / path quirks).
 * @return array
 */
function theme_baitalgahwa_get_course_dashboard_context(bool $forceenrolhub = false): array {
    global $PAGE, $DB, $CFG, $USER, $OUTPUT;
    if (empty($PAGE->course) || $PAGE->course->id <= SITEID) {
        return ['course_dashboard' => false];
    }
    $course = $PAGE->course;
    $context = \context_course::instance($course->id);
    $path = $PAGE->url->get_path();
    $isedit = false;
    $isparticipants = false;
    $isenrol = false;

    if ($forceenrolhub) {
        $isenrol = true;
    } else if (theme_baitalgahwa_url_path_ends_with_script($path, 'course/view.php')) {
        $section = $PAGE->url->get_param('section');
        if ($section !== null && (int) $section !== 0) {
            return ['course_dashboard' => false];
        }
        // Enrolled learners see the branded layout; so do staff who manage the course
        // but are not explicitly enrolled (common for admins QA-ing /course/view.php).
        $seehome = is_enrolled($context, $USER, '', true)
            || has_capability('moodle/course:update', $context);
        if (!$seehome) {
            return ['course_dashboard' => false];
        }
    } else if (theme_baitalgahwa_url_path_ends_with_script($path, 'course/edit.php')) {
        if (!$PAGE->url->get_param('id')) {
            return ['course_dashboard' => false];
        }
        if (!has_capability('moodle/course:update', $context)) {
            return ['course_dashboard' => false];
        }
        $isedit = true;
    } else if (theme_baitalgahwa_url_path_ends_with_script($path, 'user/index.php')) {
        $cid = (int) $PAGE->url->get_param('id');
        if ($cid !== (int) $course->id) {
            return ['course_dashboard' => false];
        }
        if (!has_capability('moodle/course:viewparticipants', $context)) {
            return ['course_dashboard' => false];
        }
        $isparticipants = true;
    } else if (theme_baitalgahwa_is_course_enrol_hub_page($path)) {
        // Course enrolment hub (e.g. /enrol/index.php?id=…) — same branded shell as course home.
        $isenrol = true;
    } else {
        return ['course_dashboard' => false];
    }

    require_once($CFG->libdir . '/completionlib.php');

    $courseimage = '';
    if (class_exists('\core_course\external\course_summary_exporter')) {
        $courseimage = (string) \core_course\external\course_summary_exporter::get_course_image($course);
    }
    if ($courseimage === '' && theme_baitalgahwa_is_certified_gahwa_specialist_course($course)) {
        $courseimage = theme_baitalgahwa_get_theme_image_url('course-gahwa-specialist-hero');
    }
    if ($courseimage === '') {
        $courseimage = theme_baitalgahwa_get_theme_image_url('course-activity-layout');
    }

    $instructor = get_string('course_instructor_default', 'theme_baitalgahwa');
    $instructoravatar = '';
    $teachers = get_enrolled_users($context, 'moodle/course:update', 0, 'u.id, u.firstname, u.lastname', 'u.lastname ASC, u.firstname ASC');
    if (!$teachers) {
        $teachers = get_enrolled_users($context, 'mod/assign:grade', 0, 'u.id, u.firstname, u.lastname', 'u.lastname ASC, u.firstname ASC');
    }
    if ($teachers) {
        $t = reset($teachers);
        $tuser = \core_user::get_user($t->id);
        $instructor = fullname($tuser, true);
        $instructoravatar = $OUTPUT->user_picture($tuser, [
            'size' => 64,
            'link' => false,
            'class' => 'rounded-circle baitalgahwa-course-hero__hostuserpic',
            'alttext' => true,
        ]);
    }

    $enrolled = count_enrolled_users($context);
    $cinfo = new \completion_info($course);
    $hascompletion = $cinfo->is_enabled();
    $statcompleted = null;
    $statinprogress = null;
    $statnottostart = null;
    if ($hascompletion) {
        $statcompleted = (int) $DB->count_records_select('course_completions',
            'course = ? AND timecompleted IS NOT NULL', ['course' => $course->id]);
        $statinprogress = (int) $DB->get_field_sql(
            "SELECT COUNT(DISTINCT u.id)
               FROM {user_enrolments} ue
               JOIN {enrol} e ON e.id = ue.enrolid AND e.courseid = :cid1
               JOIN {user} u ON u.id = ue.userid AND u.deleted = 0
               JOIN {user_lastaccess} ula ON ula.userid = u.id AND ula.courseid = :cid2
          LEFT JOIN {course_completions} cc
                 ON cc.userid = u.id AND cc.course = :cid3 AND cc.timecompleted IS NOT NULL
              WHERE ue.status = 0 AND cc.id IS NULL",
            ['cid1' => $course->id, 'cid2' => $course->id, 'cid3' => $course->id]
        );
        $statnottostart = (int) $DB->get_field_sql(
            "SELECT COUNT(DISTINCT u.id)
               FROM {user_enrolments} ue
               JOIN {enrol} e ON e.id = ue.enrolid AND e.courseid = :cid1
               JOIN {user} u ON u.id = ue.userid AND u.deleted = 0
          LEFT JOIN {user_lastaccess} ula ON ula.userid = u.id AND ula.courseid = :cid2
              WHERE ue.status = 0 AND ula.id IS NULL",
            ['cid1' => $course->id, 'cid2' => $course->id]
        );
    }

    $twodigit = static function (int $n): string {
        $n = max(0, min(99, $n));
        return $n < 10 ? '0' . (string) $n : (string) $n;
    };

    $enrolledcap = max(0, min(99, (int) $enrolled));
    $enrolledf = $twodigit($enrolledcap);

    $ctaurl = (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(false);
    $summary = '';
    if (!empty($course->summary)) {
        $summary = format_text($course->summary, $course->summaryformat, ['context' => $context, 'filter' => true]);
    }

    $manageurl = theme_baitalgahwa_get_manage_course_url((int) $course->category);

    $ctx = [
        'course_dashboard' => true,
        'course_fullname' => format_string($course->fullname, true, ['context' => $context]),
        'course_summary' => $summary,
        'course_image' => $courseimage,
        'course_instructor' => $instructor,
        'course_instructor_avatar' => $instructoravatar,
        'course_hero_hostline' => get_string('mycourse_card_instructor_host', 'theme_baitalgahwa'),
        'stat_enrolled' => $enrolled,
        'stat_enrolled_f' => $enrolledf,
        'stat_completed_f' => $hascompletion && $statcompleted !== null ? $twodigit((int) $statcompleted) : '—',
        'stat_inprogress_f' => $hascompletion && $statinprogress !== null ? $twodigit((int) $statinprogress) : '—',
        'stat_nottostart_f' => $hascompletion && $statnottostart !== null ? $twodigit((int) $statnottostart) : '—',
        'has_completion' => $hascompletion,
        'course_ctaurl' => $ctaurl,
        'course_ctalabel' => get_string('course_enter', 'theme_baitalgahwa'),
        'course_strip_settings_layout' => false,
        'course_strip_participants' => false,
        'course_strip_enrol' => $isenrol,
    ];
    if ($isedit) {
        $ctx['course_strip_settings_layout'] = true;
        $ctx['course_ctalabel'] = get_string('course_viewpage', 'theme_baitalgahwa');
    }
    if ($isparticipants) {
        $ctx['course_strip_participants'] = true;
        $ctx['course_ctaurl'] = $manageurl;
        $ctx['course_ctalabel'] = get_string('course_manage', 'theme_baitalgahwa');
    }
    if ($isenrol) {
        $enrolurl = new \moodle_url('/enrol/index.php', ['id' => $course->id]);
        $ctx['course_ctaurl'] = $enrolurl->out(false) . '#page-content';
        $ctx['course_ctalabel'] = get_string('course_enrol_cta', 'theme_baitalgahwa');
    }
    return $ctx;
}

/**
 * Shared “drawer” style template context (columns2, dashboard, frontpage).
 *
 * @return array
 */
function theme_baitalgahwa_bootstrap_drawer_template_context() {
    global $OUTPUT, $PAGE, $SITE, $CFG, $USER;
    require_once($CFG->libdir . '/behat/lib.php');
    require_once($CFG->dirroot . '/course/lib.php');
    $coursedashboardctx = theme_baitalgahwa_get_course_dashboard_context();
    if (empty($coursedashboardctx['course_dashboard']) && !empty($PAGE->course) && $PAGE->course->id > SITEID) {
        $sn = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($sn !== '' && strpos($sn, 'enrol/index.php') !== false) {
            $coursedashboardctx = theme_baitalgahwa_get_course_dashboard_context(true);
        }
    }
    if (isloggedin()) {
        $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
        $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
    } else {
        $courseindexopen = false;
        $blockdraweropen = false;
    }
    if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
        $blockdraweropen = true;
    }
    $extraclasses = ['uses-drawers'];
    $scriptname = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if (strpos($scriptname, '/theme/baitalgahwa/about.php') !== false) {
        $extraclasses[] = 'bag-about-body';
    }
    if ($PAGE->pagelayout === 'mydashboard' && !is_siteadmin($USER)) {
        $extraclasses[] = 'bag-figma-user-dashboard';
    }
    if ($courseindexopen) {
        $extraclasses[] = 'drawer-open-index';
    }
    $blockshtml = $OUTPUT->blocks('side-pre');
    $addblockbutton = $OUTPUT->addblockbutton();
    $hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
    if (!$hasblocks) {
        $blockdraweropen = false;
    }
    $courseindex = \core_course_drawer();
    if (!$courseindex) {
        $courseindexopen = false;
    }
    if (!empty($coursedashboardctx['course_dashboard'])) {
        $extraclasses[] = 'baitalgahwa-course-dashboard';
        if (!empty($coursedashboardctx['course_strip_settings_layout'])) {
            $extraclasses[] = 'baitalgahwa-course-settings';
        }
        if (!empty($coursedashboardctx['course_strip_participants'])) {
            $extraclasses[] = 'baitalgahwa-course-participants';
        }
        if (!empty($coursedashboardctx['course_strip_enrol'])) {
            $extraclasses[] = 'baitalgahwa-course-enrol';
        }
    }
    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $forceblockdraweropen = $OUTPUT->firstview_fakeblocks();
    $secondarynavigation = false;
    $overflow = '';
    if ($PAGE->has_secondary_navigation()) {
        $tablistnav = $PAGE->has_tablist_secondary_navigation();
        $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
        $secondarynavigation = $moremenu->export_for_template($OUTPUT);
        $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
        if (!is_null($overflowdata)) {
            $overflow = $overflowdata->export_for_template($OUTPUT);
        }
    }
    $primary = new \core\navigation\output\primary($PAGE);
    $renderer = $PAGE->get_renderer('core');
    $primarymenu = $primary->export_for_template($renderer);
    $buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
    $regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;
    $header = $PAGE->activityheader;
    $headercontent = $header->export_for_template($renderer);
    $context = [
        'sitename' => format_string($SITE->shortname, true, ['context' => \context_course::instance(SITEID), 'escape' => false]),
        'output' => $OUTPUT,
        'sidepreblocks' => $blockshtml,
        'hasblocks' => $hasblocks,
        'bodyattributes' => $bodyattributes,
        'courseindexopen' => $courseindexopen,
        'blockdraweropen' => $blockdraweropen,
        'courseindex' => $courseindex,
        'primarymoremenu' => $primarymenu['moremenu'],
        'secondarymoremenu' => $secondarynavigation ?: false,
        'mobileprimarynav' => $primarymenu['mobileprimarynav'],
        'usermenu' => $primarymenu['user'],
        'langmenu' => $primarymenu['lang'],
        'forceblockdraweropen' => $forceblockdraweropen,
        'regionmainsettingsmenu' => $regionmainsettingsmenu,
        'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
        'overflow' => $overflow,
        'headercontent' => $headercontent,
        'addblockbutton' => $addblockbutton,
    ];
    $context = array_merge($context, theme_baitalgahwa_get_footer_context());
    $context = array_merge($context, theme_baitalgahwa_get_branding_context());
    $abouturl = new \moodle_url('/theme/baitalgahwa/about.php');
    $currentpath = $PAGE->url ? $PAGE->url->get_path(false) : '';
    $aboutpath = $abouturl->get_path(false);
    $context['nav_about_active'] = ($currentpath === $aboutpath);
    $context['nav_home_active'] = !$context['nav_about_active'];
    $context['config'] = [
        'wwwroot' => $CFG->wwwroot,
        'homeurl' => (new \moodle_url('/'))->out(false),
        'dashboardurl' => (new \moodle_url('/my/'))->out(false),
        'mycoursesurl' => (new \moodle_url('/my/courses.php'))->out(false),
        'searchurl' => (new \moodle_url('/course/search.php'))->out(false),
        'notificationsurl' => (new \moodle_url('/message/output/popup/notifications.php'))->out(false),
        'messagesurl' => (new \moodle_url('/message/index.php'))->out(false),
        'abouturl' => $abouturl->out(false),
        'trainingurl' => (new \moodle_url('/', ['section' => 'programs']))->out(false) . '#bag-footer-programs',
        'contacturl' => (new \moodle_url('/', ['section' => 'contact']))->out(false) . '#bag-footer-contact',
        'loginurl' => (new \moodle_url('/login/index.php'))->out(false),
        'signupurl' => (new \moodle_url('/login/signup.php'))->out(false),
    ];
    $label = get_string('enrol_intro_heading', 'theme_baitalgahwa');
    $jsonlabel = json_encode($label, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    $context['enrol_intro_heading_json'] = ($jsonlabel !== false) ? $jsonlabel : '"Introduction"';
    $context = array_merge($context, $coursedashboardctx);
    return $context;
}

/**
 * Pre-resolved catalogue labels for the My courses template (never shows raw [[string]] if language cache lags).
 *
 * @return array<string, string>
 */
function theme_baitalgahwa_get_mycourses_page_labels(): array {
    $c = 'theme_baitalgahwa';
    return [
        'mc_toolbar_aria' => theme_baitalgahwa_string_or_default('mycourses_toolbar_aria', 'My courses actions', $c),
        'mc_tab_all' => theme_baitalgahwa_string_or_default('mycourses_tab_all', 'All', $c),
        'mc_tab_inprogress' => theme_baitalgahwa_string_or_default('mycourses_tab_inprogress', 'In progress', $c),
        'mc_tab_starred' => theme_baitalgahwa_string_or_default('mycourses_tab_starred', 'Starred', $c),
        'mc_tab_removed' => theme_baitalgahwa_string_or_default('mycourses_tab_removed', 'Removed from view', $c),
        'mc_search_placeholder' => theme_baitalgahwa_string_or_default('mycourses_search_placeholder', 'Search courses...', $c),
        'mc_filter' => theme_baitalgahwa_string_or_default('mycourses_filter', 'Filter', $c),
        'mc_sort_by' => theme_baitalgahwa_string_or_default('mycourses_sort_by', 'Sort by', $c),
        'mc_sort_latest' => theme_baitalgahwa_string_or_default('mycourses_sort_latest', 'Latest', $c),
        'mc_columns' => theme_baitalgahwa_string_or_default('mycourses_columns', 'Column', $c),
        'mc_course_new' => theme_baitalgahwa_string_or_default('course_new', 'New', $c),
        'mc_available' => theme_baitalgahwa_string_or_default('mycourse_available', 'Available', $c),
        'mc_dates_hint' => theme_baitalgahwa_string_or_default('mycourse_dates_hint', 'Open for enrolment', $c),
        'mc_gotocourse' => theme_baitalgahwa_string_or_default('gotocourse', 'View course', $c),
        'mc_nocourses' => theme_baitalgahwa_string_or_default('nocourses', 'No published courses to show yet.', $c),
    ];
}

/**
 * Toolbar for the My courses page: title + Manage / Create (same capability pattern as myoverview).
 *
 * @return array{ mycourses_toolbar: bool, mycourses_title: string, managecourseurl: string, createcourseurl: string, canmanagecourses: bool, cancreatecourse: bool, managecoursetext: string, createcoursetext: string }
 */
function theme_baitalgahwa_get_mycourses_toolbar_context(): array {
    $coursecat = \core_course_category::user_top();
    $manageurl = '';
    $createurl = '';
    $canmanage = false;
    $cancreate = false;
    if ($coursecat) {
        if ($cat = \core_course_category::get_nearest_editable_subcategory($coursecat, ['manage'])) {
            $canmanage = true;
            $manageurl = theme_baitalgahwa_get_manage_course_url((int) $cat->id);
        }
        if ($cat = \core_course_category::get_nearest_editable_subcategory($coursecat, ['create'])) {
            $cancreate = true;
            $createurl = (new \moodle_url('/course/edit.php', ['category' => $cat->id]))->out(false);
        }
    }
    $mycoursestitle = get_string('mycourses', 'moodle');
    if (preg_match('/^\[\[[^\]]+\]\]$/', (string) $mycoursestitle)) {
        $mycoursestitle = 'My courses';
    }
    return [
        'mycourses_toolbar' => true,
        'mycourses_title' => $mycoursestitle,
        'managecourseurl' => $manageurl,
        'createcourseurl' => $createurl,
        'canmanagecourses' => $canmanage,
        'cancreatecourse' => $cancreate,
        'managecoursetext' => theme_baitalgahwa_string_or_default('mycourses_manage_btn', 'Manage course'),
        'createcoursetext' => theme_baitalgahwa_string_or_default('mycourses_createcourse', 'Create course'),
    ];
}

/**
 * Branding URLs for the navbar and footer.
 *
 * @return array
 */
function theme_baitalgahwa_get_branding_context(): array {
    $theme = \theme_config::load('baitalgahwa');
    $logourl = $theme->setting_file_url('logo', 'logo');
    return [
        'themelogourl' => $logourl ? (string) $logourl : theme_baitalgahwa_get_theme_image_url('logo'),
        'footerflowerurl' => theme_baitalgahwa_get_theme_image_url('footer-flower'),
    ];
}
