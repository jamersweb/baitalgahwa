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
 * Bait Al Gahwa — core renderer; extends Boost for safe overrides.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_baitalgahwa\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Core renderer
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Instructor chip and JS read mode/hint for third-line semantics on every page.
     *
     * @param string|string[] $additionalclasses
     * @return string
     */
    public function body_attributes($additionalclasses = []) {
        $attrs = parent::body_attributes($additionalclasses);
        $line = \theme_baitalgahwa_get_instructor_chipline_mode();
        $hint = s(\theme_baitalgahwa_get_instructor_chipline_hint());
        return $attrs . ' data-bag-instructor-line="' . s($line) . '" data-bag-instructor-hint="' . $hint . '"';
    }
}
