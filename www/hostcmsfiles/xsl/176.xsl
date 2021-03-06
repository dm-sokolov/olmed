<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<!-- МагазинКаталогТоваровНаГлавнойСпецПред -->

	<xsl:decimal-format name="my" decimal-separator="," grouping-separator=" "/>

	<xsl:template match="/">
		<xsl:apply-templates select="/shop"/>
	</xsl:template>

	<xsl:template match="/shop">
		<!-- Есть товары -->
		<xsl:if test="shop_item">
			<p class="h1 red">Горячие предложения</p>
			<div class="shop_block">
				<div class="shop_table">
					<!-- Выводим товары магазина -->
					<xsl:apply-templates select="shop_item" />
				</div>
			</div>
		</xsl:if>
	</xsl:template>

	<!-- Шаблон для товара -->
	<xsl:template match="shop_item">

		<div class="shop_item">
			<div class="shop_table_item">
				<div class="image_row">
					<div class="image_cell">
						<a href="{url}">
							<xsl:choose>
							<xsl:when test="image_small != ''">
								<img src="{dir}{image_small}" alt="{name}" title="{name}"/>
							</xsl:when>
							<xsl:otherwise>
								<img src="/images/no-image.png" alt="{name}" title="{name}"/>
							</xsl:otherwise>
							</xsl:choose>
						</a>
						<div class="hit">Хит</div>
					</div>
				</div>
				<div class="description_row">
					<div class="description_sell">
						<p>
							<a href="{url}" title="{name}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_item">
								<xsl:value-of select="name"/>
							</a>
						</p>
						<div class="price">
							<xsl:value-of select="format-number(price, '### ##0,00', 'my')"/><xsl:text> </xsl:text><xsl:value-of disable-output-escaping="yes" select="currency"/><xsl:text> </xsl:text>
							<!-- Ссылку на добавление в корзины выводим, если:
							type = 0 - простой тип товара
							type = 1 - электронный товар, при этом остаток на складе больше 0 или -1,
							что означает неограниченное количество -->
							<xsl:if test="type = 0 or (type = 1 and (digitals > 0 or digitals = -1))">
								<a href="{/shop/url}cart/?add={@id}" onclick="return $.addIntoCart('{/shop/url}cart/', {@id}, 1)">
									<img src="/images/add_to_cart.gif" alt="Добавить в корзину" title="Добавить в корзину" />
								</a>
							</xsl:if>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <xsl:if test="position() mod 2 = 0 and position() != last()">
			<div class="clearing"></div>
		</xsl:if> -->
	</xsl:template>
</xsl:stylesheet>