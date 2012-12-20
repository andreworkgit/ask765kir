/*default js*/
$(document).ready(function() {
		
	//Seta o cookie da Lomadee 
	writeUtmCookie();
	
	if ( typeof no_fancybox != "undefined" && no_fancybox == true )
	{
	    // fancybox
	    $("a#inline").fancybox({
	        'titlePosition'     : 'inside',
	        'transitionIn'      : 'fade',
	        'transitionOut'     : 'fade',
	        'width'             : 330 ,
	        'height'            : 450,
	        'scrolling'			: 'no'
	        // 'onComplete'     : function () {  $('iframe#fancybox-frame').iframeAutoHeight({minHeight: 400}); }
	    });

	    $("#send_link").fancybox({
	        'titlePosition'     : 'inside',
	        'transitionIn'      : 'fade',
	        'transitionOut'     : 'fade',
	        'width'             : 450 ,
	        'height'            : 500
	        // 'onComplete'     : function () {  $('iframe#fancybox-frame').iframeAutoHeight({minHeight: 400}); }
	    });	    
	    
	    $("button#regulation_promotion_link").fancybox({
	        'titlePosition'     : 'inside',
	        'transitionIn'      : 'fade',
	        'transitionOut'     : 'fade',
	        'width'             : 700 ,
	        'height'            : 350
	    });	
	    
	    
	    $(".location_maps").fancybox({
	        'titlePosition'     : 'inside',
	        'transitionIn'      : 'fade',
	        'transitionOut'     : 'fade',
	        'width'             : 650 ,
	        'height'            : 450 
	        // 'onComplete'     : function () {  $('iframe#fancybox-frame').iframeAutoHeight({minHeight: 400}); }
	    });
	}
	//Máscara para o campo telefone
	$("#your_phone").mask('(99) 9999-9999');
	
	// função para inscrição no recebimento de newsletter
	$("#btn_submit_register_newsletter").click(function(){

        showLoading();

        var site = $("#site_permalink").val();
        
		$.ajax( {
	        type : "POST",
	        dataType : "json",
	        data : {
	        	email: $("#email_newsletter").val()
	        },
	        url : "/" + site + "/ajax/add-email-newsletter/",
	        cache : false,
	        success : function(result) {
	        	if ( result.status ) {
	        		$("#email_register").html( translateJs.layout_default_newsletter_sign_up );
	        	} else {
                    $("#submit_email_newsletter_error").attr("style","display:block");
	        		$("#submit_email_newsletter_error").html( result.error_message );
	        	}
	            hideLoading();        	
	        },
	        error : function(XMLHttpRequest, textStatus, errorThrown) {
	            alert( translateJs.layout_default_ajax_error_register_newsletter );
	            hideLoading();          
	        }
	    });	
	});
	
	$("#email_newsletter").click(function() {
		
		var field_value = $("#email_newsletter").val();

        if(field_value == translateJs.layout_default_type_your_email)
		{
			$("#email_newsletter").val("");
		}
	});
	
	$("#email_newsletter").blur(function() {
		
		var field_value = $("#email_newsletter").val();
		
		if(field_value == "")
		{
			$("#email_newsletter").val(translateJs.layout_default_type_your_emai);
		}
	});
	
	$('#slider').nivoSlider({
		effect:'fade'
	});
		

    $(".faq_title").click ( function ( ) {
//      $(".faq_desc").hide();
        $(this).parent().children(':last-child').toggle();
    });
    
    // verifica se usuario esta logado e toma as devidas decisoes
    if ( ! empty ( $.cookie('user_login_name') ) ) {
        $('#logged_name').html( $.cookie('user_login_name') );
        showLogged();
    } else {
        hideLoginForm ( );
    }
    
    // 
    if (  typeof vCityId != 'undefined' && $.cookie ( 'city_id') != vCityId ) {

    	//$.cookie ( 'city_id' , vCityId , { expires:365 , path:'/'+ site } );
    }
    
    //Alimenta os combos das opções de compra
    $(".select_variation").change(function(){

    	var current_index 	= $(this).attr('id');
    	var ids 			= [];
    	var count_selects	= $(".select_variation").length;
    	var check_stock		= '';
    	var check_image		= '';
    	
    	$.each($(".select_variation").find("option:selected"), function(key, item) {
    	    
    		if( !empty( $(this).val() ) ) {
    			
    			ids.push( $(this).val() );
    			
    		}
    	    
    	});
    	
    	//Se for o penúltimo elemento confere o stock do próximo se for o ultimo seta a imagem
    	if( ids.length == ( count_selects - 1) ) {
    		
    		check_stock = true;
    		
    	} else if( ids.length == count_selects ) {
    		
    		check_image = true;
    		
    	}
    	
    	getChoiceValues(current_index,ids,check_stock,check_image);
    });
    
   	//slideTo function for nivo-slider
    $.slideTo = function(idx) {

    		$('#slider').data('nivo:vars').currentSlide = idx - 1;
            $("#slider a.nivo-nextNav").trigger('click'); 	
            
    }
    
   //Disable a field
   $.disabled = function( field ) {
     
	   field.css('text-decoration', 'line-through');
       field.attr('disabled', true);
    }
   
   //Enable a field
   $.enabled = function( field ) {
     
	   field.css('text-decoration', 'none');
       field.attr('disabled', false);
    }
    
});

