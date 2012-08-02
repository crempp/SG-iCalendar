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
    
    private $availableMaps = array(
    //  PRODID                     Mapping file
        'Microsoft Corporation' => 'microsoft',
    );
    
    protected $map = array();
    
    public function __construct($product) {
        if (! array_key_exists($product, $this->availableMaps)) {
            throw new Exception("Mapping type '$product' not supported");
        }
        
        $file = dirname(__FILE__) . '/../tz-map/' . $this->availableMaps[$product] . '.csv';
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ( ! array_key_exists($data[0], $this->map ) ) {
                    $this->map[$data[0]] = array();
                }
                
                $this->map[$data[0]][$data[1]] = $data[2];
            }
            fclose($handle);
        } else {
            // Error
        }
    }
    
    public function map($fromTZ, $region='1') {
        return $this->map[$fromTZ][$region];
    }
}