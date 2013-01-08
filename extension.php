<?php
// Visitor settings Extension for Bolt, by Lodewijk Evers

namespace VisitorSettings;

use Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    // autoloader does not pick it up automagically
    require_once( __DIR__."/src/Settings/Settings.php" );

    // Endpoint for VisitorSettings to get and put settings
    $app->match("/visitorsettings/get", '\VisitorSettings\Controller::get')
        ->bind('visitorsettingsget');
    $app->match("/visitorsettings/put", '\VisitorSettings\Controller::put')
        ->bind('visitorsettingsput');
}

class Controller
{
    /**
     * Visitor settings endpoint
     *
     * This endpoint loads a value for a given sessiontoken and key
     */
    function get(Silex\Application $app) {
        // load visitor id by session token
        $recognizedvisitor = \Visitors\checkvisitor($app);

        //$app['log']->add(\util::var_dump($recognizedvisitor, true));
        if($recognizedvisitor) {
            $visitor_id = $recognizedvisitor['id'];
            $key = \util::get_var('key', false);

            $visitorsettings = new \VisitorSettings\Settings($app);
            $settings = $visitorsettings->load( $visitor_id, $key );

            return $app->json($settings, 200);
            //return new Response(json_encode($settings), 200, array('Cache-Control' => 's-maxage=3600, public'));
        } else {
            return $app->json(false, 404);
            //return new Response(json_encode(false), 404, array('Cache-Control' => 's-maxage=3600, public'));
        }
    }
    
    
    /**
     * Visitor settings endpoint
     *
     * This endpoint saves a key-value for a given sessiontoken
     */
    function put(Silex\Application $app) {
        // load visitor id by session token
        $recognizedvisitor = \Visitors\checkvisitor($app);

        //$app['log']->add(\util::var_dump($recognizedvisitor, true));
        if($recognizedvisitor) {
            $visitor_id = $recognizedvisitor['id'];
            $key = \util::get_var('key', false);
            $value = \util::get_var('value', false);

            //$app['log']->add(\util::var_dump($key, true));
            //$app['log']->add(\util::var_dump($value, true));
            $visitorsettings = new \VisitorSettings\Settings($app);
            $visitorsettings->update( $visitor_id, $key, $value );
        }

        return $app->json('OK', 201);
        //return new Response(json_encode('OK'), 200, array('Cache-Control' => 's-maxage=3600, public'));
    }

}


