<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\Widget\Form\Renderer;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Sm\MegaMenu\Helper\Defaults;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class SortAble extends Template implements RendererInterface
{
	/**
	 * @var AbstractElement
	 */
	protected $_element;

	/*
	 * @var Sm\MegaMenu\Helper\Defaults
	 * */
	protected $_defaults;

	protected $_objectManager;

	protected $_dataCollection = [];

	protected $_dataObject = [];

	protected $_coreRegistry = null;

	public function __construct(
		Context $context,
		Registry $registry,
		Defaults $defaults,
		Collection $collection,
		DataObject $dataObject,
		ObjectManagerInterface $objectManagerInterface,
		array $data = []
	)
	{
		$this->_coreRegistry = $registry;
		$this->_defaults = $defaults;
		$this->_dataCollection = $collection;
		$this->_dataObject = $dataObject;
		$this->_objectManager = $objectManagerInterface;
		parent::__construct($context, $data);
	}

	/**
	 * Internal constructor, that is called from real constructor
	 * @return void
	 */
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('Sm_MegaMenu::menuitems/edit/form/renderer/sortable.phtml');
	}

	/**
	 * @return AbstractElement
	 */
	public function getElement()
	{
		return $this->_element;
	}

	/**
	 * @param AbstractElement $element
	 * @return string
	 */
	public function render(AbstractElement $element)
	{
		$this->_element = $element;
		return $this->toHtml();
	}

	protected function _prepareLayout()
	{
		parent::_prepareLayout();
	}

	public function getDivSortableStyle()
	{
		return sprintf('width:%s; min-height:%s; border: %s; background: %s;', '100%', '10px', 'none', 'transparent');
	}

	public function createMenuItems()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function createMenuItemsCollection()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuItems\Collection');
	}

	public function truncate($string, $length, $etc = '...')
	{
		return $string ? $this->mbsptruncate($string, $length, $etc) : $this->sptruncate($string, $length, $etc);
	}

	public function mbsptruncate($string, $length, $etc)
	{
		$encoding = mb_detect_encoding($string);
		if ($length > 0 && $length < mb_strlen ($string, $encoding))
		{
			$buffer = '';
			$buffer_length = 0;
			$parts = preg_split('/(<[^>]*>)/', $string, - 1, PREG_SPLIT_DELIM_CAPTURE);
			$self_closing_tag = explode (', ', 'area,base,basefont,br,col,frame,hr,img,input,isindex,link,meta,param,embed');
			$open = array();
			foreach ($parts as $s)
			{
				if (false === mb_strpos ($s, '<'))
				{
					$s_length = mb_strlen ($s, $encoding);
					if ($buffer_length + $s_length < $length)
					{
						$buffer .= $s;
						$buffer_length += $s_length;
					}
					else if ($buffer_length + $s_length == $length)
					{
						if (!empty( $etc ))
							$buffer .= ( $s[$s_length - 1] == ' ' )?$etc:" $etc";
						break;
					}
					else
					{
						$words = preg_split('/([^\s]*)/', $s, - 1, PREG_SPLIT_DELIM_CAPTURE);
						$space_end = false;
						foreach ($words as $w)
						{
							if ($w_length = mb_strlen ($w, $encoding))
							{
								if ($buffer_length + $w_length < $length)
								{
									$buffer .= $w;
									$buffer_length += $w_length;
									$space_end = ( trim ($w) == '' );
								}
								else
								{
									if (!empty( $etc ))
									{
										$more = $space_end?$etc:" $etc";
										$buffer .= $more;
										$buffer_length += mb_strlen ($more);
									}
									break;
								}
							}
						}
						break;
					}
				}
				else
				{
					preg_match ('/^<([\/]?\s?)([a-zA-Z0-9]+)\s?[^>]*>$/', $s, $m);
					if (empty( $m[1] ) && isset( $m[2] ) && !in_array ($m[2], $self_closing_tag))
						array_push ($open, $m[2]);
					else if (trim ($m[1]) == '/')
						$tag = array_pop ($open);
					$buffer .= $s;
				}
			}
			$count_open = count ($open);
			if ($count_open > 0)
			{
				$tag = array_pop ($open);
				$buffer .= "</$tag>";
			}
			return $buffer;
		}
		return $string;
	}

	public function sptruncate($string, $length, $etc)
	{
		if ($length > 0 && $length < strlen($string))
		{
			$buffer = '';
			$buffer_length = 0;
			$parts = preg_split('/(<[^>]*>)/', $string, - 1, PREG_SPLIT_DELIM_CAPTURE);
			$self_closing_tag = preg_split (',', 'area,base,basefont,br,col,frame,hr,img,input,isindex,link,meta,param,embed');
			$open = array();
			foreach ($parts as $s)
			{
				if (false === strpos($s, '<'))
				{
					$s_length = strlen($s);
					if ($buffer_length + $s_length < $length)
					{
						$buffer .= $s;
						$buffer_length += $s_length;
					}
					else if ($buffer_length + $s_length == $length)
					{
						if (!empty( $etc ))
							$buffer .= ( $s[$s_length - 1] == ' ' )?$etc:" $etc";
						break;
					}
					else
					{
						$words = preg_split('/([^\s]*)/', $s, - 1, PREG_SPLIT_DELIM_CAPTURE);
						$space_end = false;
						foreach ($words as $w)
						{
							if ($w_length = strlen($w))
							{
								if ($buffer_length + $w_length < $length)
								{
									$buffer .= $w;
									$buffer_length += $w_length;
									$space_end = (trim($w) == '');
								}
								else
								{
									if (!empty( $etc ))
										$more = $space_end?$etc:" $etc";
									$buffer .= $more;
									$buffer_length += strlen ($more);
									break;
								}
							}
						}
						break;
					}
				}
				else
				{
					preg_match ('/^<([\/]?\s?)([a-zA-Z0-9]+)\s?[^>]*>$/', $s, $m);
					//$tagclose = isset($m[1]) && trim($m[1])=='/';
					if (empty( $m[1] ) && isset( $m[2] ) && !in_array ($m[2], $self_closing_tag))
						array_push($open, $m[2]);
					else
						if (trim ($m[1]) == '/')
							$tag = array_pop ($open);
					$buffer .= $s;
				}
			}
			// close tag openned.
			if (count ($open) > 0)
			{
				$tag = array_pop($open);
				$buffer .= "</$tag>";
			}

			return $buffer;
		}
		return $string;
	}

	public function dataItems()
	{
		$output = '';
		$config = $this->_defaults->get($attributes = []);
		$items = $this->_coreRegistry->registry('megamenu_items');
		foreach ($items as $item) {
			if (($item['depth'] + 1) <= $config['end_level']) {
				$enableUrl = $this->getUrl('*/menuitems/setEnableItemsByItemsId', [
					'gid'   => $item['group_id'],
					'id'   => $item['items_id']
				]);

				$disableUrl = $this->getUrl('*/menuitems/setDisableItemsByItemsId', [
					'gid'   => $item['group_id'],
					'id'   => $item['items_id']
				]);

				$duplicateUrl = $this->getUrl('*/menuitems/duplicate', [
					'gid'   => $item['group_id'],
					'id'   => $item['items_id']
				]);

				$editUrl = $this->getUrl('*/menuitems/edit', [
					'gid'   => $item['group_id'],
					'id'   => $item['items_id']
				]);

				$deleteUrl = $this->getUrl('*/menuitems/delete', [
					'gid'   => $item['group_id'],
					'id'   => $item['items_id']
				]);

				if ($item['status'] == 1)
				{
					$output .= '<li class="dd-item" data-items_id='.$item['items_id'].' data-depth='.$item['depth'].'>';
					$output .= '<div class="dd-handle">';
				} elseif ($item['status']== 2)
				{
					$output .= '<li class="dd-item" data-items_id='.$item['items_id'].' data-depth='.$item['depth'].'>';
					$output .= '<div class="dd-handle disabled_items">';
				}
				$output .= '['.$item['items_id'].'] '.$this->truncate($item['title'], 50);
				$output .= '</div>';
				$output .= '<div class="action_sortable right">';
				if ($item['status'] == 1)
				{
					$output .= '<button type="button" title="Disable" onclick="setLocation(\''.$disableUrl.'\')" class="action_sortable_changestatus">Disable</button>';
					$output .= '<button type="button" title="Duplicate" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$duplicateUrl.'\')" class="action_sortable_duplicate">Duplicate</button>';
					$output .= '<button type="button" title="Edit" onclick="setLocation(\''.$editUrl.'\')" class="action_sortable_edit">Edit</button>';
					$output .= '<button type="button" title="Delete" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$deleteUrl.'\')" class="action_sortable_delete delete">Delete</button>';
				}
				elseif ($item['status'] == 2)
				{
					$output .= '<button type="button" title="Enable" onclick="setLocation(\''.$enableUrl.'\')" class="action_sortable_changestatus disabled_action">Enable</button>';
					$output .= '<button type="button" title="Duplicate" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$duplicateUrl.'\')" class="action_sortable_duplicate disabled_action">Duplicate</button>';
					$output .= '<button type="button" title="Edit" onclick="setLocation(\''.$editUrl.'\')" class="action_sortable_edit disabled_action">Edit</button>';
					$output .= '<button type="button" title="Delete" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$deleteUrl.'\')" class="action_sortable_delete delete disabled_action">Delete</button>';
				}
				$output .= '</div>';
				$output .= $this->getChildItems($item);
				$output .= '</li>';
			}
		}
		return $output;
	}

	public function getChildItems($item)
	{
		$output = '';
		$childItems = $this->createMenuItems()->getChildsDirectlyByItem($item, 1);
		if (count($childItems) > 0)
		{
			$output .= '<ol class="dd-list sortable_sub_categories vertical">';
			foreach ($childItems as $cItems)
			{
				$enableUrl = $this->getUrl('*/menuitems/setEnableItemsByItemsId', [
					'gid'   => $item['group_id'],
					'id'   => $cItems['items_id']
				]);

				$disableUrl = $this->getUrl('*/menuitems/setDisableItemsByItemsId', [
					'gid'   => $item['group_id'],
					'id'   => $cItems['items_id']
				]);

				$duplicateUrl = $this->getUrl('*/menuitems/duplicate', [
					'gid'   => $item['group_id'],
					'id'   => $cItems['items_id']
				]);

				$editUrl = $this->getUrl('*/menuitems/edit', [
					'gid'   => $item['group_id'],
					'id'   => $cItems['items_id']
				]);

				$deleteUrl = $this->getUrl('*/menuitems/delete', [
					'gid'   => $item['group_id'],
					'id'   => $cItems['items_id']
				]);

				if ($cItems['status'] == 1)
				{
					$output .= '<li class="dd-item" data-items_id='.$cItems['items_id'].' data-depth='.$cItems['depth'].'>';
					$output .= '<div class="dd-handle">';
				} elseif ($cItems['status'] == 2)
				{
					$output .= '<li class="dd-item" data-items_id='.$cItems['items_id'].' data-depth='.$cItems['depth'].'>';
					$output .= '<div class="dd-handle disabled_items">';
				}
				$output .= '['.$cItems['items_id'].'] '.$this->truncate($cItems['title'], 50);
				$output .= '</div>';
				$output .= '<div class="action_sortable right">';
				if ($cItems['status'] == 1)
				{
					$output .= '<button type="button" title="Disable" onclick="setLocation(\''.$disableUrl.'\')" class="action_sortable_changestatus">Disable</button>';
					$output .= '<button type="button" title="Duplicate" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$duplicateUrl.'\')" class="action_sortable_duplicate">Duplicate</button>';
					$output .= '<button type="button" title="Edit" onclick="setLocation(\''.$editUrl.'\')" class="action_sortable_edit">Edit</button>';
					$output .= '<button type="button" title="Delete" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$deleteUrl.'\')" class="action_sortable_delete delete">Delete</button>';
				} elseif ($cItems['status'] == 2)
				{
					$output .= '<button type="button" title="Enable" onclick="setLocation(\''.$enableUrl.'\')" class="action_sortable_changestatus disabled_action">Enable</button>';
					$output .= '<button type="button" title="Duplicate" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$duplicateUrl.'\')" class="action_sortable_duplicate disabled_action">Duplicate</button>';
					$output .= '<button type="button" title="Edit" onclick="setLocation(\''.$editUrl.'\')" class="action_sortable_edit disabled_action">Edit</button>';
					$output .= '<button type="button" title="Delete" onclick="deleteConfirm(\'Are you sure you want to do this?\', \''.$deleteUrl.'\')" class="action_sortable_delete delete disabled_action">Delete</button>';
				}
				$output .= '</div>';
				$output .= $this->getChildItems($cItems);
				$output .= '</li>';
			}
			$output .= '</ol>';
		}
		else
			return false;

		return $output;
	}

	public function setController()
	{
		$group_id = $this->_coreRegistry->registry('group_id');
		$url = "megamenu/menuitems/sortableItems";
		$link_url = rtrim($this->getUrl($url, array('gid' => $group_id)), '/');
		return $link_url;
	}

	public function enableAll()
	{
		$group_id = $this->_coreRegistry->registry('group_id');
		$url = $this->getUrl('*/menuitems/setEnableAll', array(
			'gid' => $group_id
		));
		return $url;
	}

	public function disableAll()
	{
		$group_id = $this->_coreRegistry->registry('group_id');
		$url = $this->getUrl('*/menuitems/setDisableAll', array(
			'gid' => $group_id
		));
		return $url;
	}

	public function setLinkAddItems()
	{
		$group_id = $this->_coreRegistry->registry('group_id');
		$url = $this->getUrl('*/menuitems/newaction', array(
			'gid' => $group_id
		));
		return $url;
	}

	public function checkGroupId()
	{
		if ($this->_coreRegistry->registry('group_id') && (count($this->_coreRegistry->registry('megamenu_items')) > 0))
			return true;
		else
			return false;
	}
}