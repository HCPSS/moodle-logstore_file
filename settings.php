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
 * Log settings.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    global $CFG;

    $settings->add(new logstore_file\admin\setting\configdirectory(
        'logstore_file/log_location',
        new lang_string('log_location', 'logstore_file'),
        new lang_string('log_location_help', 'logstore_file'),
        '/var/log/moodle.log'
    ));

    $settings->add(new admin_setting_configtext(
        'logstore_file/buffersize',
        new lang_string('buffersize', 'logstore_file'),
        new lang_string('buffersize_help', 'logstore_file'),
        50
    ));
}
