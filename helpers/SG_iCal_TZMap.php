<?php // BUILD: Remove line

/**
 * Some ical products do now use standard tzid time zone formats.
 *
 * This is a simple (perhaps naive) mapping mechanism.
 *
 * Here is the official mapping from Microsoft to tzid.
 * http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/zone_tzid.html
 * 
 * @package SG_iCalReader
 * @license http://creativecommons.org/licenses/by-sa/2.5/dk/deed.en_GB CC-BY-SA-DK
 */
class SG_iCal_TZMap {
    
    static protected $availableMaps = array(
    //  PRODID                     Mapping file
        'Microsoft Corporation' => 'microsoft',
    );
    
    static protected $map = array();
    
    public function __construct($product) {
        self::loadMap($product);
    }
    
    static protected function loadMap($product) {
        if (! array_key_exists($product, self::$availableMaps)) {
            throw new Exception("Mapping type '$product' not supported");
        }
        
        if (count(self::$map) === 0) {
            $file = dirname(__FILE__) . '/../tz-map/' . self::$availableMaps[$product] . '.csv';
            
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ( ! array_key_exists($data[0], self::$map ) ) {
                        self::$map[$data[0]] = array();
                    }
                    
                    self::$map[$data[0]][$data[1]] = $data[2];
                }
                fclose($handle);
            } else {
                // Error
            }
        }
    }
    
    static public function map($product, $fromTZ, $region='1') {
        
        self::loadMap($product);
        
        $cleanKey = trim($fromTZ, '"');
        
        if ( ! array_key_exists($cleanKey, self::$map) ) {
            return $fromTZ;
        } else {
            return self::$map[$cleanKey][$region];
        }
    }
    
    public function debugShowMap() {
        var_dump(count(self::$map));
    }
}