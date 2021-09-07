<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>
	
	<!-- ОтзывыНаГлавнойNEW -->
	
	<xsl:template match="/">
		<xsl:apply-templates select="informationsystem"/>
	</xsl:template>
	
	<xsl:template match="informationsystem">
		
		<xsl:if test="informationsystem_item">
			<div class="reviews-box">
				<div class="h2 text-center mb-4">
					Отзывы клиентов
					<small>
						<a href="{url}">Все отзывы</a>
					</small>
				</div>
				<div class="review-slider mb-4">
					<div id="reviewSlider" class="owl-carousel owl-theme mb-3">
						<xsl:apply-templates select="informationsystem_item"/>
					</div>
					<div class="text-center">
						<button type="submit" class="btn btn-primary" onclick="$.showXslTemplate('{/informationsystem/url}', {/informationsystem/@id}, 321); return false;">Оставить отзыв <img src="/i/ico-review.png" /></button>
					</div>
				</div>
			</div>
		</xsl:if>
	</xsl:template>
	
	<!-- Шаблон вывода информационного элемента -->
	<xsl:template match="informationsystem_item">
		<div class="item">
			<div class="h4"><xsl:value-of select="property_value[tag_name='author']/value"/></div>
			<div class="mb-1">
				<xsl:value-of select="substring-before(date, '.')"/>
				<xsl:variable name="month_year" select="substring-after(date, '.')"/>
				<xsl:variable name="month" select="substring-before($month_year, '.')"/>
				<xsl:choose>
					<xsl:when test="$month = 1"> января </xsl:when>
					<xsl:when test="$month = 2"> февраля </xsl:when>
					<xsl:when test="$month = 3"> марта </xsl:when>
					<xsl:when test="$month = 4"> апреля </xsl:when>
					<xsl:when test="$month = 5"> мая </xsl:when>
					<xsl:when test="$month = 6"> июня </xsl:when>
					<xsl:when test="$month = 7"> июля </xsl:when>
					<xsl:when test="$month = 8"> августа </xsl:when>
					<xsl:when test="$month = 9"> сентября </xsl:when>
					<xsl:when test="$month = 10"> октября </xsl:when>
					<xsl:when test="$month = 11"> ноября </xsl:when>
					<xsl:otherwise> декабря </xsl:otherwise>
				</xsl:choose>
				<xsl:value-of select="substring-after($month_year, '.')"/><xsl:text> г.</xsl:text>
			</div>
			<div class="mb-1"><xsl:value-of select="description" disable-output-escaping="yes" /></div>
			<xsl:if test="description_real/node()">
				<div>
					<a href="https://www.mcolmed.ru{url}">Читать полностью</a>
				</div>
			</xsl:if>
		</div>
	</xsl:template>
</xsl:stylesheet>