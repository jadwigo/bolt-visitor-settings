<?php

namespace VisitorSettings;

use Bolt;
use Silex;

class Settings
{
    private $db;
    private $config;
    private $prefix;
    private $session;

    public function __construct(Silex\Application $app)
    {
        $this->config = $app['config'];
        $this->db = $app['db'];
        $this->session = $app['session'];
        $this->prefix = isset($this->config['general']['database']['prefix']) ? $this->config['general']['database']['prefix'] : "bolt_";
        // Make sure prefix ends in '_'. Prefixes without '_' are lame..
        if ($this->prefix[ strlen($this->prefix)-1 ] != "_") {
            $this->prefix .= "_";
        }
    }

    // check if sessions table exists - if not create it
    // CREATE TABLE 'bolt_visitors_settings' ('id' INTEGER PRIMARY KEY NOT NULL, 'visitor_id' INTEGER, 'key' VARCHAR(64), 'value' TEXT);
    
    // load value for visitor and key
    public function load($visitor_id, $key = null) 
    {
        if($visitor_id && $key) {
            $sql = "SELECT * from " . $this->prefix ."visitors_settings WHERE visitor_id = :vid AND `settings_key` = :key";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue("vid", $visitor_id);
            $stmt->bindValue("key", $key);
            $stmt->execute();

            $all = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $settings = array_shift($all);
            if($settings!=null && !empty($settings['value'])) {
                
                $settings['value'] = unserialize($settings['value']);
                //var_dump($settings);
            }
            return $settings;
        } else {
           return false;
        }
    }

    // update existing visitor session
    public function update($visitor_id, $key = null, $value = null) 
    {
        if($visitor_id && $key && $value) {
            $exists = $this->load($visitor_id, $key);

            $tablename =  $this->prefix ."visitors_settings";

            // update if not existing yet
            // inserting if new
            if($exists!=false && is_array($exists)) {
                $content = array(
                    'visitor_id' => $visitor_id, 
                    'settings_key' => $key, 
                    'value' => serialize($value), 
                );
                return $this->db->update($tablename, $content, array('id' => $exists['id']));
            } else {
                $content = array(
                    'visitor_id' => $visitor_id, 
                    'settings_key' => $key, 
                    'value' => serialize($value), 
                );
                return $this->db->insert($tablename, $content);                
            }
        }
    }

}