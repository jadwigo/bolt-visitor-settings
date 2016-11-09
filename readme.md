Visitor settings
=======================

This extension is not maintained anymore.

Please use the members extension that is build for the current releases of https://bolt.cm

Visitor settings
=======================

Store settings for a visitor

Installation
=======================
Download and extract the extension to a directory called VisitorSettings in your Bolt extension directory.

This extensions requires that the Visitors extension ( https://github.com/jadwigo/bolt-visitors ) is installed and working.

Create the database tables manually by using the queries below.

Usage
=======================

When enabled you can use the key value storage by the following two paths:

Looking up a value for KEY (A normal GET request will work):

    `/async/visitorsettings/get?key=KEY`

Setting a value for KEY (You might want to try a POST request):

    `/async/visitorsettings/put?key=KEY&value=VALUE`

The KEY and VALUE are set for a logged in visitor, and as long as the visitor uses the same authentication provider the KEY => VALUE pairs are device independent.

Database
=======================

You need to manually create the db tables.

For Mysql:

    CREATE TABLE IF NOT EXISTS `bolt_visitors_settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `visitor_id` int(11) NOT NULL,
      `settings_key` varchar(255) DEFAULT NULL,
      `value` text,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

For SQLite:

    CREATE TABLE 'bolt_visitors_settings' ('id' INTEGER PRIMARY KEY NOT NULL, 'visitor_id' INTEGER, 'settings_key' VARCHAR(64), 'value' TEXT);

Editing SQLite databases is relatively easy with a tool like phpLiteAdmin ( <a href="http://phpliteadmin.googlecode.com">http://phpliteadmin.googlecode.com</a> )
