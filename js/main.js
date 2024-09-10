// import smtp from 'smtp.js';

(function($) {
	"use strict";

	$(document).on('ready', function() {
	
		jQuery(window).on('scroll', function() {
			if ($(this).scrollTop() > 200) {
				$('#header .header-inner').addClass("sticky");
			} else {
				$('#header .header-inner').removeClass("sticky");
			}
		});
		
		/*====================================
			Sticky Header JS
		======================================*/ 
		jQuery(window).on('scroll', function() {
			if ($(this).scrollTop() > 100) {
				$('.header').addClass("sticky");
			} else {
				$('.header').removeClass("sticky");
			}
		});
		
		/*====================================
			Mobile Menu
		======================================*/ 	
		$('.menu').slicknav({
			prependTo:".mobile-nav",
			duration: 300,
			closeOnClick:true,
			removeIds: false,
		})
		
		/*===============================
			Index Slider JS
		=================================*/ 
		$(".index-slider").owlCarousel({
			loop:true,
			autoplay:true,
			smartSpeed: 500,
			autoplayTimeout:3500,
			singleItem: true,
			autoplayHoverPause:true,
			items:1,
			nav:false,
			navText: ['<div class="d-flex align-items-center justify-content-center"><i class="icofont-duotone icofont-angle-double-left"></i></div>', '<div class="d-flex align-items-center justify-content-center"><i class="icofont-duotone icofont-angle-double-right"></i></div>'],
			dots:false,
		});
		
		/*=====================================
			Counter Up JS
		======================================*/
		$('.counter').counterUp({
			delay:20,
			time:2000
		});
	
	
		
		/*================
			Wow JS
		==================*/		
		var window_width = $(window).width();   
			if(window_width > 767){
			new WOW().init();
		}

		/*=======================
			Animate Scroll JS
		=========================*/
		$('.scroll').on("click", function (e) {
			var anchor = $(this);
				$('html, body').stop().animate({
					scrollTop: $(anchor.attr('href')).offset().top - 100
				}, 1000);
			e.preventDefault();
		});

		/*=======================
			Move scroll to section from index
		=========================*/
		if (window.location.hash != null && window.location.hash != ''){
			console.log(window.location.hash);
			const height = $(window.location.hash).offset().top
			console.log(height);
			window.scrollTo({
				top: height - 100,
				left: 0,
				behaviour: "smooth",
			});
		}
		
	});
	
	/*====================
		Preloader JS
	======================*/
	$(window).on('load', function() {
		console.log($(".goog-te-combo"));

		
		// miniLoader($('.container-mini-loader'),'sm')
		
		var language = localStorage.getItem("language")
		if(!language){
			$(".goog-te-combo").val("es");
			localStorage.setItem("language", "es");
		}
		else{
			$(".goog-te-combo").val(language);
		}
		// var languageSelect = $('.goog-te-combo option:selected').val();
		$('.languageSwitcher').val(language).trigger('change');

		$('.container-languageSwitcher').removeClass('d-none');
		
		// Tiempo de espera para que cargue selector de palabras con trauduccion
		setTimeout(() => {
			$('.preloader').addClass('preloader-deactivate');			
		}, 900);

		$('.languageSwitcher').select2({
			templateResult: formatOption,
			templateSelection: formatOption,
			minimumResultsForSearch: -1, // Opcional: para deshabilitar el buscador si no lo necesitas
			width: '160px'
		});

		var isSyncing = false;
		$('.languageSwitcher').on('change', function() {
			console.log('entro de nuevo')
			if (isSyncing) return;
			isSyncing = true;
			var selectedValue = $(this).val();
			$('.languageSwitcher').not(this).val(selectedValue).trigger('change');
			isSyncing = false;

			localStorage.setItem("language", selectedValue);

			var lang = selectedValue;
			$(".goog-te-combo").val(lang);
			var languageSelect = document.querySelector("select.goog-te-combo");
			languageSelect.value = lang;
			languageSelect.dispatchEvent(new Event("change"));

			// var language = document.getElementsByClassName("languageSwitcher").value;
			// var googleTranslateElement = document.querySelector('select.goog-te-combo');
			
			// if (googleTranslateElement) {
			//   googleTranslateElement.value = language;
			//   googleTranslateElement.dispatchEvent(new Event('change'));
			// }

		  });		

	});
	

	
	/*====================
		Fancybox JS
	======================*/
	Fancybox.bind('[data-fancybox]');


	/*====================
		Tabla contenidos Iniciativa
	======================*/
	$('.link-content').on("click", function (e) {
		const href = $(this).data('href');
		const height = $("#"+href).offset().top
		console.log(height);
		window.scrollTo({
			top: height - 160,
			left: 0,
			behaviour: "smooth",
		});
		$('.contents-table-fixed').addClass("d-none");
		removeHashFromUrl();
	});
	$('body').on('click', function() {
		$('.contents-table-fixed').addClass("d-none");
	});

	jQuery(window).on('scroll', function() {
		if ($(this).scrollTop() > 300) {
			$('.contents-button').addClass("d-block");
			$('.contents-button').removeClass("d-none");
		} else {
			$('.contents-button').addClass("d-none");
			$('.contents-button').removeClass("d-block");
			$('.contents-table-fixed').addClass("d-none");
		}
	});

	$('.contents-button button').on("click", function (e) {
		e.stopPropagation();
		$('.contents-table-fixed').toggleClass("d-none");
	});





	/*********************************************/
	$('.link-component').on("click", function (e) {
		const href = $(this).data('href');
		const url = "initiative.html#"+href;
		window.location.href = url;
	});

	$('.btn-send').click(function(e){
		e.preventDefault();
		EnviarCorreoContato();
	});	
	
})(jQuery);

