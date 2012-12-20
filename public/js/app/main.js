/**
 * Empty do PHP
 */
function empty( obj ) {
    
    if (typeof obj == 'undefined' || obj === null || obj === '' || obj == 'null') return true;
    if (typeof obj == 'number' && isNaN(obj)) return true;
    if (obj instanceof Date && isNaN(Number(obj))) return true;
    
    return false;

}

/** 
 * Redirect generico
 * 
 * @param module
 * @param controller
 * @param action
 * @param parameters
 */ 
function redirect( module, controller, action, parameters ) {
    var url ="";

    if ( !empty( module ) ) {
        url += "/" + module ;
    }
    
    url += "/" + controller + "/" + parameters;

    location.href = url;
}

/**
 * Set city in cookie
 */
function setCityDefaultCookie ( city_id, city_permalink, site_permalink ) {

    if ( typeof $  == 'function' && typeof $.cookie == 'function' ) {
        
        $.cookie (  'city_permalink' , city_permalink , { expires:86400*30 ,path:'/'+site_permalink } ) ;
        $.cookie (  'city_id' , city_id , { expires:86400*30 ,path:'/'+site_permalink } ) ;

    }

}

/**
 * Preenche o combo de cidades de acordo com o estado selecionado
 * @param int selectStateId Id do select de estados
 * @param int selectCityId Id do select de cidades
 * @param String keyIndexSelect Valor selecionado por padrão
 * @param String textDefault Texto do primeiro índice do select de cidades
 */
function fillCity( selectStateId, selectCityId, keyIndexSelect, textDefault ) {
	
	var state_id = $("#" + selectStateId ).val();
    
	if(state_id > 0) {

        showLoading();

        $.ajax( {
	        type : 'POST',
	        dataType : 'json',
	        data : {
	        	user_state: $("#" + selectStateId ).val(),
	        	key: 'cidade_pk'
	        },
	        url : '/ajax/get-cities-by-state/',
	        cache : false,
	        success : function(result) {
	        	if (result.status) {
	        		createSelectOption ( result.select_user_city , selectCityId, keyIndexSelect , textDefault );
	        	} else {
	        		FlashMessage.onError( result.error );
	        	}
                hideLoading();          
	        },
	        error : function(XMLHttpRequest, textStatus, errorThrown) {
	            alert( translateJs.view_default_ajax_unknown_error );
                hideLoading();          
	        }
		});        
	}
}

function createSelectOption( arrValues, selectID , keyIndexSelect, textDefault)
{
    var options = '';
    var selected = '';
    
    if ( !empty(textDefault) ) {
        options += '<option value="" selected="selected">' + textDefault + '</option>';
    }
    
    for (var i in arrValues) {
        selected = "";
        if ( !empty(keyIndexSelect) && keyIndexSelect == i) selected = "selected='selected'";
        
        options += '<option value="' + i + '"' + selected + '>' + arrValues[i] + '</option>';
    }
    
    $('#'+selectID).html(options);
} 

function createSelectOptionWithSelector( arrValues, keyIndexSelect, textDefault)
{
    var selected = '';
    
    options = '<select>';
    if ( !empty(textDefault) ) {
        options += '<option value="" selected="selected">' + textDefault + '</option>';
    }
    
    for (var i in arrValues) {
        selected = "";
        if ( !empty(keyIndexSelect) && keyIndexSelect == i) selected = "selected='selected'";
        
        options += '<option value="' + i + '"' + selected + '>' + arrValues[i] + '</option>';
    }
    options += '</select>';
    
    return options;
} 



//Show PopUp
function ShowPopup(page,name,options)
{
	Window = window.open(page,name,options);
}

function callbackGeneral ( json ) {
	callbackGeneral ( json , false )
}

