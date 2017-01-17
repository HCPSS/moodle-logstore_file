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
 * Contains the custom configdirectory.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_file\admin\setting;

defined('MOODLE_INTERNAL') || die();

/**
 * Custom configdirectory.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class configdirectory extends \admin_setting_configdirectory {

    /**
     * Validate the data.
     *
     * @param string $data
     * @return boolean|string
     */
    public function validate($data) {
        if (!is_writeable($data)) {
            return get_string('validateerror', 'logstore_file', $data);
        }

        return true;
    }

}