function getChoiceValues(current_index,ids,check_stock,check_image) {
	
	$.ajax( {
        type : 'POST',
        dataType : 'json',
        data: { 
        		deal_id : $("#deal_id").val(), 
        		deal_value_id_selected : ids, 
        		check_stock : check_stock,
        		check_image : check_image
        	  },
        url : '/ajax/get-choice-values/',
        cache : false,
        beforeSend: showLoading,
        success : function(result) {
        	
        	var selects = result.selects;
        	var images  = result.images;
        	
        	//Seta a imagem da deal_stock
        	if( images ) {
        		
        		var deal_image_id = $("#deal_image_" + images).index();
        			
        		$.slideTo( deal_image_id );
        		
        		$('#slider').data('nivoslider').stop();
        	}
        	
        	//Controla os selects das combinações
        	if( selects.length != 0 ) {
        		
        		$.each( selects, function( key, item ) {
            		
            		var value_selected =  $(".select_variation#" + key + " option:selected ").val();
            		var index_one_field = $(".select_variation option[value="+ids[0]+"]").parent().attr('id');
            		
            		if( key != current_index || ( ids.length == 1 && key != index_one_field  ) ) {
            		
            			$.disabled( $(".select_variation#" + key + " option") );
            			                			
            		}
            		
            		if ( ids.length == 1 && key == index_one_field ) {
            		
            			$.enabled( $(".select_variation#" + key + " option") );
            		                			
            		}
            		
            		$.enabled( $(".select_variation#" + key + " option[value='']") );
            		
            		$.each( item, function( key2, item2 ) {
            			
            			$.enabled( $(".select_variation#" + key + " option[value=" + item2 + "]") );
            		                				 
            		});
            		
            	});
        		
        	} else if( ids.length != 0 ) {
        		
        		$.disabled( $(".select_variation option") );
        		
        		$.enabled( $(".select_variation option[value='']") );
        		
        		$.enabled( $(".select_variation#" + current_index + " option") );
        		
        	} else {
        		
        		$.enabled( $(".select_variation option") );
        		
        	}
        	
        	hideLoading();        	
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
        	
            alert( translateJs.view_default_ajax_unknown_error );
            hideLoading();
            
        }
    });
}

/**
 * Função que escreve um cookie com todos os utm's da url
 */
function writeUtmCookie( ) { 
        
        var _url = document.URL;
        var _content = '';
        var patterns = ["utm_source","utm_medium","utm_campaign"];
        var tmp;
        var utm_pattern = /utm_/i;
        
        //Caso encontre na URL algum tipo de utm_
        if( _url.match( utm_pattern)  ) {
                
                for( var i=0; i < patterns.length; i++ ){ 
                        
                        //Verifica o padrão
                        tmp = getUrlVars()[patterns[i]];
                        
                        if( typeof tmp != 'undefined' ) {
                                
                                _content += patterns[i] + '=' + tmp + "|";
                                
                        }
                        
                }
                
                //Remove o último caractere
                _content = _content.substring(0, _content.length -1 );
                
                //Escreve o cookie
                document.cookie =  "utm_purchase=" + _content + ";path=/;";
                
                //Escreve o cookie da lomadee caso exista
                var arr_cookie = getUrlVars();
                
                if( arr_cookie['utm_source'] == 'lomadee' && !$.cookie('lomadee') ) {
                	
                	$.cookie('lomadee', 1, { expires : 30, path: '/' });
            			
            	} else if( arr_cookie['utm_source'] != 'lomadee' ) {
            		
            		$.cookie( 'lomadee', null, { path: '/' });
            		
            	}
                
        }
        
}

/**
 * Função que busca por um determinado padrão contido na url
 * @returns
 */
function getUrlVars() {
    
	var vars = {};
    
	window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    
	return vars;
}

