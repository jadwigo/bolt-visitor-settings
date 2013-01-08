<?php
// Visitor settings Extension for Bolt, by Lodewijk Evers

namespace VisitorSettings;

/**
 * Info block for Visitor settings Extension.
 */
function info()
{

    $data = array(
        'name' => "Visitor settings",
        'description' => "Store settings for a user",
        'author' => "Lodewijk Evers",
        'link' => "https://github.com/jadwigo/bolt-visitor-settings",
        'version' => "0.1",
        'required_bolt_version' => "0.7.10",
        'highest_bolt_version' => "0.7.10",
        'type' => "General",
        'first_releasedate' => "2013-01-08",
        'latest_releasedate' => "2013-01-08",
        'dependancies' => "",
        'priority' => 10
    );

    return $data;

}

/**
 * Initialize Visitor settings. Called during bootstrap phase.
 */
function init($app)
{


}




