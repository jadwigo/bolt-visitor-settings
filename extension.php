<?php
// Visitor settings Extension for Bolt, by Lodewijk Evers

namespace VisitorSettings;

use Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Extension extends \Bolt\BaseExtension
{

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
    function initialize()
    {
        // autoloader does not pick it up automagically
        require_once __DIR__."/src/Settings/Settings.php";

        // define twig functions and vars
        $this->app['twig']->addExtension(new VisitorSettings_Twig_Extension());

        // Endpoint for VisitorSettings to get and put settings
        $visitorsettings_controller = $this->app['controllers_factory'];
        $visitorsettings_controller
            ->match('/get', '\VisitorSettings\Controller::get')
            ->bind('visitorsettingsget')
            ;
        $visitorsettings_controller
            ->match('/put', '\VisitorSettings\Controller::put')
            ->bind('visitorsettingsput')
            ;
        $this->app->mount('/async/visitorsettings', $visitorsettings_controller);

    }

}
    /**
 * Twig functions
 */
class VisitorSettings_Twig_Extension extends \Twig_Extension
{
    private $twig = null;

    /**
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twig = $environment;
    }

    /**
     * Return the name of the extension
     */
    public function getName()
    {
        return 'visitorsettings';
    }

    /**
     * The functions we add
     */
    public function getFunctions()
    {
        return array(
            'settingslist' =>  new \Twig_Function_Method($this, 'settingslist'),
        );
    }

    /**
     * Check who the visitor is
     */
    function settingslist() {
        $result = \VisitorSettings\Controller::settingsList();
        return $result;
    }

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
        $recognizedvisitor = \Visitors\Controller::checkvisitor($app);

        //$app['log']->add(\util::var_dump($recognizedvisitor, true));
        if($recognizedvisitor) {
            $visitor_id = $recognizedvisitor['id'];
            $key = \util::get_var('key', false);

            $visitorsettings = new \VisitorSettings\Settings($app);
            $settings = $visitorsettings->load( $visitor_id, $key );

            return $app->json($settings['value'], 200);
        } else {
            return $app->json(array('error'=>'unknown visitor'), 404);
        }
    }

    /**
     * Get all visitorsettings
     */
    function settingsList(Silex\Application $app) {
        if(!$app) {
            global $app;
        }
        // load visitor id by session token
        $recognizedvisitor = \Visitors\Controller::checkvisitor($app);

        //$app['log']->add(\util::var_dump($recognizedvisitor, true));
        if($recognizedvisitor) {
            $visitor_id = $recognizedvisitor['id'];
            $visitorsettings = new \VisitorSettings\Settings($app);
            $settingslist = $visitorsettings->settingslist($visitor_id);
            //$markup = new \Twig_Markup($markup, 'UTF-8');

            $markup .= '<div class="well"><pre>'."\n";
            $markup .= var_dump($settingslist, true);
            $markup .= "</pre></div>\n";

            $markup = new \Twig_Markup($markup, 'UTF-8');

            return $markup;
        }
        return false;
    }

    /**
     * Visitor settings endpoint
     *
     * This endpoint saves a key-value for a given sessiontoken
     */
    function put(Silex\Application $app) {
        // load visitor id by session token
        $recognizedvisitor = \Visitors\Controller::checkvisitor($app);

        //$app['log']->add(\util::var_dump($recognizedvisitor, true));
        if($recognizedvisitor) {
            $visitor_id = $recognizedvisitor['id'];

            $posted = \util::post_var('key', false);
            if(!empty($posted)) {
                $key = \util::post_var('key', false);
                $value = \util::post_var('value', false);
                $return = 'OK';
            } elseif($app['debug']) {
                // no POST key found, so let's try anything
                $key = \util::request_var('key', false);
                $value = \util::request_var('value', false);
                $return = 'OK (but only for debugging, you should POST)';
            } else {
                // no POST key found, so let's try anything
                $key = \util::request_var('key', false);
                $value = \util::request_var('value', false);
                return $app->json(array('error'=>'GET not allowed, you should use POST'), 403);
            }

            //$app['log']->add(\util::var_dump($key, true));
            //$app['log']->add(\util::var_dump($value, true));
            $visitorsettings = new \VisitorSettings\Settings($app);
            $visitorsettings->update( $visitor_id, $key, $value );
        } else {
            return $app->json(array('error'=>'unknown visitor'), 404);
        }

        return $app->json($return, 201);
    }

}


