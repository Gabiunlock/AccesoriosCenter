<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\MegaMenu\Block\Cache\Lite;

use Sm\MegaMenu\Block\Cache\Lite\Output;

class NestedOutput extends Output
{
	private $nestedIds = array();
	private $nestedGroups = array();

    /**
    * Start the cache
    *
    * @param string $id cache id
    * @param string $group name of the cache group
    * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
    * @return boolean|string false if the cache is not hit else the data
    * @access public
    */
    function start($id, $group = 'default', $doNotTestCacheValidity = false)
    {
    	$this->nestedIds[] = $id;
    	$this->nestedGroups[] = $group;
    	$data = $this->get($id, $group, $doNotTestCacheValidity);
        if ($data !== false) {
            return $data;
        }
        ob_start();
        ob_implicit_flush(false);
        return false;
    }

    /**
    * Stop the cache
    *
    * @param boolen
    * @return string return contents of cache
    */
    function end()
    {
        $data = ob_get_contents();
        ob_end_clean();
        $id = array_pop($this->nestedIds);
        $group = array_pop($this->nestedGroups);
        $this->save($data, $id, $group);
		return $data;
    }

}