function callbackGeneral ( json , flash_message ) {
    
    var status         			= json.status;
    var error_message  			= unescape(json.error_message);
    var alert_message  			= unescape(json.alert_message);
    var final_price    			= json.final_price;
    var final_price_not_formated= json.value_full_not_formated;
    var ship           			= json.ship;
    var value_full	   			= json.value_full;
    var zipcode        			= json.zipcode;
    var product_value  			= json.product_value;
    var action         			= json.action;
    var establishment  			= json.establishment;
    var googleMap	   			= json.googleMap;
    var dealCancel 	   			= json.dealCancel;
    var trackingCode   			= json.trackingCode;
    var sendEmailMinimumSale	= json.sendEmailMinimumSale;
    
    var create_field			= json.create_field;
    var themes					= json.themes;
    
    var address					= json.address;
    
    var guinetReportSales		= json.guinetReportSales;
    
    if ( status == true ) {
        
    	// @TODO: REMOVER ISSO ALGUM DIA E USAR A GENERICA: Carrega combo da lojas
    	if ( !empty(establishment) ) {
    		createSelectOption( establishment, 'establishment_id', '', translateJs.main_admin_select_establishment );    		
        }
    	
        // Carrega a imagem do googleMap
    	if( !empty(googleMap) ){
    		$("#imgGoogleMap").attr('src', googleMap);
    	}
    	
        if ( !empty(final_price) ) {
            $("#final_price").val(final_price);
        }

        if ( !empty(ship) ) {
            $("#ship_value").html(ship);
            $("#ship").val(ship);
        }

        if ( !empty(product_value) ) {
            $("#product_value").html(product_value);
        }

        if ( !empty(zipcode) ) {
        	
        	$("#zipcode").val(zipcode);
        	
        	// Atualiza o frete
            calculateShipment();
        }

        if ( !empty(value_full) ) {
            $("#value_full").html(value_full);
        }
        
        if( !empty(final_price_not_formated) ) {
        
        	populateSelectParcels( final_price_not_formated );
        }
        
        if ( !empty(alert_message) ) {
            alert(alert_message);
        }
        
        if ( !empty(dealCancel) ) {
        	alert(translateJs.main_admin_deal_canceled);
        	parent.$.fancybox.close();
        }
        
        if ( !empty(trackingCode) ) {
        	alert(translateJs.main_admin_tracking_code);
        	parent.$.fancybox.close();
        }
        
        if ( !empty(sendEmailMinimumSale) ) {
        	hideLoading();
        	alert(translateJs.main_admin_sendEmailMinimumSale);
        }
        
        if ( !empty(create_field) ) {
        	$(create_field).insertAfter($("fieldset:last"));
        }
        
        if ( !empty(themes) ) {
        	$(themes).insertAfter($(".decorator_terms:last"));
        	
        	$("#radio_theme-"+json.theme_id).attr('checked', true);
        }
        
        if( !empty(guinetReportSales) ) {
            
        	var arrCities	= json.arrCities;
        	var arrDeals	= json.arrDeals;
        	
        	$("#filter_state_id").parent().hide();
        	
        	$('#filter_city_id option').remove();
        	
        	var options = $('#filter_city_id').attr('options');
        	options[options.length] = new Option(translateJs.guinet_report_sales_selected_state, '', true, true);
        	        	        				
        	$.each(arrCities, function(key, value) { 
        		
        		var options = $('#filter_city_id').attr('options');
            	options[options.length] = new Option(value, key, true, true);
        	        		 
        	});
        	
        	if( !empty(arrDeals) )
        	{	
        		$('#filter_deal_id option').remove();
        		
        		var options = $('#filter_deal_id').attr('options');
            	options[options.length] = new Option(translateJs.guinet_report_sales_selected_state, '', true, true);
        		
	        	$.each(arrDeals, function(key, value) { 
	        		
	        		var options = $('#filter_deal_id').attr('options');
	            	options[options.length] = new Option(translateJs.guinet_report_sales_selected_state, '', true, true);
	        		 
	        	});
        	}
        	
        }
        
        if( !empty( address ) ) {
        	jQuery.each( address, function( key, value) {
        		
        		if ( key != 'address_id' ) {
        			
        			$("#" + key ).val( value );
        		}
        		
        	});
        	
        	$("#state option[value='"+ address.state_id +"']").attr('selected', 'selected');
        	       	  
        	fillCity( 'state', 'city', address.city_id , translateJs.main_selected );
        	
        }
        
    } else {
        
        if ( !empty(error_message) ) {
        	
        	if ( flash_message == true) {
        		FlashMessage.onError( error_message );
        	} else {        		
        		alert(error_message);
        	}
        }
        hideLoading();
    }
    
    //Ações que serão chamadas, dependendo do retorno
    if ( !empty ( action ) ) {
        switch(action){
            case 'user_no_log': 
            	location.reload(true);
            	break;
            case 'fill_gift_name':
            	setFormFieldError("gift_name");
            	break;
            case 'fill_gift_email':
            	setFormFieldError("gift_email");
            	break;            	
            case 'fill_gift_message':
            	setFormFieldError("gift_message");
            	break;                 
            case 'fill_select_deal_home':
                fillSelectDealHome( json.fillSelect );
                break;
            case 'fill_cities':
                var fieldCity   = json.fieldCity;
                var arrCities   = json.arrCities;
                
                createOptionReport( fieldCity, arrCities );
                break;
                
            case 'fill_payment_card_holder':
            	setFormPaymentFieldError("card_holder");
                break;
            case 'fill_payment_card_cpf':
            	setFormPaymentFieldError("card_cpf");
                break;
            case 'fill_payment_card_number':
            	setFormPaymentFieldError("card_number");
                break;
            case 'fill_payment_month':
            	setFormPaymentFieldError("month");
                break;
            case 'fill_payment_year':
            	setFormPaymentFieldError("year");
                break;
            case 'fill_payment_card_ccv':
            	setFormPaymentFieldError("card_ccv");
                break;
            case 'fill_payment_parcels':
            	setFormPaymentFieldError("parcels");
                break;    
            case 'show_address':
                $("#address" ).val( address );
                break;    
                
            default :
            break;
        }
    }
}

