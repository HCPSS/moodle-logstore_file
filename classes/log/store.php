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
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_file\log;

use logstore_file\output\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The logstore_file logstore.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class store implements \tool_log\log\writer {
    use \tool_log\helper\store,
        \tool_log\helper\reader,
        \tool_log\helper\buffered_writer;

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
     * Write the buffered events
     *
     * @param \core\event\base[] $events The buffered events.
     * @global \moodle_page $PAGE
     * @see \tool_log\helper\buffered_writer::flush()
     */
    private function insert_event_entries(array $events) {
        global $PAGE;

        $logrenderer = $PAGE->get_renderer('logstore_file', 'log');
        $logfile     = $this->get_config('log_location', '/var/log/moodle.log');

        $output = '';
        foreach ($events as $data) {
            $event = new event();

            $event
                ->set_action($data['action'])
                ->set_anonymous($data['anonymous'])
                ->set_component($data['component'])
                ->set_contextid($data['contextid'])
                ->set_contextinstanceid($data['contextinstanceid'])
                ->set_contextlevel($data['contextlevel'])
                ->set_courseid($data['courseid'])
                ->set_crud($data['crud'])
                ->set_edulevel($data['edulevel'])
                ->set_eventname($data['eventname'])
                ->set_ip($data['ip'])
                ->set_objectid($data['objectid'])
                ->set_objecttable($data['objecttable'])
                ->set_origin($data['origin'])
                ->set_other(unserialize($data['other']))
                ->set_realuserid($data['realuserid'])
                ->set_relateduserid($data['relateduserid'])
                ->set_target($data['target'])
                ->set_timecreated($data['timecreated'])
                ->set_userid($data['userid']);

            $output .= $logrenderer->render($event) . "\n";
        }

        file_put_contents($logfile, $output, FILE_APPEND | LOCK_EX);
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