// Grava no cookie a cidade selecionada
function changeCity( city_id ) {
    if ( typeof $  == 'function' && typeof $.cookie == 'function' ) {
        
        var site = $("#site_permalink").val();
        $.cookie ( 'city_id' , city_id , { expires:365 , path:'/'+ site } );
        
    }
    
}

// Limpa os campo de frete
function clearShipment(){

	$("input[name^='shipment']").each(function() {
		this.checked = false;
	});
	$("#ship_value").html('...');
	$("input[id='ship']").val('');
	$("input[id^='shipDetail_']").val('');
	$('.ship_display_none').hide();
	$("div[id^='ship_value_']").html('');
	
	$("#value_full").html($("#product_value").html());
	
	$("div[id^='divShipDetail_']").attr('class','type-Freight unchecked-Freight');
	
	$("#zipcode").val('');

}


function changeUserState() {
    showLoading();
    $.ajax({
        async: false,
        url: '/ajax/get-cities-by-state',
        type: 'POST',
        data: 'user_state='+ $('#state').val() ,                
        dataType:'json',
        success: function(data)
        {
            //Popula o select box
            if ( !empty(data.select_user_city) ) {
                if(empty(selected_index)) {
                    selected_index = 0;
                }

                createSelectOption( data.select_user_city, "city" , data.selected_index, translateJs.layout_default_select );
            }   
            hideLoading();
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert( translateJs.layout_default_ajax_unknown_error );
            hideLoading();
        }
    });         
}


/**
 * Verifica se o CPF informado é válido
 */
