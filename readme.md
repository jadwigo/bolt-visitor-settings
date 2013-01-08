Visitor settings
=======================

Store settings for a user


Database
=======================

You need to manually create the db tables.

For Mysql:

    CREATE TABLE IF NOT EXISTS `bolt_visitors_settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `visitor_id` int(11) NOT NULL,
      `key` varchar(255) DEFAULT NULL,
      `value` text,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

For SQLite:

    CREATE TABLE 'bolt_visitors_settings' ('id' INTEGER PRIMARY KEY NOT NULL, 'visitor_id' INTEGER, 'key' VARCHAR(64), 'value' TEXT);

Editing SQLite databases is relatively easy with a tool like phpLiteAdmin ( <a href="http://phpliteadmin.googlecode.com">http://phpliteadmin.googlecode.com</a> )