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
 * Public Contact page for Bait Al Gahwa.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$PAGE->set_url(new \moodle_url('/theme/baitalgahwa/contact.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_pagelayout('base');
$PAGE->set_title(format_string($SITE->fullname) . ': Contact Us');
$PAGE->set_heading(format_string($SITE->fullname));

$context = theme_baitalgahwa_bootstrap_drawer_template_context();
$context['contact_hero_url'] = theme_baitalgahwa_get_theme_image_url('hero-workshop-bg');
$context['contact_location_url'] = theme_baitalgahwa_get_theme_image_url('house-of-artisans');
$context['contact_email_display'] = !empty($context['footeremail']) ? $context['footeremail'] : 'BaitAlGahwa@ACTechnology.ae';
$context['contact_phone_display'] = !empty($context['footerphone']) ? $context['footerphone'] : '+971 52 442 0444';
$context['contact_location_display'] = !empty($context['footeraddress']) ? $context['footeraddress'] : 'Abu Dhabi';
$context['contact_instagram_display'] = '@baitalgahwa.ae';
$context['contact_directions_url'] = 'https://www.google.com/maps/search/?api=1&query=House%20of%20Artisans%20Al%20Hosn%20Abu%20Dhabi';

echo $OUTPUT->render_from_template('theme_baitalgahwa/contact', $context);
