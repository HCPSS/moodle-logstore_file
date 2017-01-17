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
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_file\output;

use core\event\base as baseevent;

defined('MOODLE_INTERNAL') || die();

/**
 * Event renderable.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class event implements \renderable, \templatable {

    /**
     * The event.
     *
     * @var baseevent
     */
    protected $event;

    /**
     * Builds a renderable event.
     *
     * @param baseevent $event
     */
    public function __construct(baseevent $event) {
        $this->event = $event;
    }

    /**
     * Export this data so it can be used as the context for a mustache
     * template.
     *
     * @param \mod_forum_renderer $renderer The render to be used for formatting
     *   the message and attachments
     * @return \stdClass Data ready for use in a mustache template
     */
    public function export_for_template(\renderer_base $output) {
        $log = (object) $this->event->get_data();

        $log->eventname = str_replace('\\', '\\\\', $log->eventname);
        $log->other = serialize($log->other);
        $log->origin = $output->get_requestorigin();
        $log->ip = $output->get_requestip();
        $log->realuserid = $output->get_realuser();

        return $log;
    }

}
