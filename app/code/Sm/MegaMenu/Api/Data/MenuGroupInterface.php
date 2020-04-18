<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Api\Data;

interface MenuGroupInterface
{
	/**#@+
	 * Constants for keys of data array. Identical to the name of the getter in snake case
	 */
	const GROUP_ID  = 'group_id';
	const TITLE     = 'title';
	const STATUS    = 'status';
	const CONTENT   = 'content';

	public function getGroupId();

	public function getTitle();

	public function getStatus();

	public function getContent();

	public function setGroupId($groupId);

	public function setTitle($title);

	public function setStatus($status);

	public function setContent($content);
}