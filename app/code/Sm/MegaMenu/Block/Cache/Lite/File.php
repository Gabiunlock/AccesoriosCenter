<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\MegaMenu\Block\Cache\Lite;

use Sm\MegaMenu\Block\Cache\Lite;

class File extends Lite
{

    // --- Private properties ---
    
    /**
    * Complete path of the file used for controlling the cache lifetime
    *
    * @var string $_masterFile
    */
    var $_masterFile = '';
    
    /**
    * Masterfile mtime
    *
    * @var int $_masterFile_mtime
    */
    var $_masterFile_mtime = 0;
    
    // --- Public methods ----
    
    /**
    * Constructor
    *
    * $options is an assoc. To have a look at availables options,
    * see the constructor of the Lite class in 'Lite.php'
    *
    * Comparing to Lite constructor, there is another option :
    * $options = array(
    *     (...) see Lite constructor
    *     'masterFile' => complete path of the file used for controlling the cache lifetime(string)
    * );
    *
    * @param array $options options
    * @access public
    */
    function File($options = array(NULL))
    {   
        $options['lifetime'] = 0;
        $this->Lite($options);
        if (isset($options['masterFile'])) {
            $this->_masterFile = $options['masterFile'];
        } else {
            return $this->raiseError('File : masterFile option must be set !');
        }
        if (!($this->_masterFile_mtime = @filemtime($this->_masterFile))) {
            return $this->raiseError('File : Unable to read masterFile : '.$this->_masterFile, -3);
        }
    }
    
    /**
    * Test if a cache is available and (if yes) return it
    *
    * @param string $id cache id
    * @param string $group name of the cache group
    * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
    * @return string data of the cache (else : false)
    * @access public
    */
    function get($id, $group = 'default', $doNotTestCacheValidity = false)
    {
        if ($data = parent::get($id, $group, true)) {
            if ($filemtime = $this->lastModified()) {
                if ($filemtime > $this->_masterFile_mtime) {
                    return $data;
                }
            }
        }
        return false;
    }

}
