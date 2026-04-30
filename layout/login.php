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
 * Login page layout: split view + form card.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $SESSION;

$path = (string) $PAGE->url->get_path();
$is_signup = (bool) preg_match('~/login/signup\.php$~', $path) || (bool) preg_match('~/signup\.php$~', $path);
$cansignup = !empty($CFG->registerauth);

// After self-registration (and email confirmation when used), send users to the dashboard (/my/)
// instead of the site home. auth_email stores $SESSION->wantsurl in the new user's preferences.
if ($is_signup) {
    $SESSION->wantsurl = (new \moodle_url('/my/index.php'))->out(false);
}

$bodyattributes = $OUTPUT->body_attributes(['baitalgahwa-login']);
$context = [
    'sitename' => format_string($SITE->fullname, true, ['context' => context_course::instance(SITEID), 'escape' => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'is_signup_page' => $is_signup,
    'cansignup' => $cansignup,
    'signupurl' => (new \moodle_url('/login/signup.php'))->out(false),
    'loginindexurl' => (new \moodle_url('/login/index.php'))->out(false),
    // JSON for {{#js}}; do not use {{#str}} inside JavaScript (Mustache nesting error).
    'divideror_json' => json_encode(get_string('divideror', 'theme_baitalgahwa'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
];
$herodefault = theme_baitalgahwa_get_hero_context();
$authhero = theme_baitalgahwa_get_auth_page_hero_context();
$context = array_merge($context, $herodefault, theme_baitalgahwa_get_footer_context(), theme_baitalgahwa_get_branding_context());
$context['herotitle'] = $authhero['herotitle'];
$context['herosubtitle'] = $authhero['herosubtitle'];
$context['heroimageurl'] = $authhero['heroimageurl'] !== ''
    ? $authhero['heroimageurl']
    : theme_baitalgahwa_get_auth_page_fallback_image($is_signup);
$context['config'] = [
    'wwwroot' => $CFG->wwwroot,
    'homeurl' => (new \moodle_url('/'))->out(false),
];

echo $OUTPUT->render_from_template('theme_baitalgahwa/login', $context);
