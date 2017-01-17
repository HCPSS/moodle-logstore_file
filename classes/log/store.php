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
 * This file contains the logstore_file logstore.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_file\log;

defined('MOODLE_INTERNAL') || die();

/**
 * The logstore_file logstore.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class store implements \tool_log\log\writer {
    use \tool_log\helper\store,
        \tool_log\helper\reader;

    /**
     * Contruct the logstore.
     *
     * @param \tool_log\log\manager $manager
     */
    public function __construct(\tool_log\log\manager $manager) {
        $this->helper_setup($manager);
    }

    /**
     * Should we ignore the event?
     *
     * @see \tool_log\helper\buffered_writer::is_event_ignored()
     * @param \core\event\base $event
     * @return boolean
     */
    protected function is_event_ignored(\core\event\base $event) {
        // We do not ever need to ignore an event.
        return false;
    }

    /**
     * Write an event
     *
     * @see \tool_log\log\writer::write()
     * @param \core\event\base $event
     * @global \moodle_page $PAGE
     */
    public function write(\core\event\base $event) {
        global $PAGE;

        $logrenderer = $PAGE->get_renderer('logstore_file', 'log');
        $log         = new \logstore_file\output\event($event);
        $logfile     = $this->get_config('log_location', '/var/log/moodle.log');
        $output      = $logrenderer->render($log);

        // This doesn't seem right. The new line should be part of the template,
        // but I could not figure out how to get mustache to allow me to keep
        // a new line character.
        $output .= "\n";

        file_put_contents($logfile, $output, FILE_APPEND | LOCK_EX);
    }

    /**
     * Notify the log store that we are not going to write to it anymore.
     */
    public function dispose() {
        // I don't think the log store cares.
        return;
    }

    /**
     * Get the name of this logstore.
     *
     * @return string
     */
    public function get_name() {
        return 'File Store';
    }

    /**
     * Get the description of this logstore.
     *
     * @return string
     */
    public function get_description() {
        return 'A file store';
    }

    public function is_logging() {
        return true;
    }
}
