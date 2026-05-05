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
 * Public About page for Bait Al Gahwa.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$PAGE->set_url(new \moodle_url('/theme/baitalgahwa/about.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_pagelayout('base');
$PAGE->set_title(format_string($SITE->fullname) . ': About Us');
$PAGE->set_heading(format_string($SITE->fullname));

$context = theme_baitalgahwa_bootstrap_drawer_template_context();
$context['about_hero_url'] = theme_baitalgahwa_get_theme_image_url('hero-workshop-bg');
$context['about_coffee_url'] = theme_baitalgahwa_get_theme_image_url('auth-signup-hero');
$context['about_activity_url'] = theme_baitalgahwa_get_theme_image_url('course-activity-layout');
$context['about_member_url'] = theme_baitalgahwa_get_theme_image_url('member-card');

echo $OUTPUT->render_from_template('theme_baitalgahwa/about', $context);
