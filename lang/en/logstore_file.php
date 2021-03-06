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
 * Translations.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'File log';
$string['pluginname_desc'] = 'File log settings';
$string['log_location'] = 'Log location';
$string['log_location_help'] = 'Make sure the file exists and is writable by Moodle.';
$string['validateerror'] = '{$a} does not exist or is not writable.';
$string['buffersize'] = 'Buffer size';
$string['buffersize_help'] = 'Number of log entries inserted in one batch database operation, which improves performance.';
