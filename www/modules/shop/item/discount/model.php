<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Shop_Item_Discount_Model
 *
 * @package HostCMS
 * @subpackage Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2020 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Item_Discount_Model extends Core_Entity
{
	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'shop_discount' => array(),
		'shop_item' => array(),
		'user' => array()
	);

	/**
	 * List of preloaded values
	 * @var array
	 */
	protected $_preloadValues = array(
	);

	/**
	 * Constructor.
	 * @param int $id entity ID
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if (is_null($id) && !$this->loaded())
		{
			$oUser = Core_Auth::getCurrentUser();
			$this->_preloadValues['user_id'] = is_null($oUser) ? 0 : $oUser->id;
		}
	}

	/**
	 * Get element by discount id
	 * @param int $iDiscountId id
	 * @return Shop_Discount|NULL
	 */
	public function getByDiscountId($iDiscountId)
	{
		$this->queryBuilder()
			//->clear()
			->where('shop_discount_id', '=', $iDiscountId)
			->limit(1);

		$aShop_Discounts = $this->findAll();

		if (isset($aShop_Discounts[0]))
		{
			return $aShop_Discounts[0];
		}

		return NULL;
	}
}