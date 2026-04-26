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
 * Site home: hero, featured courses, then standard drawer content.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$context = theme_baitalgahwa_bootstrap_drawer_template_context();
$context = array_merge($context, theme_baitalgahwa_get_hero_context());
$context['featured_courses'] = theme_baitalgahwa_get_featured_courses(8);
$context['has_courses'] = !empty($context['featured_courses']);
$context['whytitle'] = get_string('whybait', 'theme_baitalgahwa');
$context['featuretitle'] = get_string('featured', 'theme_baitalgahwa');
$wstat1 = get_string('statslearners', 'theme_baitalgahwa');
$wstat2 = get_string('statscourses', 'theme_baitalgahwa');
$context['stats'] = [
    ['label' => $wstat1, 'value' => (string) (count($context['featured_courses']) > 0 ? '240+' : '0')],
    ['label' => $wstat2, 'value' => (string) count($context['featured_courses'])],
];

echo $OUTPUT->render_from_template('theme_baitalgahwa/frontpage', $context);
