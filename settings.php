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
 * Bait Al Gahwa — theme admin settings.
 *
 * @package   theme_baitalgahwa
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new admin_settingpage('theme_baitalgahwa', get_string('configtitle', 'theme_baitalgahwa'));

    $name = 'theme_baitalgahwa/brandingsettings';
    $heading = new admin_setting_heading('theme_baitalgahwa/brandingsettings', get_string('settings_branding', 'theme_baitalgahwa'), '');
    $settings->add($heading);

    $setting = new admin_setting_configstoredfile(
        'theme_baitalgahwa/logo',
        get_string('logo', 'theme_baitalgahwa'),
        get_string('logodesc', 'theme_baitalgahwa'),
        'logo',
        0,
        ['maxfiles' => 1, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $setting = new admin_setting_configstoredfile(
        'theme_baitalgahwa/favicon',
        get_string('favicon', 'theme_baitalgahwa'),
        get_string('favicondesc', 'theme_baitalgahwa'),
        'favicon',
        0,
        ['maxfiles' => 1, 'accepted_types' => ['.ico', 'web_image']]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $setting = new admin_setting_configcolourpicker(
        'theme_baitalgahwa/primarycolor',
        get_string('primarycolor', 'theme_baitalgahwa'),
        get_string('primarycolordesc', 'theme_baitalgahwa'),
        '#3d2314',
        null,
        true
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $setting = new admin_setting_configcolourpicker(
        'theme_baitalgahwa/secondarycolor',
        get_string('secondarycolor', 'theme_baitalgahwa'),
        get_string('secondarycolordesc', 'theme_baitalgahwa'),
        '#6b4423',
        null,
        true
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $setting = new admin_setting_configcolourpicker(
        'theme_baitalgahwa/accentcolor',
        get_string('accentcolor', 'theme_baitalgahwa'),
        get_string('accentcolordesc', 'theme_baitalgahwa'),
        '#c9a227',
        null,
        true
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $settings->add(new admin_setting_heading('theme_baitalgahwa/auth', get_string('settings_auth', 'theme_baitalgahwa'), ''));

    $settings->add(new admin_setting_configtext(
        'theme_baitalgahwa/authherotitle',
        get_string('authherotitle', 'theme_baitalgahwa'),
        get_string('authherotitledesc', 'theme_baitalgahwa'),
        '',
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtextarea(
        'theme_baitalgahwa/authherosubtitle',
        get_string('authherosubtitle', 'theme_baitalgahwa'),
        get_string('authherosubtitledesc', 'theme_baitalgahwa'),
        '',
        PARAM_TEXT
    ));
    $setting = new admin_setting_configstoredfile(
        'theme_baitalgahwa/loginbackgroundimage',
        get_string('loginbackgroundimage', 'theme_baitalgahwa'),
        get_string('loginbackgroundimagedesc', 'theme_baitalgahwa'),
        'loginbackgroundimage',
        0,
        ['maxfiles' => 1, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $settings->add(new admin_setting_heading('theme_baitalgahwa/hero', get_string('settings_hero', 'theme_baitalgahwa'), ''));

    $settings->add(new admin_setting_configtext(
        'theme_baitalgahwa/herotitle',
        get_string('herotitle', 'theme_baitalgahwa'),
        get_string('herotitledesc', 'theme_baitalgahwa'),
        'Bait Al Gahwa',
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtextarea(
        'theme_baitalgahwa/herosubtitle',
        get_string('herosubtitle', 'theme_baitalgahwa'),
        get_string('herosubtitledesc', 'theme_baitalgahwa'),
        get_string('herosubtitledefault', 'theme_baitalgahwa'),
        PARAM_TEXT
    ));
    $setting = new admin_setting_configstoredfile(
        'theme_baitalgahwa/heroimage',
        get_string('heroimage', 'theme_baitalgahwa'),
        get_string('heroimagedesc', 'theme_baitalgahwa'),
        'heroimage',
        0,
        ['maxfiles' => 1, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $settings->add(new admin_setting_configtext(
        'theme_baitalgahwa/heroprimarytext',
        get_string('heroprimary', 'theme_baitalgahwa'),
        '',
        get_string('explore', 'theme_baitalgahwa'),
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtext(
        'theme_baitalgahwa/heroprimaryurl',
        get_string('heroprimaryurl', 'theme_baitalgahwa'),
        '',
        '/course',
        PARAM_URL
    ));
    $settings->add(new admin_setting_configtext(
        'theme_baitalgahwa/herosecondarytext',
        get_string('herosecondary', 'theme_baitalgahwa'),
        '',
        '',
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtext(
        'theme_baitalgahwa/herosecondaryurl',
        get_string('herosecondaryurl', 'theme_baitalgahwa'),
        '',
        '/login/index.php',
        PARAM_URL
    ));

    $settings->add(new admin_setting_heading('theme_baitalgahwa/footer', get_string('settings_footer', 'theme_baitalgahwa'), ''));

    $settings->add(new admin_setting_configtextarea(
        'theme_baitalgahwa/footerabout',
        get_string('footerabout', 'theme_baitalgahwa'),
        '',
        get_string('footeraboutdefault', 'theme_baitalgahwa'),
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footeremail', get_string('footeremail', 'theme_baitalgahwa'),
        '', '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footerphone', get_string('footerphone', 'theme_baitalgahwa'),
        '', '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footeraddress', get_string('footeraddress', 'theme_baitalgahwa'),
        '', '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footerfacebook', get_string('footerfacebook', 'theme_baitalgahwa'),
        '', '', PARAM_URL));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footertwitter', get_string('footertwitter', 'theme_baitalgahwa'),
        '', '', PARAM_URL));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footerinstagram', get_string('footerinstagram', 'theme_baitalgahwa'),
        '', '', PARAM_URL));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footerlinkedin', get_string('footerlinkedin', 'theme_baitalgahwa'),
        '', '', PARAM_URL));
    $settings->add(new admin_setting_configtext('theme_baitalgahwa/footeryoutube', get_string('footeryoutube', 'theme_baitalgahwa'),
        '', '', PARAM_URL));

    $settings->add(new admin_setting_heading('theme_baitalgahwa/courseactions', get_string('settings_courseactions', 'theme_baitalgahwa'), ''));

    $setting = new admin_setting_configtext(
        'theme_baitalgahwa/managecourseurloverride',
        get_string('managecourseurloverride', 'theme_baitalgahwa'),
        get_string('managecourseurloverridedesc', 'theme_baitalgahwa'),
        '',
        PARAM_RAW
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $setting = new admin_setting_configselect(
        'theme_baitalgahwa/instructorchipline',
        get_string('instructorchipline', 'theme_baitalgahwa'),
        get_string('instructorchiplinedesc', 'theme_baitalgahwa'),
        '0',
        [
            '0' => get_string('instructorchipline_end', 'theme_baitalgahwa'),
            '1' => get_string('instructorchipline_lastaccess', 'theme_baitalgahwa'),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $setting = new admin_setting_configtextarea(
        'theme_baitalgahwa/customcss',
        get_string('customcss', 'theme_baitalgahwa'),
        get_string('customcssdesc', 'theme_baitalgahwa'),
        '',
        PARAM_RAW
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}
