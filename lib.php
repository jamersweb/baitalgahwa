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
        'footerabout' => $t->footerabout ?? '',
        'footeremail' => $t->footeremail ?? '',
        'footerphone' => $t->footerphone ?? '',
        'footeraddress' => $t->footeraddress ?? '',
        'facebook' => $t->footerfacebook ?? '',
        'instagram' => $t->footerinstagram ?? '',
        'linkedin' => $t->footerlinkedin ?? '',
        'youtube' => $t->footeryoutube ?? '',
        'twitter' => $t->footertwitter ?? '',
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
    return [
        'herotitle' => !empty($t->herotitle) ? $t->herotitle : 'Bait Al Gahwa',
        'herosubtitle' => !empty($t->herosubtitle) ? $t->herosubtitle : get_string('herosubtitledefault', 'theme_baitalgahwa'),
        'heroprimary' => $t->heroprimarytext ?? get_string('explore', 'theme_baitalgahwa'),
        'heroprimaryurl' => $t->heroprimaryurl ?? '/course',
        'herosecondary' => $t->herosecondarytext ?? '',
        'herosecondaryurl' => $t->herosecondaryurl ?? '/login/index.php',
        'heroimageurl' => $herourl ? (string) $herourl : '',
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
 * Resolves a course image URL for cards if an overview file exists.
 *
 * @param stdClass $course
 * @return string
 */
function theme_baitalgahwa_get_course_image_url($course): string {
    if (empty($course->id)) {
        return '';
    }
    $context = \context_course::instance($course->id);
    $fs = get_file_storage();
    $files = $fs->get_area_files(
        $context->id,
        'course',
        'overviewfiles',
        0,
        'filesize > 0',
        'filesize, timemodified, filename',
        0
    );
    if (!$files) {
        return '';
    }
    $file = reset($files);
    if (!$file) {
        return '';
    }
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
    global $DB, $CFG, $USER;
    require_once($CFG->libdir . '/modinfolib.php');
    $out = [];
    if (isloggedin() && !isguestuser()) {
        $mycourses = enrol_get_my_courses(
            'summary, summaryformat, enddate, category, timecreated',
            'visible DESC, fullname ASC'
        );
        foreach ($mycourses as $c) {
            if (count($out) >= $max) {
                break;
            }
            $out[] = theme_baitalgahwa_format_course_for_template($c);
        }
    }
    if (count($out) >= $max) {
        return $out;
    }
    $fields = 'id, category, fullname, shortname, visible, summary, sortorder, summaryformat, timecreated';
    $sql = "id > :siteid AND visible = 1";
    $records = $DB->get_records_select('course', $sql, ['siteid' => SITEID], 'sortorder ASC', $fields, 0, $max * 3);
    foreach ($records as $c) {
        if (count($out) >= $max) {
            break;
        }
        if ((int) $c->id === (int) SITEID) {
            continue;
        }
        $out[] = theme_baitalgahwa_format_course_for_template($c);
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
    $summary = '';
    if (!empty($c->summary)) {
        $summary = format_text($c->summary, $c->summaryformat ?? FORMAT_HTML, ['context' => \context_course::instance($c->id)]);
    }
    if (mb_strlen(preg_replace('/\s+/', ' ', html_to_text($summary, 0))) > 0) {
        $short = shorten_text(html_to_text($summary, 0), 120);
    } else {
        $short = '';
    }
    $catname = '';
    if (!empty($c->category)) {
        $cat = \core_course_category::get($c->category, IGNORE_MISSING, true);
        if ($cat) {
            $catname = $cat->get_formatted_name();
        }
    }
    $timecreated = isset($c->timecreated) ? (int) $c->timecreated : 0;
    $isnew = $timecreated > 0 && (time() - $timecreated) < 30 * DAYSECS;
    return [
        'id' => (int) $c->id,
        'url' => (new \moodle_url('/course/view.php', ['id' => $c->id]))->out(false),
        'fullname' => format_string($c->fullname, true, ['context' => \context_course::instance($c->id)]),
        'summary' => $short,
        'categoryname' => $catname,
        'imageurl' => theme_baitalgahwa_get_course_image_url($c),
        'isnew' => $isnew,
    ];
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
            'conic' => 'conic-gradient(#e8e0d8 0% 100%)',
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
            'conic' => 'conic-gradient(#e8e0d8 0% 100%)',
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
        $parts[] = $col . ' ' . round($from, 2) . '% ' . round($running, 2) . '%';
        $segments[] = [
            'label' => $sl['label'],
            'count' => $sl['count'],
            'color' => $col,
        ];
    }
    $conic = 'conic-gradient(' . implode(', ', $parts) . ')';
    return [
        'total' => $total,
        'centerlabel' => (string) $total,
        'segments' => $segments,
        'conic' => $conic,
    ];
}

/**
 * Last six months quiz attempt counts for a small bar chart.
 *
 * @param int $userid
 * @return array<int, array{h: int, t: string}>
 */
function theme_baitalgahwa_get_dashboard_quiz_bars(int $userid): array {
    global $DB, $USER;
    if ($userid < 1 || !$DB->get_manager()->table_exists('quiz_attempts')) {
        $empty = [];
        for ($i = 5; $i >= 0; $i--) {
            $ts = strtotime('-' . $i . ' months');
            $empty[] = ['h' => 0, 't' => userdate($ts, '%b', $USER->timezone), 'pct' => 0];
        }
        return $empty;
    }
    $out = [];
    for ($i = 5; $i >= 0; $i--) {
        $t = strtotime('-' . $i . ' months');
        $from = (int) usergetmidnight($t, $USER->timezone);
        $to = (int) usergetmidnight(strtotime('+1 month', $from), $USER->timezone);
        if ($i === 0) {
            $to = time() + 3600;
        }
        $c = (int) $DB->count_records_select(
            'quiz_attempts',
            'userid = ? AND preview = 0 AND timefinish > 0 AND timefinish >= ? AND timefinish < ?',
            [$userid, $from, $to]
        );
        $out[] = [
            'h' => $c,
            't' => userdate($from, '%b', $USER->timezone),
        ];
    }
    $max = max(1, max(array_map(static function ($a) {
        return $a['h'];
    }, $out)));
    foreach (array_keys($out) as $k) {
        $out[$k]['pct'] = (int) round(100 * $out[$k]['h'] / $max);
    }
    return $out;
}

/**
 * Calendar grid (current month) for dashboard widget.
 *
 * @return array{weeks: array, title: string}
 */
function theme_baitalgahwa_get_dashboard_calendar(int $time = 0): array {
    global $USER;
    if ($time < 1) {
        $time = time();
    }
    $a = usergetdate($time);
    $m = (int) $a['mon'];
    $y = (int) $a['year'];
    $first = make_timestamp($y, $m, 1, 0, 0, 0);
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
    return ['weeks' => $weeks, 'title' => userdate($time, '%B %Y', $USER->timezone)];
}

/**
 * Co-learners from the user’s enrolled courses (not site-wide for privacy).
 *
 * @param int $limit
 * @param int $userid
 * @return array<int, array{fullname: string, profileurl: string, role: string, avatar: string}>
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
    $myc = enrol_get_my_courses('id, visible', 'visible DESC', 0, 8);
    $out = [];
    $seen = [$userid => true];
    foreach ($myc as $course) {
        if ((int) $course->id === (int) SITEID) {
            continue;
        }
        if (count($out) >= $limit) {
            break;
        }
        $context = \context_course::instance($course->id);
        $enrolled = get_enrolled_users(
            $context,
            '',
            0,
            'u.id, u.firstname, u.lastname, u.picture, u.imagealt, u.deleted, u.suspended',
            'lastname ASC, firstname ASC',
            0,
            20
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
                'profileurl' => (new \moodle_url('/user/view.php', ['id' => $u->id, 'course' => $course->id]))->out(false),
                'role' => get_string('dashboard_peer', 'theme_baitalgahwa'),
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
 * Context for the Bait course strip (hero + stats) on course home and course settings.
 *
 * Shown on /course/view.php (section 0), /course/edit.php (existing course), and /user/index.php (participants).
 *
 * @return array
 */
function theme_baitalgahwa_get_course_dashboard_context(): array {
    global $PAGE, $DB, $CFG, $USER, $OUTPUT;
    if (empty($PAGE->course) || $PAGE->course->id <= SITEID) {
        return ['course_dashboard' => false];
    }
    $course = $PAGE->course;
    $context = \context_course::instance($course->id);
    $path = $PAGE->url->get_path();
    $isedit = false;
    $isparticipants = false;

    if ($path === '/course/view.php') {
        $section = $PAGE->url->get_param('section');
        if ($section !== null && (int) $section !== 0) {
            return ['course_dashboard' => false];
        }
        if (!is_enrolled($context, $USER, '', true)) {
            return ['course_dashboard' => false];
        }
    } else if ($path === '/course/edit.php') {
        if (!$PAGE->url->get_param('id')) {
            return ['course_dashboard' => false];
        }
        if (!has_capability('moodle/course:update', $context)) {
            return ['course_dashboard' => false];
        }
        $isedit = true;
    } else if ($path === '/user/index.php') {
        $cid = (int) $PAGE->url->get_param('id');
        if ($cid !== (int) $course->id) {
            return ['course_dashboard' => false];
        }
        if (!has_capability('moodle/course:viewparticipants', $context)) {
            return ['course_dashboard' => false];
        }
        $isparticipants = true;
    } else {
        return ['course_dashboard' => false];
    }

    require_once($CFG->libdir . '/completionlib.php');

    $courseimage = '';
    if (class_exists('\core_course\external\course_summary_exporter')) {
        $courseimage = (string) \core_course\external\course_summary_exporter::get_course_image($course);
    }

    $instructor = get_string('course_instructor_default', 'theme_baitalgahwa');
    $teachers = get_enrolled_users($context, 'moodle/course:update', 0, 'u.id, u.firstname, u.lastname', 'u.lastname ASC, u.firstname ASC');
    if (!$teachers) {
        $teachers = get_enrolled_users($context, 'mod/assign:grade', 0, 'u.id, u.firstname, u.lastname', 'u.lastname ASC, u.firstname ASC');
    }
    if ($teachers) {
        $t = reset($teachers);
        $instructor = fullname($t, true);
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
    return $ctx;
}

/**
 * Shared “drawer” style template context (columns2, dashboard, frontpage).
 *
 * @return array
 */
function theme_baitalgahwa_bootstrap_drawer_template_context() {
    global $OUTPUT, $PAGE, $SITE, $CFG;
    require_once($CFG->libdir . '/behat/lib.php');
    require_once($CFG->dirroot . '/course/lib.php');
    $coursedashboardctx = theme_baitalgahwa_get_course_dashboard_context();
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
    $context['config'] = [
        'wwwroot' => $CFG->wwwroot,
        'homeurl' => (new \moodle_url('/'))->out(false),
    ];
    $context = array_merge($context, $coursedashboardctx);
    return $context;
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
    return [
        'mycourses_toolbar' => true,
        'mycourses_title' => get_string('mycourses', 'moodle'),
        'managecourseurl' => $manageurl,
        'createcourseurl' => $createurl,
        'canmanagecourses' => $canmanage,
        'cancreatecourse' => $cancreate,
        'managecoursetext' => get_string('managecourses'),
        'createcoursetext' => get_string('mycourses_createcourse', 'theme_baitalgahwa'),
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
        'themelogourl' => $logourl ? (string) $logourl : '',
    ];
}
