var chkMob = window.innerWidth < 580;

$(function() {

	!chkMob && desctopManipulation();
	chkMob && mobileManipulation();

	var navText = ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"];

	$('#mainSlider').owlCarousel({
		items:1,
		nav:true,
		loop:true,
		navText: navText,
		autoplay:true,
		autoplayTimeout:5000,
		autoplayHoverPause:true,
		lazyLoad: true
	});
	$('#reviewSlider').owlCarousel({
		items:3,
		dots:false,
		autoplay:true,
		autoplayTimeout:3000,
		autoplayHoverPause:true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3,
				margin:20
			}
		}
	});
	$('#licenseSlider').owlCarousel({
		loop:false,
		dots:false,
		lazyLoad:true,
		navText: navText,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:5,
				margin:20,
				nav:true
			}
		}
	});
	$('#specialistSlider').owlCarousel({
		dots:false,
		navText: navText,
		responsive:{
			0:{
				items:1,
				loop:true
			},
			600:{
				items:2,
				margin:30,
				nav:true
			}
		}
	});
	$('#videoSlider').owlCarousel({
		loop:true,
		dots:false,
		video:true,
		lazyLoad:true,
		center:true,
		navText: navText,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3,
				margin:30,
				nav:true,
			}
		}
	});

        if($('.card-header').length) {
           $('.card-header').click(function() {
             $(this).next('.card-body').toggle();
           })
         }

	$.extend({
		showXslTemplate: function(path, formId, xslid, d, docname) {
			$(".modal, .modal-backdrop").remove();

			path += '?getForm=' + formId + '&xsl=' + xslid;

			if(typeof d != 'undefined' && d !='')
			{
				path += '&' + d;
			}
                        if(typeof docname != 'undefined' && docname !='')
			{
				path += '&docname=' + docname;
			}
			$.clientRequest({path: path, 'callBack': $.showXslTemplateCallback, context: ''});

			return false;
		},
		showXslTemplateCallback: function(data, status, jqXHR) {
			$.loadingScreen('hide');
			$("body").append(data.html);
			$(".modal").modal("show");
		},
		sendForm: function(path, jObject, formId, xslid, d) {
			path += '?getForm=' + formId + '&xsl=' + xslid;

			if(typeof d != 'undefined' && d !='')
			{
				path += '&' + d;
			}

			$.clientRequest({path: path, 'callBack': $.sendFormCallback, context: jObject});

			return false;
		},
		sendFormCallback: function(data, status, jqXHR) {
			$.loadingScreen('hide');
			$(this).html(data.html);
		}
	});
});

function desctopManipulation()
{
	$('header a.nav-link.dropdown-toggle').on('click', function(){
		location.href = $(this).attr('href');
	});
}
function mobileManipulation()
{

}