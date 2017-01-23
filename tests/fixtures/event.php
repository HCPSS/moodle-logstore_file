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
 * Fixtures for file log storage testing
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace logstore_file\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Fixtures for file log storage testing
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class unittest_executed extends \core\event\base {

    /**
     * Get the event name.
     *
     * @return string
     */
    public static function get_name() {
        return 'xxx';
    }

    /**
     * Get the event description.
     * {@inheritDoc}
     * @see \core\event\base::get_description()
     */
    public function get_description() {
        return 'yyy';
    }

    /**
     * Initialize the event.
     * {@inheritDoc}
     * @see \core\event\base::init()
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }

    /**
     * Get the event URL.
     * {@inheritDoc}
     * @see \core\event\base::get_url()
     */
    public function get_url() {
        return new \moodle_url('/somepath/somefile.php', array(
            'id' => $this->data['other']['sample']
        ));
    }
}
