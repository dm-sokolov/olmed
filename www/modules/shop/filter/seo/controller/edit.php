<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Shop_Filter_Seo Backend Editing Controller.
 *
 * @package HostCMS
 * @subpackage Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Filter_Seo_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		if (!$object->id)
		{
			$object->shop_id = Core_Array::getGet('shop_id');
			$object->shop_group_id = Core_Array::getGet('shop_group_id', 0);
		}

		parent::setObject($object);

		$title = $this->_object->id
			? Core::_('Shop_Filter_Seo.edit_form_title', $this->_object->name)
			: Core::_('Shop_Filter_Seo.add_form_title');

		$this->title($title);

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRowButton = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRowConditions = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow6 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow7 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow8 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow9 = Admin_Form_Entity::factory('Div')->class('row'))
			;

		$oMainTab
			->move($this->getField('name')->divAttr(array('class' => 'form-group col-xs-12')), $oMainRow1);

		$oAdditionalTab->delete($this->getField('shop_group_id'));

		// Добавляем группу товаров
		$aResult = $this->shopGroupShow('shop_group_id');
		foreach ($aResult as $resultItem)
		{
			$oMainRow2->add($resultItem);
		}

		// Удаляем производителей
		$oAdditionalTab->delete($this->getField('shop_producer_id'));

		$oShopProducerSelect = Admin_Form_Entity::factory('Select')
			->caption(Core::_('Shop_Filter_Seo.shop_producer_id'))
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-3'))
			->options(Shop_Item_Controller_Edit::fillProducersList($object->shop_id))
			->name('shop_producer_id')
			->value($this->_object->id
				? $this->_object->shop_producer_id
				: 0
			);

		// Добавляем продавцов
		$oMainRow3->add($oShopProducerSelect);

		ob_start();
		?>
		<div class="form-group col-xs-12">
			<a class="btn btn-sky" onclick="$('#conditionsModal').modal('show')"><i class="fa fa-plus"></i> <?php echo Core::_('Shop_Filter_Seo.condition')?></a>
		</div>

		<div class="modal fade" id="conditionsModal" tabindex="-1" role="dialog" aria-labelledby="conditionsModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo Core::_('Shop_Filter_Seo.add_condition')?></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<!-- Empty div`s for property row -->
							<div class="property-list"></div>
							<div class="property-values"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" onclick="$.applySeoFilterConditions()"><?php echo Core::_('Shop_Filter_Seo.add')?></button>
					</div>
				</div>
			</div>
		</div>

		<script>
		$(function() {
			$('#conditionsModal').on('show.bs.modal', function () {
				var shop_group_id = $(':input[name = "shop_group_id"]').val(),
					shop_id = $('input[name = "shop_id"]').val();

				$.ajax({
					url: '/admin/shop/filter/seo/index.php',
					data: { 'load_properties': 1, 'shop_group_id': shop_group_id, 'shop_id': shop_id },
					dataType: 'json',
					type: 'POST',
					success: function(result){
						if (result.html.length)
						{
							$('.property-list').html(result.html);

							// first select
							$('.property-list .property-select').change();
						}

						if (result.count == 0)
						{
							$('#conditionsModal').find('button.btn-success').remove();
						}
					}
				});
			});

			$.loadSeoFilterNestable();
		});
		</script>
		<?php

		$oMainRowButton->add(Admin_Form_Entity::factory('Code')->html(ob_get_clean()));

		$aShop_Filter_Seo_Properties = $this->_object->Shop_Filter_Seo_Properties->findAll(FALSE);

		ob_start();
		?>
		<div class="col-xs-12">
			<div class="well well-sm margin-bottom-10 filter-conditions">
				<p class="semi-bold"><i class="widget-icon fa fa-list icon-separator palegreen"></i><?php echo Core::_('Shop_Filter_Seo.conditions')?></p>

				<?php
				if (count($aShop_Filter_Seo_Properties))
				{
					$aAvailableProperties = array(0, 11, 1, 7, 8, 9);
					Core::moduleIsActive('list') && $aAvailableProperties[] = 3;

					foreach ($aShop_Filter_Seo_Properties as $key => $oShop_Filter_Seo_Property)
					{
						$oProperty = $oShop_Filter_Seo_Property->Property;

						if (in_array($oProperty->type, $aAvailableProperties))
						{
							$onclick = $this->_Admin_Form_Controller->getAdminActionLoadAjax('/admin/shop/filter/seo/index.php', 'deleteCondition', NULL, 0, $this->_object->id, "shop_filter_seo_property_id={$oShop_Filter_Seo_Property->id}");

							?>
							<div class="dd">
								<ol class="dd-list">
									<li class="dd-item bordered-palegreen" data-sorting="<?php echo $key?>" data-id="<?php echo $oShop_Filter_Seo_Property->id?>">
										<div class="dd-handle">
											<div id="<?php echo $oShop_Filter_Seo_Property->id?>" class="form-horizontal">
												<div class="form-group no-margin-bottom">
													<label for="property_value<?php echo $oShop_Filter_Seo_Property->id?>" class="col-xs-12 col-sm-2 control-label text-align-left"><?php echo htmlspecialchars($oProperty->name)?></label>
													<!-- <div class="col-xs-12 col-sm-4 property-data"> -->
														<?php
														switch ($oProperty->type)
														{
															case 3:
																if (Core::moduleIsActive('list'))
																{
																	$aList_Items = $oProperty->List->List_Items->findAll(FALSE);

																	$aValues = array();
																	foreach ($aList_Items as $oList_Item)
																	{
																		$aValues[$oList_Item->id] = $oList_Item->value;
																	}

																	$oAdmin_Form_Entity = Admin_Form_Entity::factory('Select')
																		->options($aValues);

																	$value = $oShop_Filter_Seo_Property->value;
																}
															break;
															case 7:
																$value = $oShop_Filter_Seo_Property->value;

																$oAdmin_Form_Entity = Admin_Form_Entity::factory('Checkbox')
																	->checked(intval($value));
															break;
															case 8: // Date
																$oAdmin_Form_Entity = Admin_Form_Entity::factory('Date');

																$value = $oShop_Filter_Seo_Property->value == '0000-00-00 00:00:00'
																	? ''
																	: Core_Date::sql2date($oShop_Filter_Seo_Property->value);

																$value_to = $oShop_Filter_Seo_Property->value_to == '0000-00-00 00:00:00'
																	? ''
																	: Core_Date::sql2date($oShop_Filter_Seo_Property->value_to);
															break;
															case 9: // Datetime
																$oAdmin_Form_Entity = Admin_Form_Entity::factory('Datetime');

																$value = $oShop_Filter_Seo_Property->value == '0000-00-00 00:00:00'
																	? ''
																	: Core_Date::sql2datetime($oShop_Filter_Seo_Property->value);

																$value_to = $oShop_Filter_Seo_Property->value_to == '0000-00-00 00:00:00'
																	? ''
																	: Core_Date::sql2datetime($oShop_Filter_Seo_Property->value_to);
															break;
															default:
																$oAdmin_Form_Entity = Admin_Form_Entity::factory('Input');

																$value = $oShop_Filter_Seo_Property->value;
																$value_to = $oShop_Filter_Seo_Property->value_to;
														}

														$oAdmin_Form_Entity
															->id('property_value' . $oShop_Filter_Seo_Property->id)
															->name('property_value' . $oShop_Filter_Seo_Property->id)
															->class('form-control')
															->divAttr(array('class' => 'col-xs-12 col-sm-4 property-data'))
															->value($value)
															->controller($this->_Admin_Form_Controller)
															->execute();

														// от-до
														if ($oProperty->Shop_Item_Property->filter == 6)
														{
															$oAdmin_Form_Entity
																->id('property_value_to' . $oShop_Filter_Seo_Property->id)
																->name('property_value_to' . $oShop_Filter_Seo_Property->id)
																->divAttr(array('class' => 'col-xs-12 col-sm-4'))
																->value($value_to)
																->controller($this->_Admin_Form_Controller)
																->execute();
														}
														?>
													<!-- </div> -->
													<a class="delete-associated-item" onclick="<?php echo $onclick?>"><i class="fa fa-times-circle darkorange"></i></a>
												</div>
												<input type="hidden" name="property_value_sorting<?php echo $oShop_Filter_Seo_Property->id?>" value="<?php echo $oShop_Filter_Seo_Property->sorting?>"/>
											</div>
										</div>
									</li>
								</ol>
							</div>
							<?php
						}
					}
				}
				?>
			</div>
		</div>
		<?php
		$oMainRowConditions->add(Admin_Form_Entity::factory('Code')->html(ob_get_clean()));

		$oMainTab
			->move($this->getField('seo_title')->divAttr(array('class' => 'form-group col-xs-12')), $oMainRow4)
			->move($this->getField('seo_description')->divAttr(array('class' => 'form-group col-xs-12')), $oMainRow5)
			->move($this->getField('seo_keywords')->divAttr(array('class' => 'form-group col-xs-12')), $oMainRow6)
			->move($this->getField('h1')->divAttr(array('class' => 'form-group col-xs-12')), $oMainRow7)
			->move($this->getField('text')->wysiwyg(TRUE)->rows(10)->divAttr(array('class' => 'form-group col-xs-12')), $oMainRow8)
			->move($this->getField('active')->divAttr(array('class' => 'form-group col-xs-12 col-sm-3')), $oMainRow9)
			;

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @hostcms-event Shop_Filter_Seo_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		// Существующие условия
		$aShop_Filter_Seo_Properties = $this->_object->Shop_Filter_Seo_Properties->findAll(FALSE);
		foreach ($aShop_Filter_Seo_Properties as $key => $oShop_Filter_Seo_Property)
		{
			$value = strval(Core_Array::getPost('property_value' . $oShop_Filter_Seo_Property->id));
			$value_to = Core_Array::getPost('property_value_to' . $oShop_Filter_Seo_Property->id); // Might be null

			switch ($oShop_Filter_Seo_Property->Property->type)
			{
				case 8:
					$value = $value == ''
						? '0000-00-00 00:00:00'
						: Core_Date::date2sql($value);

					$value_to = $value_to == ''
						? '0000-00-00 00:00:00'
						: Core_Date::date2sql($value_to);
				break;
				case 9:
					$value = $value == ''
						? '0000-00-00 00:00:00'
						: Core_Date::datetime2sql($value);

					$value_to = $value_to == ''
						? '0000-00-00 00:00:00'
						: Core_Date::datetime2sql($value_to);
				break;
			}

			$oShop_Filter_Seo_Property->value = $value;
			$oShop_Filter_Seo_Property->value_to = $value_to;
			$oShop_Filter_Seo_Property->sorting(
				isset($_POST['property_value_sorting' . $oShop_Filter_Seo_Property->id]) ? $_POST['property_value_sorting' . $oShop_Filter_Seo_Property->id] : 0
			);
			$oShop_Filter_Seo_Property->save();
		}

		// Новые условия
		$aAddShopFilterSeoProperties = Core_Array::getPost('property_value', array());
		$aAddShopFilterSeoPropertiesTo = Core_Array::getPost('property_value_to', array());

		foreach ($aAddShopFilterSeoProperties as $property_id => $aValue)
		{
			foreach ($aValue as $key => $value)
			{
				$aValuesTo = isset($aAddShopFilterSeoPropertiesTo[$property_id])
					? $aAddShopFilterSeoPropertiesTo[$property_id]
					: array();

				$value_to = isset($aValuesTo[$key])
					? $aValuesTo[$key]
					: '';

				$oProperty = Core_Entity::factory('Property', $property_id);

				switch ($oProperty->type)
				{
					case 8:
						$value = $value == ''
							? '0000-00-00 00:00:00'
							: Core_Date::date2sql($value);

						$value_to = $value_to == ''
							? '0000-00-00 00:00:00'
							: Core_Date::date2sql($value_to);
					break;
					case 9:
						$value = $value == ''
							? '0000-00-00 00:00:00'
							: Core_Date::datetime2sql($value);

						$value_to = $value_to == ''
							? '0000-00-00 00:00:00'
							: Core_Date::datetime2sql($value_to);
					break;
				}

				$oShop_Filter_Seo_Property = Core_Entity::factory('Shop_Filter_Seo_Property');
				$oShop_Filter_Seo_Property
					->shop_filter_seo_id($this->_object->id)
					->property_id($property_id)
					->value($value)
					->value_to($value_to)
					->sorting(
						isset($_POST['property_value_sorting'][$key]) ? $_POST['property_value_sorting'][$key] : 0
					)
					->save();
			}
		}

		// Index item
		$this->_object->active
			? $this->_object->index()
			: $this->_object->unindex();

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}

	/**
	 * Показ списка групп или поле ввода с autocomplete для большого количества групп
	 * @param string $fieldName имя поля группы
	 * @return array  массив элементов, для доабвления в строку
	 */
	public function shopGroupShow($fieldName)
	{
		$return = array();

		$iCountGroups = $this->_object->Shop->Shop_Groups->getCount();

		$i18n = 'Shop_Filter_Seo';
		$aExclude = array();

		if ($iCountGroups < Core::$mainConfig['switchSelectToAutocomplete'])
		{
			$oShopGroupSelect = Admin_Form_Entity::factory('Select');
			$oShopGroupSelect
				->caption(Core::_($i18n . '.' . $fieldName))
				->options(array(' … ') + Shop_Item_Controller_Edit::fillShopGroup($this->_object->shop_id, 0, $aExclude))
				->name($fieldName)
				->value($this->_object->$fieldName)
				->divAttr(array('class' => 'form-group col-xs-12'))
				->filter(TRUE);

			$return = array($oShopGroupSelect);
		}
		else
		{
			$oShop_Group = Core_Entity::factory('Shop_Group', $this->_object->$fieldName);

			$oShopGroupInput = Admin_Form_Entity::factory('Input')
				->caption(Core::_($i18n . '.' . $fieldName))
				->divAttr(array('class' => 'form-group col-xs-12'))
				->name('shop_group_name');

			$this->_object->$fieldName
				&& $oShopGroupInput->value($oShop_Group->name . ' [' . $oShop_Group->id . ']');

			$oShopGroupInputHidden = Admin_Form_Entity::factory('Input')
				->divAttr(array('class' => 'form-group col-xs-12 hidden'))
				->name($fieldName)
				->value($this->_object->$fieldName)
				->type('hidden');

			$oCore_Html_Entity_Script = Core::factory('Core_Html_Entity_Script')
				->value("
					$('[name = shop_group_name]').autocomplete({
						  source: function(request, response) {

							$.ajax({
							  url: '/admin/shop/item/index.php?autocomplete=1&show_group=1&shop_id={$this->_object->shop_id}',
							  dataType: 'json',
							  data: {
								queryString: request.term
							  },
							  success: function( data ) {
								response( data );
							  }
							});
						  },
						  minLength: 1,
						  create: function() {
							$(this).data('ui-autocomplete')._renderItem = function( ul, item ) {
								return $('<li></li>')
									.data('item.autocomplete', item)
									.append($('<a>').text(item.label))
									.appendTo(ul);
							}

							 $(this).prev('.ui-helper-hidden-accessible').remove();
						  },
						  select: function( event, ui ) {
							$('[name = {$fieldName}]').val(ui.item.id);
						  },
						  open: function() {
							$(this).removeClass('ui-corner-all').addClass('ui-corner-top');
						  },
						  close: function() {
							$(this).removeClass('ui-corner-top').addClass('ui-corner-all');
						  }
					});
				");

			$return = array($oShopGroupInput, $oShopGroupInputHidden, $oCore_Html_Entity_Script);
		}

		return $return;
	}
}