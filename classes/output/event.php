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
     * The event name.
     *
     * @var string
     */
    protected $eventname;

    /**
     * Moodle component.
     *
     * @var string
     */
    protected $component;

    /**
     * The action that was performed.
     *
     * @var string
     */
    protected $action;

    /**
     * The target context.
     *
     * @var string
     */
    protected $target;

    /**
     * The table.
     *
     * @var string
     */
    protected $objecttable;

    /**
     * Object id.
     *
     * @var int
     */
    protected $objectid;

    /**
     * Which CRUD operation?
     *
     * @var string
     */
    protected $crud;

    /**
     * Edu level.
     *
     * @var int
     */
    protected $edulevel;

    /**
     * Context id.
     *
     * @var int
     */
    protected $contextid;

    /**
     * Context level.
     *
     * @var int
     */
    protected $contextlevel;

    /**
     * Context instance id.
     *
     * @var int
     */
    protected $contextinstanceid;

    /**
     * User id.
     *
     * @var int
     */
    protected $userid;

    /**
     * Course id.
     *
     * @var int
     */
    protected $courseid;

    /**
     * Related user id.
     *
     * @var int
     */
    protected $relateduserid;

    /**
     * Is this an anonymous user?
     *
     * @var bool
     */
    protected $anonymous;

    /**
     * Additional information.
     *
     * @var array
     */
    protected $other;

    /**
     * Created timestamp.
     *
     * @var int
     */
    protected $timecreated;

    /**
     * Web or CLI event?
     *
     * @var string
     */
    protected $origin;

    /**
     * The ip address the event originated from.
     *
     * @var string
     */
    protected $ip;

    /**
     * If the user us logged in for someone else, who is the original user?
     *
     * @var int
     */
    protected $realuserid;

    /**
     * Convert the event into an array.
     *
     * @return array
     */
    private function to_array() {
        return array(
            'eventname'         => $this->eventname,
            'component'         => $this->component,
            'action'            => $this->action,
            'target'            => $this->target,
            'objecttable'       => $this->objecttable,
            'objectid'          => $this->objectid,
            'crud'              => $this->crud,
            'edulevel'          => $this->edulevel,
            'contextid'         => $this->contextid,
            'contextlevel'      => $this->contextlevel,
            'contextinstanceid' => $this->contextinstanceid,
            'userid'            => $this->userid,
            'courseid'          => $this->courseid,
            'relateduserid'     => $this->relateduserid,
            'anonymous'         => $this->anonymous,
            'other'             => $this->other,
            'timecreated'       => $this->timecreated,
            'origin'            => $this->origin,
            'ip'                => $this->ip,
            'realuserid'        => $this->realuserid,
        );
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
        $data = $this->to_array();

        switch ($format) {
            case self::FORMAT_HTML:
                $log = (object) $data;
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
                $log = (object) $data;
                break;
        }

        return $log;
    }

    /**
     * Get the event name.
     *
     * @return string
     */
    public function get_eventname() {
        return $this->eventname;
    }

    /**
     * Get the component.
     *
     * @return string
     */
    public function get_component() {
        return $this->component;
    }

    /**
     * Get action.
     *
     * @return string
     */
    public function get_action() {
        return $this->action;
    }

    /**
     * Get target.
     *
     * @return string
     */
    public function get_target() {
        return $this->target;
    }

    /**
     * Get object table.
     *
     * @return string
     */
    public function get_objecttable() {
        return $this->objecttable;
    }

    /**
     * Get object id.
     *
     * @return number
     */
    public function get_objectid() {
        return $this->objectid;
    }

    /**
     * Get CRUD.
     *
     * @return string
     */
    public function get_crud() {
        return $this->crud;
    }

    /**
     * Get EDU level.
     *
     * @return number
     */
    public function get_edulevel() {
        return $this->edulevel;
    }

    /**
     * Get context id.
     *
     * @return number
     */
    public function get_contextid() {
        return $this->contextid;
    }

    /**
     * Get context level.
     *
     * @return number
     */
    public function get_contextlevel() {
        return $this->contextlevel;
    }

    /**
     * Get context instance id.
     *
     * @return number
     */
    public function get_contextinstanceid() {
        return $this->contextinstanceid;
    }

    /**
     * Get user id.
     *
     * @return number
     */
    public function get_userid() {
        return $this->userid;
    }

    /**
     * Get course id.
     *
     * @return number
     */
    public function get_courseid() {
        return $this->courseid;
    }

    /**
     * Get related user id.
     *
     * @return number
     */
    public function get_relateduserid() {
        return $this->relateduserid;
    }

    /**
     * Get anonymous
     *
     * @return boolean
     */
    public function get_anonymous() {
        return $this->anonymous;
    }

    /**
     * Get other.
     *
     * @return array
     */
    public function get_other() {
        return $this->other;
    }

    /**
     * Get time created.
     *
     * @return number
     */
    public function get_timecreated() {
        return $this->timecreated;
    }

    /**
     * Get origin.
     *
     * @return string
     */
    public function get_origin() {
        return $this->origin;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function get_ip() {
        return $this->ip;
    }

    /**
     * Get the real user id.
     *
     * @return number
     */
    public function get_realuserid() {
        return $this->realuserid;
    }

    /**
     * Set the event name.
     *
     * @param string $eventname
     * @return \logstore_file\output\event
     */
    public function set_eventname($eventname) {
        $this->eventname = $eventname;
        return $this;
    }

    /**
     * Set the component.
     *
     * @param string $component
     * @return \logstore_file\output\event
     */
    public function set_component($component) {
        $this->component = $component;
        return $this;
    }

    /**
     * Set the action.
     *
     * @param string $action
     * @return \logstore_file\output\event
     */
    public function set_action($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * Set the target.
     *
     * @param string $target
     * @return \logstore_file\output\event
     */
    public function set_target($target) {
        $this->target = $target;
        return $this;
    }

    /**
     * Set the object table.
     *
     * @param string $objecttable
     * @return \logstore_file\output\event
     */
    public function set_objecttable($objecttable) {
        $this->objecttable = $objecttable;
        return $this;
    }

    /**
     * Set the objectid.
     *
     * @param int $objectid
     * @return \logstore_file\output\event
     */
    public function set_objectid($objectid) {
        $this->objectid = $objectid;
        return $this;
    }

    /**
     * Set CRUD.
     *
     * @param string $crud
     * @return \logstore_file\output\event
     */
    public function set_crud($crud) {
        $this->crud = $crud;
        return $this;
    }

    /**
     * Set EDU level.
     *
     * @param int $edulevel
     * @return \logstore_file\output\event
     */
    public function set_edulevel($edulevel) {
        $this->edulevel = $edulevel;
        return $this;
    }

    /**
     * Set context id.
     *
     * @param int $contextid
     * @return \logstore_file\output\event
     */
    public function set_contextid($contextid) {
        $this->contextid = $contextid;
        return $this;
    }

    /**
     * Set context level.
     *
     * @param int $contextlevel
     * @return \logstore_file\output\event
     */
    public function set_contextlevel($contextlevel) {
        $this->contextlevel = $contextlevel;
        return $this;
    }

    /**
     * Set context instance id.
     *
     * @param int $contextinstanceid
     * @return \logstore_file\output\event
     */
    public function set_contextinstanceid($contextinstanceid) {
        $this->contextinstanceid = $contextinstanceid;
        return $this;
    }

    /**
     * Set user id.
     *
     * @param int $userid
     * @return \logstore_file\output\event
     */
    public function set_userid($userid) {
        $this->userid = $userid;
        return $this;
    }

    /**
     * Set course id.
     *
     * @param int $courseid
     * @return \logstore_file\output\event
     */
    public function set_courseid($courseid) {
        $this->courseid = $courseid;
        return $this;
    }

    /**
     * Set related user id.
     *
     * @param int $relateduserid
     * @return \logstore_file\output\event
     */
    public function set_relateduserid($relateduserid) {
        $this->relateduserid = $relateduserid;
        return $this;
    }

    /**
     * Set anonymous
     *
     * @param bool $anonymous
     * @return \logstore_file\output\event
     */
    public function set_anonymous($anonymous) {
        $this->anonymous = $anonymous;
        return $this;
    }

    /**
     * Set other
     *
     * @param array $other
     * @return \logstore_file\output\event
     */
    public function set_other($other = array()) {
        $this->other = $other;
        return $this;
    }

    /**
     * Set time created.
     *
     * @param int $timecreated
     * @return \logstore_file\output\event
     */
    public function set_timecreated($timecreated) {
        $this->timecreated = $timecreated;
        return $this;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return \logstore_file\output\event
     */
    public function set_origin($origin) {
        $this->origin = $origin;
        return $this;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     * @return \logstore_file\output\event
     */
    public function set_ip($ip) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Set real user id.
     *
     * @param int $realuserid
     * @return \logstore_file\output\event
     */
    public function set_realuserid($realuserid) {
        $this->realuserid = $realuserid;
        return $this;
    }
}
