<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Shop_Purchase_Discount_Model
 *
 * @package HostCMS
 * @subpackage Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Purchase_Discount_Model extends Core_Entity
{
	/**
	 * shop_purchase_discount_coupon_id, uses in the getByCouponText()
	 */
	public $shop_purchase_discount_coupon_id = NULL;

	/**
	 * One-to-many or many-to-many relations
	 * @var array
	 */
	protected $_hasMany = array(
		'shop_purchase_discount_coupon' => array()
	);

	/**
	 * One-to-one relations
	 * @var array
	 */
	protected $_hasOne = array(
		'shop' => array()
	);

	/**
	 * List of preloaded values
	 * @var array
	 */
	protected $_preloadValues = array(
		'active' => 1,
		'value' => 0,
		'min_amount' => 0,
		'max_amount' => 0,
		'min_count' => 0,
		'max_count' => 0
	);

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'shop' => array(),
		'shop_currency' => array(),
		'user' => array()
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
			$this->_preloadValues['start_datetime'] = Core_Date::timestamp2sql(time());
			$this->_preloadValues['end_datetime'] = Core_Date::timestamp2sql(strtotime("+1 year"));
		}
	}

	/**
	 * Order's discount
	 * Расчитанный размер скидки для заказа
	 * @var float
	 */
	protected $_discountAmount = NULL;

	/**
	 * Set discount amount
	 * @param float $discountAmount amount
	 * @return self
	 */
	public function discountAmount($discountAmount)
	{
		$this->_discountAmount = $discountAmount;
		return $this;
	}

	/**
	 * Get discount amount
	 * @return float
	 */
	public function getDiscountAmount()
	{
		return $this->_discountAmount;
	}

	/**
	 * Get All Discounts By Coupon Text
	 * @param string $couponText coupon code
	 * @return array
	 */
	public function getAllByCouponText($couponText)
	{
		$sDatetime = Core_Date::timestamp2sql(time());

		$this->queryBuilder()
			->select('shop_purchase_discounts.*',
				array('shop_purchase_discount_coupons.id', 'shop_purchase_discount_coupon_id')
			)
			->join('shop_purchase_discount_coupons', 'shop_purchase_discounts.id', '=', 'shop_purchase_discount_coupons.shop_purchase_discount_id')
			->where('shop_purchase_discount_coupons.active', '=', 1)
			->where('shop_purchase_discount_coupons.deleted', '=', 0)
			->where('shop_purchase_discount_coupons.text', '=', $couponText)
			->where('shop_purchase_discount_coupons.start_datetime', '<=', $sDatetime)
			->where('shop_purchase_discount_coupons.end_datetime', '>=', $sDatetime)
			->open()
			->where('shop_purchase_discount_coupons.count', '>', 0)
			->setOr()
			->where('shop_purchase_discount_coupons.count', '=', -1)
			->close();

		// Чтобы получить новый объект с заполненным shop_purchase_discount_coupon_id используем FALSE
		return $this->findAll(FALSE);
	}

	/**
	 * Get Discounts By Coupon Text
	 * @param string $couponText coupon code
	 * @return self|NULL
	 */
	public function getByCouponText($couponText)
	{
		// Чтобы получить новый объект с заполненным shop_purchase_discount_coupon_id используем FALSE
		$aObjects = $this->getAllByCouponText($couponText);

		return isset($aObjects[0])
			? $aObjects[0]
			: NULL;
	}

	/**
	 * Check available discounts with position
	 * @return boolean
	 */
	public function checkAvailableWithPosition()
	{
		$this->queryBuilder()
			->where('active', '=', 1)
			->where('position', '>', 0)
			->where('start_datetime', '<=', Core_Date::timestamp2sql(time()))
			->where('end_datetime', '>=', Core_Date::timestamp2sql(time()))
			->clearOrderBy()
			->limit(1);

		return $this->getCount() > 0;
	}

	/**
	 * Change status of activity for discount
	 * @return self
	 */
	public function changeStatus()
	{
		$this->active = 1 - $this->active;
		return $this->save();
	}

	/**
	 * Copy object
	 * @return Core_Entity
	 * @hostcms-event shop_purchase_discount.onAfterRedeclaredCopy
	 */
	public function copy()
	{
		$newObject = parent::copy();

		$aShop_Purchase_Discount_Coupons = $this->Shop_Purchase_Discount_Coupons->findAll();
		foreach ($aShop_Purchase_Discount_Coupons as $oShop_Purchase_Discount_Coupon)
		{
			$oNew_Shop_Purchase_Discount_Coupon = $oShop_Purchase_Discount_Coupon->copy();
			$newObject->add($oNew_Shop_Purchase_Discount_Coupon);
		}

		Core_Event::notify($this->_modelName . '.onAfterRedeclaredCopy', $newObject, array($this));

		return $newObject;
	}

	/**
	 * Delete object from database
	 * @param mixed $primaryKey primary key for deleting object
	 * @return Core_Entity
	 * @hostcms-event shop_purchase_discount.onBeforeRedeclaredDelete
	 */
	public function delete($primaryKey = NULL)
	{
		if (is_null($primaryKey))
		{
			$primaryKey = $this->getPrimaryKey();
		}

		$this->id = $primaryKey;

		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredDelete', $this, array($primaryKey));

		$this->Shop_Purchase_Discount_Coupons->deleteAll(FALSE);

		return parent::delete($primaryKey);
	}

	/**
	 * Get XML for entity and children entities
	 * @return string
	 * @hostcms-event shop_purchase_discount.onBeforeRedeclaredGetXml
	 */
	public function getXml()
	{
		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredGetXml', $this);

		$this->_prepareData();

		return parent::getXml();
	}

	/**
	 * Get stdObject for entity and children entities
	 * @return stdObject
	 * @hostcms-event shop_purchase_discount.onBeforeRedeclaredGetStdObject
	 */
	public function getStdObject($attributePrefix = '_')
	{
		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredGetStdObject', $this);

		$this->_prepareData();

		return parent::getStdObject($attributePrefix);
	}

	/**
	 * Prepare entity and children entities
	 * @return self
	 */
	protected function _prepareData()
	{
		$this->clearXmlTags()
			->addXmlTag('discount_amount', $this->_discountAmount);

		return $this;
	}

	/**
	 * Backend callback method
	 * @param Admin_Form_Field $oAdmin_Form_Field
	 * @param Admin_Form_Controller $oAdmin_Form_Controller
	 * @return string
	 */
	public function valueBackend($oAdmin_Form_Field, $oAdmin_Form_Controller)
	{
		return $this->type == 0
			? $this->value . '%'
			: $this->value . htmlspecialchars($this->shop_currency_id ? ' ' . $this->Shop_Currency->name : '');
	}

	/**
	 * Backend callback method
	 * @return string
	 */
	public function nameBackend()
	{
		$oCore_Html_Entity_Div = Core::factory('Core_Html_Entity_Div')->value(
			htmlspecialchars($this->name)
		);

		$bRightTime = ($this->start_datetime == '0000-00-00 00:00:00' || time() > Core_Date::sql2timestamp($this->start_datetime))
			&& ($this->end_datetime == '0000-00-00 00:00:00' || time() < Core_Date::sql2timestamp($this->end_datetime));

		!$bRightTime && $oCore_Html_Entity_Div->class('wrongTime');

		// Зачеркнут в зависимости от статуса родительского товара или своего статуса
		if (!$this->active)
		{
			$oCore_Html_Entity_Div->class('inactive');
		}
		elseif (!$bRightTime)
		{
			$oCore_Html_Entity_Div
				->add(
					Core::factory('Core_Html_Entity_I')->class('fa fa-clock-o black')
				);
		}

		$oCore_Html_Entity_Div->execute();
	}

	/**
	 * Backend callback method
	 * @param Admin_Form_Field $oAdmin_Form_Field
	 * @param Admin_Form_Controller $oAdmin_Form_Controller
	 * @return string
	 */
	public function min_amountBackend($oAdmin_Form_Field, $oAdmin_Form_Controller)
	{
		return $this->min_amount == 0
			? '—'
			: $this->min_amount;
	}

	/**
	 * Backend callback method
	 * @param Admin_Form_Field $oAdmin_Form_Field
	 * @param Admin_Form_Controller $oAdmin_Form_Controller
	 * @return string
	 */
	public function max_amountBackend($oAdmin_Form_Field, $oAdmin_Form_Controller)
	{
		return $this->max_amount == 0
			? '—'
			: $this->max_amount;
	}
}