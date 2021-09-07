<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Company_Controller_Edit
 *
 * @package HostCMS
 * @subpackage Company
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Company_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Load object's fields when object has been set
	 * После установки объекта загружаются данные о его полях
	 * @param object $object
	 * @return Company_Controller_Edit
	 */
	public function setObject($object)
	{
		$this
			->addSkipColumn('~address')
			->addSkipColumn('~phone')
			->addSkipColumn('~fax')
			->addSkipColumn('~site')
			->addSkipColumn('~email');

		parent::setObject($object);

		// Основная вкладка
		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		// Добавляем вкладки
		$this
			->addTabAfter($oTabBankingDetails = Admin_Form_Entity::factory('Tab')
				->caption(Core::_('Company.tabBankingDetails'))
				->name('BankingDetails'),
			$oMainTab);

		$oMainTab
			->add($oMainTabRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainTabRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainTabRow3 = Admin_Form_Entity::factory('Div')->class('row'));

		$oTabBankingDetails
			->add($oTabBankingDetailsRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oTabBankingDetailsRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oTabBankingDetailsRow3 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oTabBankingDetailsRow4 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oTabBankingDetailsRow5 = Admin_Form_Entity::factory('Div')->class('row'));

		$oAdditionalTab
			->add($oAdditionalTabRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oAdditionalTabRow2 = Admin_Form_Entity::factory('Div')->class('row'));

		$oMainTab
			// BankingDetails
			->move($this->getField('tin'), $oTabBankingDetails)
			->move($this->getField('kpp'), $oTabBankingDetails)
			->move($this->getField('psrn'), $oTabBankingDetails)
			->move($this->getField('okpo'), $oTabBankingDetails)
			->move($this->getField('okved'), $oTabBankingDetails)
			->move($this->getField('bic'), $oTabBankingDetails)
			->move($this->getField('current_account'), $oTabBankingDetails)
			->move($this->getField('correspondent_account'), $oTabBankingDetails)
			->move($this->getField('bank_name'), $oTabBankingDetails)
			->move($this->getField('bank_address'), $oTabBankingDetails)
			// GUID
			->move($this->getField('guid'), $oAdditionalTab);

		$oMainTab->move($this->getField('legal_name')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oMainTabRow1);
		$oMainTab->move($this->getField('accountant_legal_name')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oMainTabRow1);

		// Адреса
		$oCompanyAddressesRow = Directory_Controller_Tab::instance('address')
			->title(Core::_('Directory_Address.addresses'))
			->relation($this->_object->Company_Directory_Addresses)
			->execute();

		$oMainTab->add($oCompanyAddressesRow);

		// Телефоны
		$oCompanyPhonesRow = Directory_Controller_Tab::instance('phone')
			->title(Core::_('Directory_Phone.phones'))
			->relation($this->_object->Company_Directory_Phones)
			->execute();

		$oMainTab->add($oCompanyPhonesRow);

		// Email'ы
		$oCompanyEmailsRow = Directory_Controller_Tab::instance('email')
			->title(Core::_('Directory_Email.emails'))
			->relation($this->_object->Company_Directory_Emails)
			->execute();

		$oMainTab->add($oCompanyEmailsRow);

		// Сайты
		$oCompanyWebsitesRow = Directory_Controller_Tab::instance('website')
			->title(Core::_('Directory_Website.sites'))
			->relation($this->_object->Company_Directory_Websites)
			->execute();

		$oMainTab->add($oCompanyWebsitesRow);

		$oAdmin_Form_Entity_Section = Admin_Form_Entity::factory('Section')
			->caption(Core::_('Company.sites'))
			->id('accordion_' . $object->id);

		$oMainTab->add($oAdmin_Form_Entity_Section);

		// Sites
		$aTmp = array();
		$aCompany_Sites = $object->Company_Sites->findAll(FALSE);
		foreach ($aCompany_Sites as $oCompany_Site)
		{
			$aTmp[] = $oCompany_Site->site_id;
		}

		$aSites = Core_Entity::factory('Site')->findAll();
		foreach ($aSites as $oSite)
		{
			$oAdmin_Form_Entity_Section->add($oCheckbox = Admin_Form_Entity::factory('Checkbox')
				->divAttr(array('class' => 'form-group col-xs-12 col-md-6'))
				->name('site_' . $oSite->id)
				->caption($oSite->name)
			);

			in_array($oSite->id, $aTmp) && $oCheckbox->checked('checked');
		}

		$oTabBankingDetails->move($this->getField('tin')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow1);
		$oTabBankingDetails->move($this->getField('kpp')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow1);

		$oTabBankingDetails->move($this->getField('psrn')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow2);
		$oTabBankingDetails->move($this->getField('okpo')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow2);

		$oTabBankingDetails->move($this->getField('okved')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow3);
		$oTabBankingDetails->move($this->getField('bic')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow3);

		$oTabBankingDetails->move($this->getField('current_account')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow4);
		$oTabBankingDetails->move($this->getField('correspondent_account')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow4);

		$oTabBankingDetails->move($this->getField('bank_name')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow5);
		$oTabBankingDetails->move($this->getField('bank_address')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')),$oTabBankingDetailsRow5);

		$oAdditionalTab->move($this->getField('guid')->divAttr(array('class' => 'form-group col-xs-12')),$oAdditionalTabRow1);

		$title = $this->_object->id
			? Core::_('Company.company_form_edit_title', $this->_object->name)
			: Core::_('Company.company_form_add_title');

		$this->title($title);

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @hostcms-event Company_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		$this
			->addSkipColumn('phone')
			->addSkipColumn('fax')
			->addSkipColumn('site')
			->addSkipColumn('email');

		parent::_applyObjectProperty();

		$aTmp = array();

		$aCompany_Sites = $this->_object->Company_Sites->findAll(FALSE);
		foreach ($aCompany_Sites as $oCompany_Site)
		{
			if (Core_Array::getPost('site_' . $oCompany_Site->site_id))
			{
				$aTmp[] = $oCompany_Site->site_id;
			}
			else
			{
				$oCompany_Site->delete();
			}
		}

		$aSites = Core_Entity::factory('Site')->findAll(FALSE);
		foreach ($aSites as $oSite)
		{
			if (Core_Array::getPost('site_' . $oSite->id) && !in_array($oSite->id, $aTmp))
			{
				$oCompany_Site = Core_Entity::factory('Company_Site');
				$oCompany_Site->site_id = $oSite->id;
				$oCompany_Site->company_id = $this->_object->id;
				$oCompany_Site->save();
			}
		}

		$windowId = $this->_Admin_Form_Controller->getWindowId();

		// Адреса, установленные значения
		$aCompany_Directory_Addresses = $this->_object->Company_Directory_Addresses->findAll();
		foreach ($aCompany_Directory_Addresses as $oCompany_Directory_Address)
		{
			$oDirectory_Address = $oCompany_Directory_Address->Directory_Address;

			$sAddress = trim(Core_Array::getPost("address#{$oDirectory_Address->id}"));
			$sCountry = strval(Core_Array::getPost("address_country#{$oDirectory_Address->id}"));
			$sPostcode = strval(Core_Array::getPost("address_postcode#{$oDirectory_Address->id}"));
			$sCity = strval(Core_Array::getPost("address_city#{$oDirectory_Address->id}"));

			if (strlen($sAddress) || strlen($sCountry) || strlen($sPostcode) || strlen($sCity))
			{
				$oDirectory_Address
					->directory_address_type_id(intval(Core_Array::getPost("address_type#{$oDirectory_Address->id}", 0)))
					->country($sCountry)
					->postcode($sPostcode)
					->city($sCity)
					->value($sAddress)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} select[name='address_type#{$oDirectory_Address->id}']\").closest('.row').find('.btn-delete').get(0));")
					->execute();
				$this->_Admin_Form_Controller->addMessage(ob_get_clean());

				$oCompany_Directory_Address->Directory_Address->delete();
			}
		}

		// Адреса, новые значения
		$aAddresses = Core_Array::getPost('address', array());
		$aAddress_Country = Core_Array::getPost('address_country', array());
		$aAddress_Postcode = Core_Array::getPost('address_postcode', array());
		$aAddress_City = Core_Array::getPost('address_city', array());
		$aAddress_Types = Core_Array::getPost('address_type', array());

		if (is_array($aAddresses) && count($aAddresses))
		{
			$i = 0;
			foreach ($aAddresses as $key => $sAddress)
			{
				$sAddress = trim($sAddress);
				$sCountry = strval(Core_Array::get($aAddress_Country, $key));
				$sPostcode = strval(Core_Array::get($aAddress_Postcode, $key));
				$sCity = strval(Core_Array::get($aAddress_City, $key));

				if (strlen($sAddress) || strlen($sCountry) || strlen($sPostcode) || strlen($sCity))
				{
					$oDirectory_Address = Core_Entity::factory('Directory_Address')
						->directory_address_type_id(intval(Core_Array::get($aAddress_Types, $key)))
						->country($sCountry)
						->postcode($sPostcode)
						->city($sCity)
						->value($sAddress)
						->save();

					$this->_object->add($oDirectory_Address);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$(\"#{$windowId} select[name='address_type\\[\\]']\").eq({$i}).prop('name', 'address_type#{$oDirectory_Address->id}').closest('.row').find('.btn-delete').removeClass('hide');
						$(\"#{$windowId} input[name='address\\[\\]']\").eq({$i}).prop('name', 'address#{$oDirectory_Address->id}');
						$(\"#{$windowId} input[name='address_country\\[\\]']\").eq({$i}).prop('name', 'address_country#{$oDirectory_Address->id}');
						$(\"#{$windowId} input[name='address_postcode\\[\\]']\").eq({$i}).prop('name', 'address_postcode#{$oDirectory_Address->id}');
						$(\"#{$windowId} input[name='address_city\\[\\]']\").eq({$i}).prop('name', 'address_city#{$oDirectory_Address->id}');
						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		// Электронные адреса, установленные значения
		$aCompany_Directory_Emails = $this->_object->Company_Directory_Emails->findAll();
		foreach ($aCompany_Directory_Emails as $oCompany_Directory_Email)
		{
			$oDirectory_Email = $oCompany_Directory_Email->Directory_Email;

			$sEmail = trim(Core_Array::getPost("email#{$oDirectory_Email->id}"));

			if (!empty($sEmail))
			{
				$oDirectory_Email
					->directory_email_type_id(intval(Core_Array::getPost("email_type#{$oDirectory_Email->id}", 0)))
					->value($sEmail)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} select[name='email_type#{$oDirectory_Email->id}']\").closest('.row').find('.btn-delete111').get(0));")
					->execute();

				$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				$oCompany_Directory_Email->Directory_Email->delete();
			}
		}

		// Электронные адреса, новые значения
		$aEmails = Core_Array::getPost('email', array());
		$aEmail_Types = Core_Array::getPost('email_type', array());

		if (is_array($aEmails) && count($aEmails))
		{
			$i = 0;
			foreach ($aEmails as $key => $sEmail)
			{
				$sEmail = trim($sEmail);

				if (!empty($sEmail))
				{
					$oDirectory_Email = Core_Entity::factory('Directory_Email')
						->directory_email_type_id(intval(Core_Array::get($aEmail_Types, $key)))
						->value($sEmail)
						->save();

					$this->_object->add($oDirectory_Email);

					//$this->_object->add($oDirectory_Email);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$(\"#{$windowId} select[name='email_type\\[\\]']\").eq({$i}).prop('name', 'email_type#{$oDirectory_Email->id}').closest('.row').find('.btn-delete').removeClass('hide');
						$(\"#{$windowId} input[name='email\\[\\]']\").eq({$i}).prop('name', 'email#{$oDirectory_Email->id}');")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		// Телефоны, установленные значения
		$aCompany_Directory_Phones = $this->_object->Company_Directory_Phones->findAll();
		foreach ($aCompany_Directory_Phones as $oCompany_Directory_Phone)
		{
			$oDirectory_Phone = $oCompany_Directory_Phone->Directory_Phone;

			$sPhone = trim(Core_Array::getPost("phone#{$oDirectory_Phone->id}"));

			if (!empty($sPhone))
			{
				$oDirectory_Phone
					->directory_phone_type_id(intval(Core_Array::getPost("phone_type#{$oDirectory_Phone->id}", 0)))
					->value($sPhone)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} select[name='phone_type#{$oDirectory_Phone->id}']\").closest('.row').find('.btn-delete').get(0));")
					->execute();
				$this->_Admin_Form_Controller->addMessage(ob_get_clean());

				$oCompany_Directory_Phone->Directory_Phone->delete();
			}
		}

		// Телефоны, новые значения
		$aPhones = Core_Array::getPost('phone', array());
		$aPhone_Types = Core_Array::getPost('phone_type', array());

		if (is_array($aPhones) && count($aPhones))
		{
			$i = 0;
			foreach ($aPhones as $key => $sPhone)
			{
				$sPhone = trim($sPhone);

				if (!empty($sPhone))
				{
					$oDirectory_Phone = Core_Entity::factory('Directory_Phone')
						->directory_phone_type_id(intval(Core_Array::get($aPhone_Types, $key)))
						->value($sPhone)
						->save();

					$this->_object->add($oDirectory_Phone);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$(\"#{$windowId} select[name='phone_type\\[\\]']\").eq({$i}).prop('name', 'phone_type#{$oDirectory_Phone->id}').closest('.row').find('.btn-delete').removeClass('hide');
						$(\"#{$windowId} input[name='phone\\[\\]']\").eq({$i}).prop('name', 'phone#{$oDirectory_Phone->id}');
						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		// Cайты, установленные значения
		$aCompany_Directory_Websites = $this->_object->Company_Directory_Websites->findAll();
		foreach ($aCompany_Directory_Websites as $oCompany_Directory_Website)
		{
			$oDirectory_Website = $oCompany_Directory_Website->Directory_Website;

			$sWebsite_Address = trim(Core_Array::getPost("website_address#{$oDirectory_Website->id}"));

			if (!empty($sWebsite_Address))
			{
				$aUrl = parse_url($sWebsite_Address);

				// Если не был указан протокол, или
				// указанный протокол некорректен для url
				!array_key_exists('scheme', $aUrl)
					&& $sWebsite_Address = 'http://' . $sWebsite_Address;

				$oDirectory_Website
					->description(Core_Array::getPost("website_description#{$oDirectory_Website->id}"))
					->value($sWebsite_Address)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} input[name='website_address#{$oDirectory_Website->id}']\").closest('.row').find('.btn-delete').get(0));")
					->execute();

				$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				$oDirectory_Website->delete();
			}
		}

		// Сайты, новые значения
		$aWebsite_Addresses = Core_Array::getPost('website_address', array());
		$aWebsite_Names =  Core_Array::getPost('website_description', array());

		if (is_array($aWebsite_Addresses) && count($aWebsite_Addresses))
		{
			$i = 0;

			foreach ($aWebsite_Addresses as $key => $sWebsite_Address)
			{
				$sWebsite_Address = trim($sWebsite_Address);

				if (!empty($sWebsite_Address))
				{
					$aUrl = parse_url($sWebsite_Address);

					// Если не был указан протокол, или
					// указанный протокол некорректен для url
					!array_key_exists('scheme', $aUrl)
						&& $sWebsite_Address = 'http://' . $sWebsite_Address;

					$oDirectory_Website = Core_Entity::factory('Directory_Website')
						->description(Core_Array::get($aWebsite_Names, $key))
						->value($sWebsite_Address);

					$this->_object->add($oDirectory_Website);

					$oCompany_Directory_Website = $oDirectory_Website->Company_Directory_Websites->getByCompany_Id($this->_object->id);

					//$this->_object->add($oDirectory_Email);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$(\"#{$windowId} input[name='website_address\\[\\]']\").eq({$i}).prop('name', 'website_address#{$oCompany_Directory_Website->id}').closest('.row').find('.btn-delete').removeClass('hide');
						$(\"#{$windowId} input[name='website_description\\[\\]']\").eq({$i}).prop('name', 'website_description#{$oCompany_Directory_Website->id}');

						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}
}