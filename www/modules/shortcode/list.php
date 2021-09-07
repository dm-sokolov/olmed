<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Shortcode List
 *
 * DO NOT EDIT THIS FILE BY HAND!
 * YOUR CHANGES WILL BE OVERWRITTEN!
 *
 * @package HostCMS 6\Shortcode
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2017 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */

class Shortcode_List
{
	static public function yandex_map($args, $body)
	{
		$args += array(
			'id' => 'yandexMap',
			'latlng' => '55.684758, 37.738521',
			'content' => '<strong>Маркер</strong> места',
			'color' => '#0095b6',
			'width' => '500px',
			'height' => '400px',
			'zoom' => 15,
		);
		
		ob_start();
		?>
		<script type="text/javascript">
			ymaps.ready(init);
		
			function init() {
				var myMap = new ymaps.Map("<?php echo htmlspecialchars($args['id'])?>", {
						center: [<?php echo htmlspecialchars($args['latlng'])?>],
						zoom: <?php echo intval($args['zoom'])?>
					}, {
						searchControlProvider: 'yandex#search'
					});
		
				myMap.geoObjects
					.add(new ymaps.Placemark([<?php echo htmlspecialchars($args['latlng'])?>], {
						balloonContent: "<?php echo $args['content']?>"
					}, {
						preset: 'islands#icon',
						iconColor: "<?php echo htmlspecialchars($args['color'])?>"
					}));
			}
		</script>
		
		<style>
			#<?php echo htmlspecialchars($args['id'])?> {
				width: <?php echo htmlspecialchars($args['width'])?>; height: <?php echo htmlspecialchars($args['height'])?>; padding: 0; margin: 0;
			}
		</style>
		
		<div id="<?php echo htmlspecialchars($args['id'])?>"></div>
		
