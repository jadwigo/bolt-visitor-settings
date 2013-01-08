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
        'dependencies' => "[ Visitors ]",
        'priority' => 10
    );

    return $data;

}

/**
 * Initialize VisitorSettings. Called during bootstrap phase.
 */
function init($app)
{

    // Endpoint for VisitorSettings to get and put settings
    $app->match("/visitorsettings/get", '\VisitorSettings\get')
        ->before('Bolt\Controllers\Frontend::before')
        ->bind('visitorsettingsget');
    $app->match("/visitorsettings/put", '\VisitorSettings\put')
        ->before('Bolt\Controllers\Frontend::before')
        ->bind('visitorsettingsput');
}


/**
 * Visitor settings endpoint
 *
 * This endpoint loads a value for a given sessiontoken and key
 */
function get(Silex\Application $app) {

}


/**
 * Visitor settings endpoint
 *
 * This endpoint saves a key-value for a given sessiontoken
 */
function put(Silex\Application $app) {

}



