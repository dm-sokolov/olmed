<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Company_Controller
 *
 * @package HostCMS
 * @subpackage Company
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2020 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Company_Controller
{
	/**
	 * Build company array
	 * @param int $iSiteId site ID
	 * @return array
	 */
	static public function fillCompanies($iSiteId)
	{
		$aReturn = array();

		$iSiteId = intval($iSiteId);

		$oCompanies = Core_Entity::factory('Site', $iSiteId)->Companies;
		$oCompanies->queryBuilder()
			->orderBy('companies.name', 'ASC');

		$aCompanies = $oCompanies->findAll(FALSE);
		foreach ($aCompanies as $oCompany)
		{
			$aReturn[$oCompany->id] = $oCompany->name;
		}

		return $aReturn;
	}
}