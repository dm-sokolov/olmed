<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<!-- Анкетные данные -->
	<xsl:template match="/siteuser">
		<xsl:choose>
			<!-- Пользователь отсутствует или у него не подтверждена регистрация -->
			<xsl:when test="not(login/node())">
				<h1>Пользователь не найден</h1>
				<p>Пользователь незарегистрирован или не подтвердил регистрацию.</p>
			</xsl:when>
			<xsl:otherwise>
				<xsl:choose>
					<xsl:when test="property_value[tag_name = 'avatar']/file != ''">
						<!-- Отображаем картинку-аватарку -->
						<img src="{dir}{property_value[tag_name = 'avatar']/file}" alt="" border="0" class="userAvatar"/>
						<br/>
					</xsl:when>
					<xsl:otherwise>
						<!-- Отображаем картинку, символизирующую пустую аватарку -->
						<img src="/hostcmsfiles/forum/avatar.gif" alt="" border="0" class="userAvatar"/>
						<br/>
					</xsl:otherwise>
				</xsl:choose>

				<h1><xsl:value-of select="login"/></h1>

				<div class="userData">

					<!-- Проверяем, указано ли имя -->
					<xsl:if test="name != ''">
						<dl>
						  <dt>Имя: </dt>
							<dd><xsl:value-of select="name" /></dd>
						</dl>
					</xsl:if>

					<!-- E-mail -->
					<xsl:if test="property_value[tag_name = 'public_email']/value != 0">
						<dl>
						  <dt>E-mail: </dt>
							<dd><a href="mailto:{email}">	<xsl:value-of select="email"/></a></dd>
						</dl>
					</xsl:if>

					<!-- Страна -->
					<xsl:if test="country != ''">
						<dl>
						  <dt>Страна: </dt>
							<dd><xsl:value-of select="country" /></dd>
						</dl>
					</xsl:if>

					<!-- Город -->
					<xsl:if test="city != ''">
						<dl>
						  <dt>Город: </dt>
							<dd><xsl:value-of select="city" /></dd>
						</dl>
					</xsl:if>

					<!-- Компания -->
					<xsl:if test="company != ''">
						<dl>
						  <dt>Компания: </dt>
							<dd><xsl:value-of select="company" /></dd>
						</dl>
					</xsl:if>

					<!-- Телефон -->
					<xsl:if test="phone != ''">
						<dl>
						  <dt>Телефон: </dt>
							<dd><xsl:value-of select="phone" /></dd>
						</dl>
					</xsl:if>

					<!-- Факс -->
					<xsl:if test="fax != ''">
						<dl>
						  <dt>Факс: </dt>
							<dd><xsl:value-of select="fax" /></dd>
						</dl>
					</xsl:if>

					<!-- Сайт -->
					<xsl:if test="website != ''">
						<dl>
						  <dt>Сайт: </dt>
							<dd><noindex><a href="{website}" rel="nofollow" target="_blank">	<xsl:value-of select="website"/></a></noindex></dd>
						</dl>
					</xsl:if>

					<!-- ICQ -->
					<xsl:if test="icq != ''">
						<dl>
						  <dt>ICQ: </dt>
							<dd><img src="http://status.icq.com/online.gif?icq={icq}&#38;img=5" alt="Статус ICQ" title="Статус ICQ" style="position: relative; top: 4px;"/> <xsl:value-of select="icq"/></dd>
						</dl>
					</xsl:if>

					<!-- Зарегистрирован -->
					<xsl:if test="website != ''">
						<dl>
						  <dt>Зарегистрирован: </dt>
							<dd><xsl:value-of select="date"/> г.</dd>
						</dl>
					</xsl:if>
				</div>
				<div class="userData">
					<!-- Друзья -->
					<xsl:apply-templates select="siteuser_relationship_type"/>
				</div>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="siteuser_relationship_type">
		<!-- Вывод просто друзей или в гурппе есть друзья -->
		<xsl:if test="@id = 0 or count(siteuser_relationship)">
			<dl>
			  <dt>
				<!-- Название группы -->
				<xsl:choose>
					<xsl:when test="@id = 0">Друзья:</xsl:when>
					<xsl:otherwise><xsl:value-of select="name" />:</xsl:otherwise>
				</xsl:choose>
			  </dt>
				<dd>
					<xsl:variable name="current_siteuser_id" select="/siteuser/current_siteuser_id" />

					<xsl:if test="@id = 0">
						<xsl:choose>
							<xsl:when test="/siteuser/current_siteuser_relation/siteuser_relationship[siteuser_id = $current_siteuser_id]/node() and /siteuser/current_siteuser_relation/siteuser_relationship[recipient_siteuser_id = $current_siteuser_id]/node()">
								<!-- вы взаимные друзья. -->
								<a title="Убрать из друзей" onclick="$.clientRequest({{path: '?removeFriend', 'callBack': $.friendOperations, context: $(this)}}); return false">Убрать из друзей</a>
							</xsl:when>
							<xsl:when test="/siteuser/current_siteuser_relation/siteuser_relationship[siteuser_id = $current_siteuser_id]/node()">
								<!-- вы подписчик. -->
								<a title="Отписаться" onclick="$.clientRequest({{path: '?removeFriend', 'callBack': $.friendOperations, context: $(this)}}); return false">Отписаться</a>
							</xsl:when>
							<xsl:when test="/siteuser/@id != $current_siteuser_id">
								<a title="Добавить в друзья" onclick="$.clientRequest({{path: '?addFriend', 'callBack': $.friendOperations, context: $(this)}}); return false">Добавить в друзья</a>
							</xsl:when>
							<xsl:otherwise></xsl:otherwise>
						</xsl:choose>
					</xsl:if>

					<!-- Друзья -->
					<div>
					<xsl:for-each select="siteuser_relationship">
						<xsl:value-of select="siteuser/login"/>
						<xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if>
					</xsl:for-each>
					</div>
				</dd>
			</dl>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>