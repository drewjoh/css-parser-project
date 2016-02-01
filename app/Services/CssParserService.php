<?php namespace App\Services;

/**
 * Parser service for CSS strings
 * 
 * Class CssParserService
 * @package App\Services
 */
class CssParserService
{
    
    /**
     * Get all the stats that we can for a CSS string
     * 
     * @param null $css_string
     *
     * @return array
     */
    public function getAllStatsForString($css_string = null)
    {
        return array_merge(
            $this->getFileDataInfo($css_string),
            $this->cssAnalysis( $this->parseCss($css_string) )
        );
    }

    /**
     * Get "file info" for a CSS string
     *  - full size in bytes
     *  - amount of whitespace in bytes
     *  - amount of data only in bytes
     * 
     * @param $css_string
     *
     * @return array
     */
    public function getFileDataInfo($css_string)
    {
        # Get the full size of the CSS
        $full_size       = strlen($css_string);
        # Get the size of whitespace in CSS
        $whitespace_size = $full_size - strlen( $this->_removeNewLines($this->_removeWhitespace($css_string)) );
        
        # Return our values as an array
        return [
            'full_size'       => $full_size,
            'data_size'       => $full_size - $whitespace_size,
            'whitespace_size' => $whitespace_size,
        ];
    }

    /**
     * Parse a CSS string
     *  - returns back an array with selectors and selector blocks
     * 
     * @param $css_string
     *
     * @return array
     */
    public function parseCss($css_string)
    {
        # Init our results array
        $results = array();

        # Get rid of new lines first
        $css_string = $this->_removeNewLines($css_string);
        
        # Matches all CSS blocks and returns info about all of them
        # - Does NOT account for @media blocks being something separate, but this doesn't make our selector count incorrect ({ })
        preg_match_all('/([A-Za-z0-9\-\[\]\: ,=\^\"#.>\(\)\*]+?)\s?\{\s?(.+?)\s?\}/', $css_string, $all_matches);
        
                /**
                *  We get 3 lists back from the preg_match_all call
                *  - $all_matches[0] = the selector and block together
                *  - $all_matches[1] = the selector only
                *  - $all_matches[2] = the block for the corresponding selector in [1]
                **/
        
        # Go through each block match and put the values in an array
        foreach($all_matches[0] as $count => $original_string):
            
            # For this block, get and go through all the property declarations
            foreach( explode(';', $all_matches[2][$count]) as $attribute ) {
                
                # In case we don't have a semi-colon on the last property declaration
                if($attribute) {
                    # Get our name/value by using the cool list method, assigning the values we get from explode()
                    @list($name, $value) = explode(':', $attribute);
                    # Add this data to our final result
                    $results[ $all_matches[1][$count] ][ trim($name) ] = trim($value);
                }
                
            }
            
        endforeach;
        
        # Return our results
        return $results;
    }

    /**
     * Analyzes a parsed CSS string and returns an array of information
     * 
     * @param $parse_results
     *
     * @return array
     */
    public function cssAnalysis($parse_results)
    {
        $block_count = 0;
        $property_count = 0;
        $unique_selector_count = 0;
        $colors = [];
        $selectors = [];
        
        foreach($parse_results as $selector => $properties):
            
            # Put these selectors in our list
            foreach( explode(',', $selector) as $current_selector) {
                $selectors[] = trim($current_selector);
            }
            
            # Get our colors
            foreach($properties as $property_name => $property_value) {
                # If it looks like this property is defining a color, let's add it to our list
                if(stripos($property_name, 'color') !== false) {
                    # If the color is in RGBA(X,X,X,X) format
                    if(stripos($property_value, 'rgba') !== false) {
                        $colors[] = $property_value;
                    }
                    # If the color is in #XXX or #XXXXXX format
                    elseif(preg_match('/([#0-9A-Fa-f]{3,7})/', $property_value, $matches)) {
                        # Gets the match and adds it to colors
                        $colors[] = $matches[1];
                    } 
                    # Otherwise, maybe they did something like "BLUE"
                    else {
                        $colors[] = $property_value;
                    }
                }
            }
            
            # Update our property count based on the number of properties in this selector
            $property_count += count($properties);
            
            # Update our block count
            $block_count++;
            
        endforeach;
        
        return [
            'block_count'           => $block_count,
            'property_count'        => $property_count,
            'color_count'           => count(array_unique($colors)),
            'unique_selector_count' => count(array_unique($selectors)), # Count only unique selectors
            'unique_selectors'      => array_unique($selectors),
            'colors'                => array_unique($colors),
        ];
    }

    /**
     * Removes new lines from a string
     * 
     * @param $string
     *
     * @return string
     */
    public function _removeNewLines($string)
    {
        return trim(str_replace(["\r\n", "\n", "\r"], '', $string));
    }

    /**
     * Removes all whitespace from a string
     * 
     * @param $string
     *
     * @return mixed
     */
    public function _removeWhitespace($string)
    {
        return str_replace(' ', '', $string);
    }
    
}