/**
 * Altera a cor do campo do formulário com dados inválidos
 * @param id  Id do form element
 */
function setFormFieldError(id){
	$("#div_" + id).addClass("div_gift_error");
	openAndClosedDivGift();
	$("#" + id).focus();	
}

function setFormPaymentFieldError(id){
	$("#form_payment_" + id).removeClass("form-Payment");
	$("#form_payment_" + id).addClass("form-Payment-Error");
	$("#form_credit_card-" + id).focus();	
}

function openAndClosedDivGift(){
	$('.div_gift').slideToggle();
}

function populateSelectParcels( total_value ) {
    
	$site = $('#site_permalink').val();
	
    $.ajax( {
        type : 'POST',
        dataType : 'json',
        data : { total_value: total_value },
        url : '/' + $site + '/ajax/get-parcels/',
        cache : false,
        success : function(result) {
        	
        	var options = '';
        	
        	for( x in result.parcel_range) {
        		parcel_number = (parseInt(x) + 1);
        		
        		if( parcel_number == 1 ) {
        			text = "A vista";
        		} else {        		
        			text = parcel_number + " x de R$ " + result.parcel_value[x];
        		}
        		
        		options+= "<option value='" + parcel_number + "'>" + text + "</option>";
        	}
        	
        	$("#form_credit_card-parcels").html(options);
        }        
    });
    
}

//funcao generica da chamada via ajax
function callAjax (url, data) {
    
    //showLoading();
    
    $.ajax( {
        type : 'POST',
        dataType : 'json',
        data : data,
        url : url,
        cache : false,
        success : function(result) {
            // chamada da função de callback
            callbackGeneral(result);
            //hideLoading();          
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            //alert( translateJs.view_default_ajax_unknown_error );
            hideLoading();          
        }
    });
    
}

function showLoading() { 
	
	$.blockUI({	message:  '<span class="loading_span"></span>'  });
    
}

function hideLoading() { 
	$.unblockUI(); 
}

function encerrada(id)
{
	$("#image_over_"+id).show();
	$("#deal_buy_"+id).hide();
	$("#deal_buy_expired_"+id).show();
	$("#deal_image_"+id).attr('class', 'deal_min no');
	$("#deal_status_"+id).html(translateJs.view_deal_no_active);
	$("#deal_time_"+id).hide();
	// index
	$("#time_expired_"+id).show();
	$("#tempo-ofertadodia"+id).hide();
    $("#div_buy_present_"+id).hide();         

}


function createSelectOptionGeneral( fillSelect, select_id )
{
    
    var options = '';
    var selected = '';
    
    if ( !empty(fillSelect.textDefault) ) {
        options += '<option value="" selected="selected">' + fillSelect.textDefault + '</option>';
    }
    
    for (var i in fillSelect.data) {
        selected = "";
        if ( !empty(fillSelect.keyIndexSelect) && fillSelect.keyIndexSelect == i) selected = "selected='selected'";

        options += '<option value="' + fillSelect.data[i].id + '"' + selected + '>' + fillSelect.data[i].value + '</option>';
    }
    
    
    $('#'+select_id).html(options);
    
} 

