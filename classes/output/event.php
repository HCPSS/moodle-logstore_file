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
     * Format as HTML.
     *
     * @var integer
     */
    const FORMAT_HTML = 0;

    /**
     * Format as JSON.
     *
     * @var integer
     */
    const FORMAT_JSON = 1;

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
     * @param int $format The format the template is in. Use one of the class
     *   constants FORMAT_XXXX.
     * @return \stdClass Data ready for use in a mustache template
     */
    public function export_for_template(\renderer_base $output, $format = self::FORMAT_HTML) {
        $data = $this->event->get_data();

        $data['origin'] = $output->get_requestorigin();
        $data['ip'] = $output->get_requestip();
        $data['realuserid'] = $output->get_realuser();

        switch ($format) {
            case self::FORMAT_HTML:
                $log = (object)$data;
                break;
            case self::FORMAT_JSON:
                $log = new \stdClass();
                foreach ($data as $property => $value) {
                    if (is_numeric($value)) {
                        $log->{$property} = $value;
                    } else {
                        $log->{$property} = json_encode($value);
                    }
                }
                break;
            default:
                $log = (object)$data;
                break;
        }

        return $log;
    }

}
