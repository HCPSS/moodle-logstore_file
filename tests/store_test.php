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
 * File logstore tests.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/fixtures/event.php';

use logstore_file\event\unittest_executed;
use core\session\manager as sessionmanager;

/**
 * File logstore tests.
 *
 * @package    logstore_file
 * @copyright  2017 Howard County Public School System (based on standard log store from Petr Skoda)
 * @author     Brendan Anderson <brendan_anderson@hcpss.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logstore_file_store_testcase extends advanced_testcase {

    /**
     * The path to the log file. For example "/moodledata/moodle.log";
     *
     * @var string
     */
    private $logfile;

    /**
     * Test log writing.
     */
    public function test_log_writing() {
        global $CFG;

        // We are going to need some course structures.
        list($user1, $course1, $module1) = $this->generate_structure();
        list($user2, $course2, $module2) = $this->generate_structure();

        $this->resetAfterTest();

        // Enable the plugin
        $this->logfile = "{$CFG->phpunit_dataroot}/moodle.log";
        set_config('enabled_stores', 'logstore_file', 'tool_log');
        set_config('buffersize', 0, 'logstore_file');
        set_config('log_location', $this->logfile, 'logstore_file');
        $manager = get_log_manager(true);

        // Check that the log is empty
        $this->assertFileNotExists($this->logfile);

        $this->setCurrentTimeStart();

        // Log something
        $this->setUser(0);
        $event1 = unittest_executed::create(array(
            'context' => context_module::instance($module1->cmid),
            'other' => array('sample' => 5, 'xx' => 10),
        ));
        $event1->trigger();

        $this->assertFileExists($this->logfile);
        $this->assertCount(1, $this->get_log_lines($this->logfile));

        $line1 = $this->get_line(0);
        $log1 = $this->decode_log_line($line1);
        $data = $event1->get_data();
        $data['origin'] = 'cli';
        $data['ip'] = null;
        $data['realuserid'] = null;
        $this->assertEquals($data, $log1);

        $this->setAdminUser();
        \core\session\manager::loginas(
            $user1->id,
            context_system::instance()
        );
        $this->assertEquals(2, $this->count_lines());

        $event2 = unittest_executed::create(array(
            'context' => context_module::instance($module2->cmid),
            'other' => array('sample' => 6, 'xx' => 9),
        ));
        $event2->trigger();

        sessionmanager::init_empty_session();
        $this->assertFalse(sessionmanager::is_loggedinas());

        $this->assertCount(3, $this->get_log_lines());
        $line2 = $this->get_line(1);
        $log2 = $this->decode_log_line($line2);
        $this->assertSame('\core\event\user_loggedinas', $log2['eventname']);

        $line3 = $this->get_line(2);
        $log3 = $this->decode_log_line($line3);
        $data = $event2->get_data();
        $data['origin'] = 'cli';
        $data['ip'] = null;
        $data['realuserid'] = 2;
        $this->assertEquals($data, $log3);

        // Test buffering.
        set_config('buffersize', 3, 'logstore_file');
        $manager = get_log_manager(true);

        $this->reset_log();

        unittest_executed::create(array(
            'context' => context_module::instance($module1->cmid),
            'other' => array('sample' => 5, 'xx' => 10),
        ))->trigger();
        $this->assertEquals(0, $this->count_lines());

        unittest_executed::create(array(
            'context' => context_module::instance($module1->cmid),
            'other' => array('sample' => 5, 'xx' => 10),
        ))->trigger();
        $this->assertEquals(0, $this->count_lines());


        unittest_executed::create(array(
            'context' => context_module::instance($module1->cmid),
            'other' => array('sample' => 5, 'xx' => 10),
        ))->trigger();
        $this->assertEquals(3, $this->count_lines());

        unittest_executed::create(array(
            'context' => context_module::instance($module1->cmid),
            'other' => array('sample' => 5, 'xx' => 10),
        ))->trigger();
        $this->assertEquals(3, $this->count_lines());

        unittest_executed::create(array(
            'context' => context_module::instance($module1->cmid),
            'other' => array('sample' => 5, 'xx' => 10),
        ))->trigger();
        $this->assertEquals(3, $this->count_lines());

        unittest_executed::create(array(
            'context' => context_module::instance($module1->cmid),
            'other' => array('sample' => 5, 'xx' => 10),
        ))->trigger();
        $this->assertEquals(6, $this->count_lines());

        set_config('enabled_stores', '', 'tool_log');
        get_log_manager(true);
    }

    /**
     * Reset the log.
     */
    private function reset_log() {
        unlink($this->logfile);
        touch($this->logfile);
    }

    /**
     * Decode the JSON string stored in the log.
     *
     * @param int $line
     * @return string
     */
    private function decode_log_line($line) {
        $parts = explode(': ', $line, 2);

        return json_decode($parts[1], true);
    }

    /**
     * Count the number of lines in the log.
     *
     * @return int
     */
    private function count_lines() {
        $lines = $this->get_log_lines();

        return count($lines);
    }


    /**
     * Get an array of lines in the log.
     *
     * @return array
     */
    private function get_log_lines() {
        $log = file_get_contents($this->logfile);
        $lines = explode("\n", trim($log));

        return array_filter($lines);
    }

    /**
     * Get line by zero indexed line number.
     *
     * @param int $linenumber
     * @return string
     */
    private function get_line($linenumber) {
        $lines = $this->get_log_lines();

        return $lines[$linenumber];
    }

    /**
     * Generate a $user, $course and $module.
     *
     * @return stdClass[]
     */
    private function generate_structure() {
        $this->setAdminUser();

        $user   = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $module = $this->getDataGenerator()->create_module('resource', array(
            'course' => $course
        ));

        return array($user, $course, $module);
    }
}
