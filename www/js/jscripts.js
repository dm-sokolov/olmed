var winWidth=$(window).width();var winHeight=$(window).height();$(function(){$('#gallery a[target="_blank"]:has(img)').fancybox();$('#setCurrent a[target="_blank"]:has(img)').fancybox();$('.modal-button').fancybox({padding:0,margin:10,fitToView:false,width:455,scrolling:'no'});$('.button:has(a)').click(function(){window.location.href=$(this).find('a').attr('href')});$.validator.setDefaults({submitHandler:function(form){var d=$(form).serialize();var div=$(form).parents('[id ^= form_]');$.ajax({type:$(form).attr('method'),url:$(form).attr('action'),data:d,success:function(msg){$(div).html(msg);}});}});$('.slk .s-arrow > span, .slk .close').click(function(){if($(this).parents(".slk").hasClass('s-show')){$(this).parents(".slk").removeClass('s-show');$('.more-city').hide();}else{$(this).parents(".slk").addClass('s-show');$('.more-city').show();}});$('#form1').validate({focusInvalid:true,errorClass:"input_error"});$('#form2').validate({focusInvalid:true,errorClass:"input_error"});$('#form3').validate({focusInvalid:true,errorClass:"input_error"});$('#form5').validate({focusInvalid:true,errorClass:"input_error"});$('#form10').validate({focusInvalid:true,errorClass:"input_error"});$('#modal-overlay, a.close-yt').on('click',function(e){$('#modal-overlay').fadeOut(600);$('#modal-container').fadeOut(600);CloseVideo();});$('#accordion > div').hide();$('#accordion h3.n-title').toggle(function(){$(this).next().slideDown(1000);},function(){$(this).next().slideUp(1000);});$(window).resize(function(){$('#callBox').each(function(){resizeModal(this);});});$.extend({showXslTemplate:function(path,formId,xslid,item,d){var object=$("#callBox"+formId);object.remove();$.clientRequest({path:path+'?getForm=1&xsl='+xslid+'&formId='+formId+'&'+d,'callBack':$.showXslTemplateCallback,context:object});return false;},showXslTemplateCallback:function(data,status,jqXHR){$.loadingScreen('hide');$("body").append(data);resizeModal(this.selector);$(this.selector).show();},showXslTemplateAjax:function(path,getName,xslName,formId,item,d){var object=$("#callBox"+formId),getData='';if(typeof getName!='undefined'&&getName)getData+=getName+'='+formId;if(typeof xslName!='undefined'&&xslName)getData+='&xsl='+xslName;if(typeof item!='undefined'&&item)getData+='&item='+item;if(typeof d!='undefined'&&d)getData+='&'+d;$.clientRequest({path:path+'?'+getData,'callBack':$.showXslTemplateAjaxCallback,context:object});return false;},showXslTemplateAjaxCallback:function(data,status,jqXHR){$.loadingScreen('hide');var jObject=jQuery(this);jObject.html(data);},modalClose2:function(){$('.callBox').fadeOut();return false;}});});function CloseVideo(){$('.show-yt').hide();setTimeout(function(){$('#ytBox').html('');},500);}function LoadVideo(link){$.loadingScreen('show');$.ajax({dataType:"html",url:link,success:function(data){$('#ytBox').load(link+' #youtube');$('#AjaxFrame').css({'left':Math.round((winWidth-$('#AjaxFrame').width()-40)/2),'top':Math.round((winHeight-$('#AjaxFrame').height()-40)/2)});setTimeout(function(){$.loadingScreen('hide');$('.show-yt').show();$('#modal-overlay').show();},500);}});return false;}$(window).load(function(){$('.main-slides').flexslider({animation:"slide",controlNav:false,pauseOnHover:true,prevText:" ",nextText:" ",slideshowSpeed:5000,});});function resizeModal(id){$(id).css({'top':Math.round(($(window).height()-$(id).height())/2),'left':Math.round(($(window).width()-$(id).width())/2)});}