function slugifyPermalinkGeneric( siteNameId ) {
	var str='';
	str = string_to_slug( $('#' + siteNameId).val() );
	
	$('#permalink').val( $('#base_url').val() + '/' + str );
}

function string_to_slug( str ) {
	str = str.replace(/^\s+|\s+$/g, ''); // trim
	str = str.toLowerCase();
  
	// remove accents, swap ñ for n, etc
	var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
	var to   = "aaaaeeeeiiiioooouuuunc------";
	for (var i=0, l=from.length ; i<l ; i++) {
		str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
	}

	str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
	.replace(/\s+/g, '-') // collapse whitespace and replace by -
	.replace(/-+/g, '-'); // collapse dashes

	return str;
}

/**
 * Verifica se a url informada está disponível no sistema
 * @param string siteNameId id do campo que possui o nome do site
 * @param int siteId
 * @param string moduleName
 * @param string classReturn classe onde será adicionada as mensagens de retorno, caso informada
 */
function checkValidPermalinkGeneric( siteNameId, siteId, moduleName, classReturn){
	
	if( $('#' + siteNameId ).val() != '' &&  ( siteId == 0 || moduleName == 'guinet' ) ) {
	
		FlashMessage.onRemove();
		$.ajax( {
			type : 'POST',
		    dataType : 'json',
		    data :  {
		    	site_permalink: $("#permalink").val(),
		    	site_id: siteId
		    },
		    url : '/admin/ajax/is-site-permalink-registered',
		    cache : false,
		    success : function( result ) {
		    	if ( result.status ) { // processou corretamente
		    		
		    		$('#ul_message').remove();
		    		
		        	if ( result.permalink_availability ) { // disponível
		        		FlashMessage.onSuccess( translateJs.admin_site_permalink_available );
		        		
		        		if ( classReturn != undefined && classReturn != "" ) {
		        			$('.' + classReturn ).append('<ul id="ul_message" class="success"><li>' + translateJs.admin_site_permalink_available + "</li></ul>");
		        		}
		        		
		        	} else { // não disponínel
		        		FlashMessage.onError( translateJs.admin_site_no_permalink_available );
		        		//Apaga o nome inválido
		        		$( '#' + siteNameId ).val('');
		        		
		        		if ( classReturn != undefined && classReturn != "" ) {
		        			$('.' + classReturn ).append('<ul id="ul_message" class="errors"><li>' + translateJs.admin_site_no_permalink_available + "</li></ul>");
		        		}
		        	}
		    	} else { // erro no processamento
		    		FlashMessage.onError( result.error_message( data.error_message ) );  
		    	}
		    },
		    error : function(XMLHttpRequest, textStatus, errorThrown) {
		    	FlashMessage.onError( 'Erro: ' + errorThrown );
		    }
		});
	}else if( siteId == 0 ){
		FlashMessage.onError( translateJs.admin_site_no_permalink_invalid );  
	}
	
}

function verifyEmailAdvertiserGeneric( email ) { 
	var data = { email : email };
	$.ajax( {
	    type : 'POST',
	    dataType : 'json',
	    data : data,
	    url : '/site/ajax/verify-email-advertiser/',
	    cache : false,
	    success : function(result) {
	    	if ( result.status ) { 
	    		FlashMessage.onSuccess( result.error_message );
	    	} else { // erro no processamento
	    		$('#email').val('');
	    		$('#email').focus();
	    		FlashMessage.onError( result.error_message );  
	    	}	
	    		
	    },
	    error : function(XMLHttpRequest, textStatus, errorThrown) {
	    	FlashMessage.onError( 'Erro: ' + errorThrown );
	    }
	});
}


function view_howTo_googleAnalytics( url ){
    $.fancybox({
    	href: url ,
        type:'iframe',
        height:'80%',
        width:'80%',
        autoDimensions:false 
	});
}

var zip_code;

$(document).ready(function () {
	
	$('#zip_code').focus(function(){
		zip_code = parseFloat( $(this).val() );    	
	});
	
});

function getAddress( zip_code_id ) {
	
	var zc = parseFloat( $( "#" + zip_code_id ).val() );
	
	if( zc && zc != zip_code ) {
		
		showLoading();
		
		var url  = '/ajax/get-address';
	    	
	    var data = {
	    		
	    				zip_code  	 : $( "#" + zip_code_id ).val() 
	    			
	                };
	    
	    callAjax( url, data );
		
	}
	
}