		<?php
		return ob_get_clean();
	}

	static public function shop($args, $body)
	{
		$args += array(
			'xsl' => 'МагазинКаталогТоваровНаГлавнойСпецПред',
			'limit' => 3,
			'group' => FALSE,
		);
		
		ob_start();
		
		if (Core::moduleIsActive('shop'))
		{
			if (isset($args['id']) && $args['id'])
			{
				$Shop_Controller_Show = new Shop_Controller_Show(
					Core_Entity::factory('Shop', $args['id'])
				);
		
				$oXsl = Core_Entity::factory('Xsl')->getByName($args['xsl']);
		
				if ($oXsl)
				{
					$Shop_Controller_Show
						->xsl($oXsl)
						->groupsMode('none')
						->itemsForbiddenTags(array('text'))
						->group($args['group'])
						->limit($args['limit'])
						->show();
				}
				else
				{
					?>Ошибка, XSL не найден!<?php
				}
			}
			else
			{
				?>Ошибка, ID магазина не указан!<?php
			}
		}
		
		return ob_get_clean();
	}

	static public function informationsystem($args, $body)
	{
		$args += array(
			'xsl' => 'СписокНовостейНаГлавной',
			'limit' => 5,
			'group' => FALSE,
		);
				
		ob_start();
		
		if (Core::moduleIsActive('informationsystem'))
		{
			if (isset($args['id']) && $args['id'])
			{
				$Informationsystem_Controller_Show = new Informationsystem_Controller_Show(
					Core_Entity::factory('Informationsystem', $args['id'])
				);
		
				$oXsl = Core_Entity::factory('Xsl')->getByName($args['xsl']);
		
				if ($oXsl)
				{
					$Informationsystem_Controller_Show
						->xsl($oXsl)
						->groupsMode('none')
						->itemsForbiddenTags(array('text'))
						->group($args['group'])
						->limit($args['limit']);
		                        
		                         isset($args['items']) && $Informationsystem_Controller_Show
					->informationsystemItems()
						->queryBuilder()
						->where('id', 'IN', explode(',', $args['items'])); 
		
					isset($args['othername']) && $Informationsystem_Controller_Show->addEntity(
						Core::factory('Core_Xml_Entity')
							->name('other_name')->value($args['othername'])
						);
		
					$Informationsystem_Controller_Show->show();
				}
				else
				{
					?>Ошибка, XSL не найден!<?php
				}
			}
			else
			{
				?>Ошибка, ID информационной системы не указан!<?php
			}
		}
		
		return ob_get_clean();
	}

	static public function specialist($args, $body)
	{
		$args += array(
			'limit' => 5,
			'groups' => FALSE,
		);
				
		ob_start();
		
		if (Core::moduleIsActive('informationsystem'))
		{
			if (isset($args['id']) && $args['id'])
			{
				Core_Event::attach('informationsystem_item.onBeforeRedeclaredGetXml', array('Informationsystem_Item_Observer', 'onBeforeRedeclaredGetXml'));
		
				$Informationsystem_Controller_Show = new Informationsystem_Controller_Show(
					Core_Entity::factory('Informationsystem', $args['id'])
				);
		
				$oXsl = Core_Entity::factory('Xsl')->getByName($args['xsl']);
		
				if ($oXsl)
				{
					$Informationsystem_Controller_Show
						->xsl($oXsl)
						->groupsMode('none')
						->itemsForbiddenTags(array('text'))
						->group($args['groups'])
						->limit($args['limit'])
					->addCacheSignature('group' . $args['groups'])
					->addCacheSignature('limit' . $args['limit']);
					
					isset($args['othername']) && $Informationsystem_Controller_Show
						->addEntity(
							Core::factory('Core_Xml_Entity')
								->name('other_name')->value($args['othername'])
							)
						->addCacheSignature('other_name=' . $args['othername']);
		
					$Informationsystem_Controller_Show->show();
				}
				else
				{
					?>Ошибка, XSL не найден!<?php
				}
		
				Core_Event::detach('informationsystem_item.onBeforeRedeclaredGetXml', array('Informationsystem_Item_Observer', 'onBeforeRedeclaredGetXml'));
			}
			else
			{
				?>Ошибка, ID информационной системы не указан!<?php
			}
		}
		
		return ob_get_clean();
	}

	static public function review($args, $body)
	{
		$args += array(
			'limit' => 5,
			'group' => FALSE,
		);
				
		ob_start();
		
		if (Core::moduleIsActive('informationsystem'))
		{
			if (isset($args['id']) && $args['id'])
			{
				Core_Event::attach('informationsystem_item.onBeforeRedeclaredGetXml', array('Informationsystem_Item_Observer', 'onBeforeRedeclaredGetXml'));
		
				$Informationsystem_Controller_Show = new Informationsystem_Controller_Show(
					Core_Entity::factory('Informationsystem', $args['id'])
				);
		
				$oXsl = Core_Entity::factory('Xsl')->getByName($args['xsl']);
		
				if ($oXsl)
				{
					$Informationsystem_Controller_Show
						->xsl($oXsl)
						->groupsMode('none')
						->itemsForbiddenTags(array('text'))
						->group($args['group'])
						->limit($args['limit']);
		
					isset($args['othername']) && $Informationsystem_Controller_Show->addEntity(
						Core::factory('Core_Xml_Entity')
							->name('other_name')->value($args['othername'])
						);
		
					$Informationsystem_Controller_Show->show();
				}
				else
				{
					?>Ошибка, XSL не найден!<?php
				}
		
				Core_Event::detach('informationsystem_item.onBeforeRedeclaredGetXml', array('Informationsystem_Item_Observer', 'onBeforeRedeclaredGetXml'));
			}
			else
			{
				?>Ошибка, ID информационной системы не указан!<?php
			}
		}
		
		return ob_get_clean();
	}

	static public function priceitem($args, $body)
	{
		ob_start();
		
		if (Core::moduleIsActive('informationsystem'))
		{
		  if (isset($args['id']) && $args['id']) {
			$Informationsystem_Controller_Show = new Informationsystem_Controller_Show(
					Core_Entity::factory('Informationsystem', $args['id'])
				);
		
			$oXsl = Core_Entity::factory('Xsl')->getByName($args['xsl']);
		
			if ($oXsl)
			{
				$Informationsystem_Controller_Show
					->xsl($oXsl)
					->groupsMode('all')
					->itemsForbiddenTags(array('text'))
		            ->itemsProperties(TRUE)
					->group(FALSE);
		
		
				isset($args['groups']) && $Informationsystem_Controller_Show
					->informationsystemGroups()
					->queryBuilder()
					->where('id', 'IN', explode(',', $args['groups']))
		            ->setOr()
		            ->where('parent_id', 'IN', explode(',', $args['groups']));
					$Informationsystem_Controller_Show->addCacheSignature('groups' . $args['groups']);
		               
		         /*      isset($args['groups']) && $Informationsystem_Controller_Show
					->informationsystemItems()
					->queryBuilder()
					->where('informationsystem_group_id', 'IN', explode(',', $args['groups']))
		                        
		                        ->where('informationsystem_group.parent_group_id', 'IN', explode(',', $args['groups']))
		                     ;
		       */
		
				$Informationsystem_Controller_Show->limit(1000);
		
				isset($args['othername']) && $Informationsystem_Controller_Show->addEntity(
					Core::factory('Core_Xml_Entity')
						->name('other_name')->value($args['othername'])
					)->addCacheSignature('other_name=' . $args['othername']);
		
				$Informationsystem_Controller_Show->show();
			}
		}
			else
			{
				?>Ошибка, XSL не найден!<?php
			}
		
		}
		
		return ob_get_clean();
	}

	static public function document($args, $body)
	{
		ob_start();
		
		if (Core::moduleIsActive('document'))
		{
			if (isset($args['id']) && $args['id'])
			{
				Core_Entity::factory('Document', $args['id'])->execute();
			}
			else
			{
				?>Ошибка, ID документа не указан!<?php
			}
		}
		
		return ob_get_clean();
	}

	static public function donate($args, $body)
	{
		$args += array(
			'sum' => 100,
			'targets' => 'Благотворительность',
			'project-name' => 'Кошкин дом'
		);
		
		if (!isset($args['project-site']))
		{	
			$oSite = Core_Entity::factory('Site', CURRENT_SITE);
			
			$oSiteAlias = $oSite->getCurrentAlias();
			if ($oSiteAlias)
			{
				$args['project-site'] = 'http://' . $oSiteAlias->name;
			}
			else
			{
				return 'Ошибка, домен для сайта не указан!';
			}
		}
		
		if (!isset($args['account']))
		{
			return 'Ошибка, номер Яндекс.Кошелек не указан!';
		}
		
		ob_start();
		?>
		<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/donate.xml?account=<?php echo rawurlencode($args['account'])?>&quickpay=donate&payment-type-choice=on&mobile-payment-type-choice=on&default-sum=<?php echo rawurlencode($args['sum'])?>&targets=<?php echo rawurlencode($args['targets'])?>&project-name=<?php echo rawurlencode($args['project-name'])?>&project-site=<?php echo rawurlencode($args['project-site'])?>&button-text=01&mail=on&successURL=<?php echo rawurlencode($args['project-site'])?>" width="524" height="93"></iframe>
		
		<?php
			
		return ob_get_clean();
	}

	static public function pdf($args, $body)
	{
		$args += array(
			'width' => '100%',
			'height' => '700px',
		);
		
		ob_start();
		?>
		<iframe src="http://docs.google.com/viewer?url=<?php echo rawurlencode($args['url'])?>&embedded=true" style="width:<?php echo htmlspecialchars($args['width'])?>; height:<?php echo htmlspecialchars($args['height'])?>;" 
		frameborder="0">Ваш браузер не поддерживает фреймы</iframe>
		<?php
			
		return ob_get_clean();
	}

	static public function youtube($args, $body)
	{
		$args += array(
			'width' => '560',
			'height' => '315',
		);
		
		ob_start();
		?>
		<iframe width="<?php echo htmlspecialchars($args['width'])?>" height="<?php echo htmlspecialchars($args['height'])?>" src="<?php echo htmlspecialchars($args['src'])?>" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
		<?php 
		
		return ob_get_clean();
	}

	static public function form($args, $body)
	{
		$args += array(
			'xsl' => 'ОтобразитьФорму',
		);
		
		ob_start();
		
		if (Core::moduleIsActive('form'))
		{
			if (isset($args['id']) && $args['id'])
			{
				$oForm = Core_Entity::factory('Form', $args['id']);
		
				$Form_Controller_Show = new Form_Controller_Show($oForm);
		
				$oXsl = Core_Entity::factory('Xsl')->getByName($args['xsl']);
		
				if ($oXsl)
				{
					isset($args['othername']) && $Form_Controller_Show->addEntity(
						Core::factory('Core_Xml_Entity')
							->name('other_name')->value($args['othername'])
						);
		
					$Form_Controller_Show
						->xsl($oXsl)
						->show();
				}
				else
				{
					?>Ошибка, XSL не найден!<?php
				}
			}
			else
			{
				?>Ошибка, ID формы не указан!<?php
			}
		}
		
		return ob_get_clean();
	}
}