function removeHashFromUrl() {
	var uri = window.location.toString();

	if (uri.indexOf("#") > 0) {
		var clean_uri = uri.substring(0, 
						uri.indexOf("#"));

		window.history.replaceState({}, 
				document.title, clean_uri);
	}
}

function notificacionCustom(mensaje, tipo, duracion){
    if(tipo == 'success'){
        icono = 'fas fa-check-circle';
        titulo = 'CORRECTO';
    }
    else if(tipo=="info"){
        icono = 'fas fa-info-circle';
        titulo = 'INFORMACIÓN';
    }
    else if(tipo=="warning"){
        icono = 'fas fa-exclamation-triangle';    
        titulo = 'ATENCIÓN';
    }
    else if(tipo=="danger"){
        icono = 'fas fa-exclamation-circle';    
        titulo = 'ERROR';
    }


    $.notify({
        // options
        icon: icono,
        title: titulo,
        message: mensaje,
        // url: response.url,
        // target: response.target
    },{
        // settings
        type: tipo,
        showProgressbar: true,
        placement: {
            from: 'top',
            align: 'right'
        },
        offset:  {
            x: 0,
            y: 15
        },
        spacing: 15,
        z_index: 2000,
        delay: duracion*1000,
        timer: 300,
        mouse_over: 'pause',
        icon_type: 'class',
        template:   '<div class="col-xs-11 col-sm-3 px-3">'+
                        '<div data-notify="container">'+
                            '<div class="progress custom-progress" data-notify="progressbar">'+
                                '<div class="progress-bar progress-bar-striped bg-{0} progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="10" style="width: 0%;"></div>'+
                            '</div>'+
                            '<div class="alert alert-{0} mb-0 custom-notify" role="alert">'+
                                '<button type="button" aria-hidden="true" class="btn btn-md btn-close-notify btn-texto-{0}" data-notify="dismiss"><i class="fas fa-times"></i></button>'+
                                '<div class="d-flex align-items-center">'+
                                    '<div class="notify-icon">'+
                                        '<i data-notify="icon"></i>'+
                                    '</div>'+
                                    '<div class="notify-content pl-3">'+
                                        '<div class="notify-title">'+
                                            '<span data-notify="title">{1}</span>'+
                                        '</div>'+
                                        '<div class="notify-message texto-normal">'+
                                            '<span data-notify="message">{2}</span>'+
                                        '</div>'+
                                        '<div class="notify-url">'+
                                            '<a href="{3}" target="{4}" data-notify="url"></a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'

    });
}

function miniLoader(contenedor, size, contraste=''){
    var div = `<div class='${size}'></div>`;
    contenedor.html(
        `<div class='contenedor-loader ${contraste} text-center d-flex align-items-center justify-content-center'>
            <div class="loadingio-spinner-spinner-55sh9g009cu ${size}">
                <div class="ldio-tmsjd6m58lc">
                    ${Array(12).join(0).split(0).map((item,i)=>div).join('')}
                </div>
            </div>
        </div>`
    );
}

function formatOption(option) {
	if (!option.id) {
		return option.text;
	}

	// Obtener la imagen de la opción seleccionada usando data-img-src
	var imgSrc = $(option.element).data('img-src');
	if (!imgSrc) {
		return option.text;
	}

	// Crear la estructura con imagen y texto
	var $option = $(
		'<span><img src="' + imgSrc + '" class="img-option" style="width: 30px; height: 30px; margin-right: 5px;" /> ' + option.text + '</span>'
	);
	return $option;
}