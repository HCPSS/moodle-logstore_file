# File Logstore

A log store for logging to a file.

## Overview

On large installations, the standard log produced by Moodle and stored in the 
database can be quite large. On one site I found that the standard log was 
responsible for over 90% of the data in my database. This made it slow to 
backup, restore, or move the database.

This plugin allows you to output your logs to a file.

## Installation and configuration

1. Create a log file and make sure the webserver can write to it. For example: 
   `touch /var/log/moodle.log && chown www-data:www-data /var/log/moodle.log`.
2. Clone this repository with git or grab a [release](/releases) and place the 
   files in: `/your/site/admin/tools/log/store/file`.
3. When you visit your site, you will be asked to input the location of the file 
   you created in step 1 (/var/log/moodle.log is the default).
4. Optionally, if you want to stop writing logs to the database, you should go
   to _Site administration > Plugins > Logging > Manage log stores_ and disable
   the standard log store.

### Advanced configuration

Logs are rendered using a Mustache template. By default each log entry consists 
of a timestamp and then a JSON representation of the event being logged. For 
example:

```json
1484664029: { "eventname": "\\core\\event\\calendar_event_created", "component": "core", "action": "created", "etc...": "etc..." }
```

If you want to format your logs, you are free to do so by [overridding the 
template in your theme](https://docs.moodle.org/dev/Templates#How_to_I_override_a_template_in_my_theme.3F).
