<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>
	
	<!-- ПрессаНаГлавнойNEW -->
	
	<xsl:template match="/">
		<xsl:apply-templates select="informationsystem"/>
	</xsl:template>
	
	<xsl:template match="informationsystem">
		
		<xsl:if test="informationsystem_item">
			
			<div class="h2 text-center mb-4">Пресса о нас</div>
			
			<div class="row justify-content-md-center">
				<div class="col-sm-11">
					<div id="videoSlider" class="owl-carousel owl-theme owl-arrow-black mb-4">
						<xsl:apply-templates select="informationsystem_item"/>
					</div>
				</div>
			</div>
		</xsl:if>
	</xsl:template>
	
	<!-- Шаблон вывода информационного элемента -->
	<xsl:template match="informationsystem_item">
		<div class="item-video">
			<!-- Дата время
			<div class="n-data">
				<xsl:value-of disable-output-escaping="yes" select="date"/>
			</div>
			<a href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="informationsystem_item" class="n-title">
				<xsl:value-of select="name"/>
			</a>-->
			<a class="owl-video" href="{property_value[tag_name='video-link']/value}"></a>
		</div>
	</xsl:template>
</xsl:stylesheet>