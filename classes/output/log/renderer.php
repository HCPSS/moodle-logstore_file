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
 * This file contains the logstore_file render class.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_file\output\log;

use core\session\manager;

defined('MOODLE_INTERNAL') || die();

/**
 * logstore_file render class.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \plugin_renderer_base {

    /**
     * Get the request origin.
     *
     * @return string
     */
    public function get_requestorigin() {
        return $this->page->requestorigin;
    }

    /**
     * Get the request ip.
     *
     * @return string
     */
    public function get_requestip() {
        return $this->page->requestip;
    }

    /**
     * Get the user id of the user, if she is logged in as someone else.
     *
     * @return int
     */
    public function get_realuser() {
        return manager::is_loggedinas() ? $GLOBALS['USER']->realuser : null;
    }

    /**
     * Render an event.
     *
     * @param \logstore_file\output\event $event
     * @return string
     */
    public function render_event(\logstore_file\output\event $event) {
        $data = $event->export_for_template($this);
        return $this->render_from_template('logstore_file/file_log', $data);
    }
}