function validateEmail ( ) {
    if( !empty($('#email_register').val()) ) {   
        showLoading();

        $.ajax({
            async: false,
            url: '/ajax/validate-email',
            type: 'POST',
            data: 'email='+ $('#email_register').val() ,                
            dataType:'json',
            success: function(data)
            {
                if(!data.status) {
                    $("#alert_message").html(data.error_message);
                    $("#alert_message").animate({ height: 'show'}, 300); 

                    $('#email_register').focus();

                    hideLoading();
                }else{
                    $("#alert_message").hide();             
                    $("#alert_message").html("");

                    //Verifica se o e-mail existe no webservice
                    verifyEmailWs ( );
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert( translateJs.layout_default_ajax_unknown_error );
                hideLoading();
            }
        });
    }
}

/**
 * Verifica se o E-mail informado está cadastrado no WebService
 */
function verifyEmailWs ( ) {
    if( !empty($('#email_register').val()) ) {   
        showLoading();
        
        $.ajax({
            async: false,
            url: '/ajax/verify-email-ws',
            type: 'POST',
            data: 'email='+ $('#email_register').val() ,                
            dataType:'json',
            success: function(data)
            {
                if(!data.status) {
                    $("#alert_message").html(data.error_message);
                    $("#alert_message").animate({ height: 'show'}, 300); 
                    $('#email_register').val("");
                    $('#email_register').focus();
                }else{
                    $("#alert_message").hide();             
                    $("#alert_message").html("");
                }
                hideLoading();
                
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert( translateJs.layout_default_ajax_unknown_error );
                hideLoading();
            }
        }); 
    }
}

/**
 * Verifica se o CPF informado é válido
 */
function cpfValidate ( ) {
    var cpf = $('#document').val().replace(/[A-Za-z$-._-]/g, "");
    if( !empty(cpf) ) {
        showLoading();
        
        $.ajax({
            async: false,
            url: '/ajax/validate-cpf',
            type: 'POST',
            data: 'cpf='+ $('#document').val() ,                
            dataType:'json',
            success: function(data)
            {
                if(!data.status) {
                    $("#alert_message").html(data.error_message);
                    $("#alert_message").animate({ height: 'show'}, 300); 
                    $('#email_register').val("");
                    $('#email_register').focus();

                    hideLoading();
                }else{

                    $("#alert_message").hide();             
                    $("#alert_message").html("");

                    //chama a validação de CPF no Webservice
                    verifyCpfWs ( );
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert( translateJs.layout_default_ajax_unknown_error );
                hideLoading();
            }
        });
    }
}

/**
 * Verifica se o CPF informado está cadastrado no WebService
 */
function verifyCpfWs ( ) {
    //Remove 
    var cpf = $('#document').val().replace(/[A-Za-z$-._-]/g, "");
    if( !empty(cpf) ) {
        showLoading();
        
        $.ajax({
            async: false,
            url: '/ajax/verify-cpf-ws',
            type: 'POST',
            data: 'cpf='+ $('#document').val() ,                
            dataType:'json',
            success: function(data)
            {
                if(!data.status) {
                	               	                	                	
                    $("#alert_message").html(data.error_message);
                    $("#alert_message").animate({ height: 'show'}, 300);
                    
                    //$('#document').focus();
                }else{
                    $("#alert_message").hide();             
                    $("#alert_message").html("");
                }
                hideLoading();
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert( translateJs.layout_default_ajax_unknown_error );
                hideLoading();
            }
        });     
    }
}
/**
 * Preenche o combo de cidades de acordo com o estado selecionado
 */
/*function fillCity( selected_city ) {
	
	var state_id = $("#state").val();
	var selected_city = selected_city;
	
	//$("#zip").mask("99999-999");

    
	if(state_id > 0) {

        showLoading();

        $.ajax( {
	        type : 'POST',
	        dataType : 'json',
	        data : {
	        	user_state: $("#state").val(),
	        	key: 'cidade_pk'
	        },
	        url : '/ajax/get-cities-by-state/',
	        cache : false,
	        success : function(result) {
	        	if (result.status) {
	        		createSelectOption ( result.select_user_city , 'city_id', selected_city, 'Selecione' );
	        	} else {
	        		FlashMessage.onError( result.error );
	        	}
                hideLoading();          
	        },
	        error : function(XMLHttpRequest, textStatus, errorThrown) {
	            alert( translateJs.layout_default_ajax_unknown_error );
                hideLoading();          
	        }
		});
        
	}

}*/

function hideLoginForm() {
    $('#SVM_loginform').hide();
    $('#SVM_dologin').show();
}

function showLogged() {
    $('#SVM_loginform').hide();
    $('#SVM_dologin').hide();
    $('#SVM_logged').show();
}

function show_tooltip () {
	$('.tooltip_ms1g').animate({
    opacity: 'toggle'
  }, 200 );
}

function doLogin(){
	$('.login_form').toggle();
	$('.search_container').toggle();
	$('.login_options').toggle();
}

function loginNavBar() {
	$('.login_container').animate({
    height: 'toggle'
  }, 200 );
}
function selectOtherCity() {
	$('.select_other_city_box').animate({
    height: 'toggle'
  }, 200 );
}

/**
 * Verifica o estoque antes de fazer a compra 
 */
function checkStockBeforePurchase( url ) {
    
    var options = Array();
    var check = false;
    
    $(".select_variation").each( function() {

        if ( empty( this.value ) ) {

            var title = $("#title_variation_" + this.id).html();
            alert( "'" + title + "' " + translateJs.layout_default_ajax_select_choice );
            check = false;
            return false;
            
        } else {

            options.push( { deal_variable_value_id: this.value} );
            check = true;
        }
        
    } )  ;

    if (check) {
        var site = $("#site_permalink").val();
        var deal_id = $("#deal_id").val();

        $.ajax( {
            type : "POST",
            dataType : "json",
            data : {
                options: options,
                deal_id: deal_id
            },
            url : "/" + site + "/ajax/check-stock-before-purchase/",
            cache : false,
            success : function( deal_stock_id ) {
                if ( deal_stock_id > 0  ) {

                    if ( !empty( url ) ) {
                        window.location.href = url + "/deal_stock_id/" + deal_stock_id;
                    }
                    
                } else {
                    
                    alert( translateJs.layout_default_ajax_stock_missing );
                    
                }
                
                hideLoading();          
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert( translateJs.layout_default_ajax_error_register_newsletter );
                hideLoading();          
            }
        }); 
        
    // garante que ira para a tela de compra caso a oferta nao tenha escolha de produto
    } else if ( $(".select_variation").length <= 0) {
        window.location.href = url;
    }
    
    
    
    return check;
}

function clearOptionsDetail() {

    $(".select_variation").each( function() {

        this.value = "";
        
    } )  ;
    
}

/* Código de Vídeo do Youtube */
function viewFoto()
		{ 
			document.getElementById("box-View-Foto").style.display = "block";
			document.getElementById("box-View-Video").style.display = "none";
			
			document.getElementById("icon-Foto-on").style.display = "inline";
			document.getElementById("icon-Foto-off").style.display = "none";
			document.getElementById("icon-Video-on").style.display = "none";
			document.getElementById("icon-Video-off").style.display = "inline";	
		}

	function viewVideo()
		{ 
			document.getElementById("box-View-Foto").style.display = "none";
			document.getElementById("box-View-Video").style.display = "block";

			document.getElementById("icon-Foto-on").style.display = "none";
			document.getElementById("icon-Foto-off").style.display = "inline";
			document.getElementById("icon-Video-on").style.display = "inline";
			document.getElementById("icon-Video-off").style.display = "none";
		}
/* Código de Vídeo do Youtube */		
