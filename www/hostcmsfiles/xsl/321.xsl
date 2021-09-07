<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml" />
	
	<!-- ФормаОтзывАяксNEW -->
	
	<xsl:template match="/">
		<xsl:apply-templates select="informationsystem" />
	</xsl:template>
	
	<xsl:template match="informationsystem">
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<script type="text/javascript">
				$(function() {
				/*dataLayer.push({'event': 'form<xsl:value-of select="@id" />.openMS'});
				$('#informationsystem<xsl:value-of select="@id" /> .btn-primary').on('click',function(a) {
				dataLayer.push({'event': 'form<xsl:value-of select="@id" />.sendMS'});
				});*/
				$('#informationsystem<xsl:value-of select="@id" />').validate({
				focusInvalid: true,
				errorClass: "input_error",
				submitHandler: function(form) {
				$(".modal, .modal-backdrop").remove();
				var d = $(form).serialize();
				setTimeout(function() {
				$.showXslTemplate('<xsl:value-of select="url" />', <xsl:value-of select="@id" />, 321, d);
				}, 500);
				/*yaCounter20420221.reachGoal('SEND_INFORMATIONSYSTEM_<xsl:value-of select="@id" />');*/
				/*dataLayer.push({'event': 'form<xsl:value-of select="@id" />.successMS'});*/
				}
				});
				});
			</script>
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<form name="informationsystem{@id}" id="informationsystem{@id}" class="validate" method="post">
						<div class="modal-body">
							<div class="h4 text-center">Оставить отзыв</div>
							<xsl:choose>
								<xsl:when test="message/node()">
									<div class="text-center">
										<xsl:value-of disable-output-escaping="yes" select="message"/>
									</div>
								</xsl:when>
								<xsl:otherwise>
									<xsl:choose>
										<xsl:when test="error/node()">
											<div class="text-center">
												<xsl:value-of select="error"/>
											</div>
										</xsl:when>
										<xsl:otherwise>
											<div><xsl:value-of disable-output-escaping="yes" select="description"/></div>
										</xsl:otherwise>
									</xsl:choose>
									<div class="form-row">
										<div class="form-group col-md-6">
											<input type="text" size="70" name="author" class="form-control required" value="{/informationsystem/adding_item/author}" title="Заполните поле Имя" placeholder="Как к Вам обращаться? *" />
										</div>
										<div class="form-group col-md-6">
											<input type="text" size="70" name="email" class="form-control required" value="{/informationsystem/adding_item/email}" title="Заполните поле Email" placeholder="Ваш Email *" />
										</div>
									</div>
									<div class="form-group">
										<textarea name="text" cols="68" rows="5" wrap="off" placeholder="Ваш отзыв *" class="form-control required" title="Введите Ваше сообщение"></textarea>
									</div>
									<div class="form-group">
										<small class="form-text text-muted">
											<span class="text-danger">Поля, отмеченные * обязательны для заполнения.</span><br />
										Нажимая кнопку "Отправить", я подтверждаю, что даю свое согласие на обработку предоставленных мной данных в соответствии с <a href="/personalnie-dannie/">Политикой обработки персональных данных</a>
										</small>
									</div>
								</xsl:otherwise>
							</xsl:choose>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							<xsl:if test="not(message)">
								<button class="btn btn-primary" type="submit" name="submit_question" value="submit_question">Отправить отзыв</button>
							</xsl:if>
						</div>
					</form>
				</div>
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>