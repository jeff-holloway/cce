function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  delete el;
  return s;
}

function email_attachment(id) {
	var txt = 'Please enter the E-Mail address to send this attachment to ';
	txt = txt + '<input name="attachment_email_to" id="attachment_email_to" class="long">';
	txt = txt + '<br><br>Enter a Subject that you would like to use for this E-Mail<br>';
	txt = txt + '<input name="attachment_email_subject" id="attachment_email_subject" class="long">';
	txt = txt + '<br><br>Enter any notes you would like to include in the E-Mail<br>';
	txt = txt + "<textarea name='attachment_email_notes' id='attachment_email_notes' style='width:400px;height:75px'></textarea>";

	function mycallbackform(v,m,f){
		if(v) {
		      
		      f.attachment_email_to=$('#attachment_email_to').val();
			 f.attachment_email_notes=$('#attachment_email_notes').val();
			      
			 f.attachment_email_subject=$('#attachment_email_subject').val();
			 		      
		      if(f.attachment_email_to != '') {
				
				$('#email_attachment_icon_'+id).attr('src','images/loader.gif');
				$.ajax({url:'ajax.php?cmd=send_attachment_email',
					dataType:"xml",
					type: "post",
					data: {
						email_to: f.attachment_email_to,
						email_subject: f.attachment_email_subject,
						email_notes: f.attachment_email_notes,
						attachment_id: id
					},
					error: function() {
						$.prompt("General Error sending E-Mail, please try again");
						$('#email_attachment_icon_'+id).attr('src','images/blank_message_20.png');
					},
					success:function(xml) { 
						$('#email_attachment_icon_'+id).attr('src','images/blank_message_20.png');
						if($(xml).find("EmailResult").text() == 0) {
							$.prompt("Error sending E-Mail: " + $(xml).find("EmailResultText").text());
						} else {
							$.noticeAdd({text: "Success - email sent."});
						}
						
					}
				});
		     }
		}
	}
	
	function loadedfunction() {
		$('#attachment_email_to').val();
		$('#attachment_email_to').focus();
	}
	
	$.prompt(txt,{
	      overlayspeed: 'fast',
	      loaded: loadedfunction,
	      buttons: { Ok: true, Cancel: false },
		      submit: function(v,m,f){
				mycallbackform(v,m,f);
			 }
	});
}

function get_html_translation_table(table, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: noname
    // %          note: It has been decided that we're not going to add global
    // %          note: dependencies to php.js. Meaning the constants are not
    // %          note: real constants, but strings instead. integers are also supported if someone
    // %          note: chooses to create the constants themselves.
    // %          note: Table from http://www.the-art-of-web.com/html/character-codes/
    // *     example 1: get_html_translation_table('HTML_SPECIALCHARS');
    // *     returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}
    
    var entities = {}, histogram = {}, decimal = 0, symbol = '';
    var constMappingTable = {}, constMappingQuoteStyle = {};
    var useTable = {}, useQuoteStyle = {};
    
    useTable      = (table ? table.toUpperCase() : 'HTML_SPECIALCHARS');
    useQuoteStyle = (quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT');
    
    // Translate arguments
    constMappingTable[0]      = 'HTML_SPECIALCHARS';
    constMappingTable[1]      = 'HTML_ENTITIES';
    constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
    constMappingQuoteStyle[2] = 'ENT_COMPAT';
    constMappingQuoteStyle[3] = 'ENT_QUOTES';
    
    // Map numbers to strings for compatibilty with PHP constants
    if (!isNaN(useTable)) {
        useTable = constMappingTable[useTable];
    }
    if (!isNaN(useQuoteStyle)) {
        useQuoteStyle = constMappingQuoteStyle[useQuoteStyle];
    }
    
    if (useQuoteStyle != 'ENT_NOQUOTES') {
        entities['34'] = '&quot;';
    }
 
    if (useQuoteStyle == 'ENT_QUOTES') {
        entities['39'] = '&#039;';
    }
 
    if (useTable == 'HTML_SPECIALCHARS') {
        // ascii decimals for better compatibility
        //entities['38'] = '&amp;';
        entities['60'] = '&lt;';
        entities['62'] = '&gt;';
    } else if (useTable == 'HTML_ENTITIES') {
        // ascii decimals for better compatibility
      entities['38']  = '&amp;';
      entities['60']  = '&lt;';
      entities['62']  = '&gt;';
      entities['160'] = '&nbsp;';
      entities['161'] = '&iexcl;';
      entities['162'] = '&cent;';
      entities['163'] = '&pound;';
      entities['164'] = '&curren;';
      entities['165'] = '&yen;';
      entities['166'] = '&brvbar;';
      entities['167'] = '&sect;';
      entities['168'] = '&uml;';
      entities['169'] = '&copy;';
      entities['170'] = '&ordf;';
      entities['171'] = '&laquo;';
      entities['172'] = '&not;';
      entities['173'] = '&shy;';
      entities['174'] = '&reg;';
      entities['175'] = '&macr;';
      entities['176'] = '&deg;';
      entities['177'] = '&plusmn;';
      entities['178'] = '&sup2;';
      entities['179'] = '&sup3;';
      entities['180'] = '&acute;';
      entities['181'] = '&micro;';
      entities['182'] = '&para;';
      entities['183'] = '&middot;';
      entities['184'] = '&cedil;';
      entities['185'] = '&sup1;';
      entities['186'] = '&ordm;';
      entities['187'] = '&raquo;';
      entities['188'] = '&frac14;';
      entities['189'] = '&frac12;';
      entities['190'] = '&frac34;';
      entities['191'] = '&iquest;';
      entities['192'] = '&Agrave;';
      entities['193'] = '&Aacute;';
      entities['194'] = '&Acirc;';
      entities['195'] = '&Atilde;';
      entities['196'] = '&Auml;';
      entities['197'] = '&Aring;';
      entities['198'] = '&AElig;';
      entities['199'] = '&Ccedil;';
      entities['200'] = '&Egrave;';
      entities['201'] = '&Eacute;';
      entities['202'] = '&Ecirc;';
      entities['203'] = '&Euml;';
      entities['204'] = '&Igrave;';
      entities['205'] = '&Iacute;';
      entities['206'] = '&Icirc;';
      entities['207'] = '&Iuml;';
      entities['208'] = '&ETH;';
      entities['209'] = '&Ntilde;';
      entities['210'] = '&Ograve;';
      entities['211'] = '&Oacute;';
      entities['212'] = '&Ocirc;';
      entities['213'] = '&Otilde;';
      entities['214'] = '&Ouml;';
      entities['215'] = '&times;';
      entities['216'] = '&Oslash;';
      entities['217'] = '&Ugrave;';
      entities['218'] = '&Uacute;';
      entities['219'] = '&Ucirc;';
      entities['220'] = '&Uuml;';
      entities['221'] = '&Yacute;';
      entities['222'] = '&THORN;';
      entities['223'] = '&szlig;';
      entities['224'] = '&agrave;';
      entities['225'] = '&aacute;';
      entities['226'] = '&acirc;';
      entities['227'] = '&atilde;';
      entities['228'] = '&auml;';
      entities['229'] = '&aring;';
      entities['230'] = '&aelig;';
      entities['231'] = '&ccedil;';
      entities['232'] = '&egrave;';
      entities['233'] = '&eacute;';
      entities['234'] = '&ecirc;';
      entities['235'] = '&euml;';
      entities['236'] = '&igrave;';
      entities['237'] = '&iacute;';
      entities['238'] = '&icirc;';
      entities['239'] = '&iuml;';
      entities['240'] = '&eth;';
      entities['241'] = '&ntilde;';
      entities['242'] = '&ograve;';
      entities['243'] = '&oacute;';
      entities['244'] = '&ocirc;';
      entities['245'] = '&otilde;';
      entities['246'] = '&ouml;';
      entities['247'] = '&divide;';
      entities['248'] = '&oslash;';
      entities['249'] = '&ugrave;';
      entities['250'] = '&uacute;';
      entities['251'] = '&ucirc;';
      entities['252'] = '&uuml;';
      entities['253'] = '&yacute;';
      entities['254'] = '&thorn;';
      entities['255'] = '&yuml;';
    } else {
        throw Error("Table: "+useTable+' not supported');
        return false;
    }
    
    // ascii decimals to real symbols
    for (decimal in entities) {
        symbol = String.fromCharCode(decimal)
        histogram[symbol] = entities[decimal];
    }
    
    return histogram;
}

function htmlentities (string, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: nobbler
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: get_html_translation_table
    // *     example 1: htmlentities('Kevin & van Zonneveld');
    // *     returns 1: 'Kevin &amp; van Zonneveld'
 
    var histogram = {}, symbol = '', tmp_str = '', entity = '';
    tmp_str = string.toString();
    
    if (false === (histogram = get_html_translation_table('HTML_SPECIALCHARS', quote_style))) {
        return false;
    }
    
    for (symbol in histogram) {
        entity = histogram[symbol];
        tmp_str = tmp_str.split(symbol).join(entity);
    }
    
    return tmp_str;
}

function html_entity_decode( string, quote_style ) {
    // http://kevin.vanzonneveld.net
    // +   original by: john (http://www.jd-tech.net)
    // +      input by: ger
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   improved by: marc andreu
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: get_html_translation_table
    // *     example 1: html_entity_decode('Kevin &amp; van Zonneveld');
    // *     returns 1: 'Kevin & van Zonneveld'
    // *     example 2: html_entity_decode('&amp;lt;');
    // *     returns 2: '&lt;'
 
    var histogram = {}, symbol = '', tmp_str = '', entity = '';
    tmp_str = string.toString();
    
    if (false === (histogram = get_html_translation_table('HTML_SPECIALCHARS', quote_style))) {
        return false;
    }
 
    // &amp; must be the last character when decoding!
    delete(histogram['&']);
    histogram['&'] = '&amp;';
 
    for (symbol in histogram) {
        entity = histogram[symbol];
        tmp_str = tmp_str.split(entity).join(symbol);
    }
    
    return tmp_str;
}

//Associated file section...associated to other pieces, not the direct section used...
function create_associated_section(element_holder2, section_id, xref_id) {
	
	upload_name2="associated_container";
	inside_name2="associated";
	holder_name2="browse_holder_associated";
	input_name2="";	//fileInput";
	attach_name2="associated_holder";
	
	create_associated_section(element_holder2, section_id, xref_id, upload_name2,inside_name2,holder_name2,input_name2,attach_name2);	
}
function create_associated_section(element_holder2, section_id, xref_id, upload_name2,inside_name2,holder_name2,input_name2,attach_name2)
{	
	uc_tmp = "<div id='"+upload_name2+"'>";
		uc_tmp += "<div class='"+inside_name2+"'>";			
			uc_tmp += "<div class='header2'>Associated File(s)</div>";
			uc_tmp += "<div id='"+holder_name2+"'>&nbsp;</div>";		
		uc_tmp += "</div>";
		uc_tmp += "<div id='"+attach_name2+"'></div>";
	uc_tmp += "</div>"; 
		
	$(element_holder2).append(uc_tmp);
	
	display_files_associated(attach_name2,section_id, xref_id);
}
function display_files_associated(holder_name2,section_id, xref_id) {
	 $.ajax({
	   type: "POST",
	   url: "ajax.php?cmd=display_mrr_attachments",
	   data: {"section_id":section_id,
	   		xref_id:xref_id},
	   success: function(data) {
	   		$('#'+holder_name2+'').html(data);
	   }
	 });
}
//.....................................................................................

function create_upload_section(element_holder, section_id, xref_id) {
	
	upload_name="upload_container";
	inside_name="inside_container";
	holder_name="browse_holder";
	input_name="fileInput";
	attach_name="attachment_holder";
	
	create_adapted_upload_section(element_holder, section_id, xref_id, upload_name,inside_name,holder_name,input_name,attach_name);	
}

function create_adapted_upload_section(element_holder, section_id, xref_id, upload_name,inside_name,holder_name,input_name,attach_name)
{	
	uc_tmp = "<div id='"+upload_name+"'>";
		uc_tmp += "<div class='"+inside_name+"'>";			
			uc_tmp += "<div class='header'>Attach(ed) File(s)</div>";
			uc_tmp += "<div id='"+holder_name+"'>";
				uc_tmp += "<input id='"+input_name+"' name='"+input_name+"' type='file' />";
			uc_tmp += "</div>";			
		uc_tmp += "</div>";
		uc_tmp += "<div id='"+attach_name+"'></div>";
	uc_tmp += "</div>";
		
	$(element_holder).append(uc_tmp);
	
	display_files_adapted(attach_name,section_id, xref_id);
	
	//display_files(section_id, xref_id);
	
	$('#'+input_name+'').uploadify({
		'uploader'  : 'includes/uploadify/uploadify.swf',
		'script'    : 'uploadify.php',
		'cancelImg' : 'includes/uploadify/cancel.png',
		'sizeLimit' : 500 * 1024 * 1024,
		'fileExt'	  : '*.*',
		'multi'     : true,
		'fileDesc'  : 'Documents',
		'method'    : 'post',
		'scriptData': {'section_id': section_id,
					'xref_id': xref_id},
		'auto'      : true,
		'folder'    : 'documents',
		'onAllComplete' : function(event, data) {
			if(data.errors) {
				alert('oops, an error came up');
			} else {
				//display_files(section_id, xref_id);
				display_files_adapted(attach_name,section_id, xref_id);
			}
		}
	});	
	
	if(input_name!="fileInput")
	{
		//alert('Upload Name is '+upload_name+', Element Holder Name is '+element_holder+', Input Name is '+input_name+', Attach Name is '+attach_name+', Section '+section_id+', ID='+xref_id+'.');	
	}
}


function view_error_file(id) {
	$.ajax({
	   type: "POST",
	   dataType: "xml",
	   url: "ajax.php?cmd=view_error_file",
	   data: {file_id: id},
	   success: function(xml) {
	   		if($(xml).find('rslt').text() == '0') {
	   			alert($(xml).find('rsltmsg').text());
	   		} else {
	   			window.open($(xml).find('filename').text());
	   		}
	   }
	 });
}

function view_attached_file(section_id, xref_id, id) {
	
	$.ajax({
	   type: "POST",
	   dataType: "xml",
	   url: "ajax.php?cmd=view_attached_file",
	   data: {"section_id":section_id,
	   		xref_id:xref_id,
	   		file_id: id},
	   success: function(xml) {
	   		if($(xml).find('rslt').text() == '0') {
	   			alert($(xml).find('rsltmsg').text());
	   		} else {
	   			window.open($(xml).find('filename').text());
	   		}
	   }
	 });
}
function mrr_view_attached_file(section_id, id,moder) {
	
	$.ajax({
	   type: "POST",
	   dataType: "xml",
	   url: "ajax.php?cmd=mrr_view_attached_file",
	   data: {"section_id":section_id,
	   		file_id: id},
	   success: function(xml) {
	   		if($(xml).find('rslt').text() == '0') {
	   			alert($(xml).find('rsltmsg').text());
	   		} else {
	   			window.open($(xml).find('filename').text());
	   		}
	   }
	 });
}

function mrr_attachers_filler()
{	//debugging tool used to make sure that the attachments for the email system are being kept correctly.  
	var mrrfiles=$('#mrr_attachment_files').val();	
	var mrrdisplay=""+mrrfiles+" Files Found<br>";
	
	var i=0;
	for(i=0;i < mrrfiles;i++)
	{
		mrrdisplay+="File ID "+$('#mrr_attachment_file_'+i+'').val()+" is "+ $('#mrr_attachment_link_'+i+'').html()+".<br>";
	}		
	$('#mrr_attachers').html(mrrdisplay);	
}

function display_files(section_id, xref_id) {
	 
	 holder_name="attachment_holder";
	 
	 display_files_adapted(holder_name,section_id, xref_id);
}
function display_files_adapted(holder_name,section_id, xref_id) {
	 $.ajax({
	   type: "POST",
	   url: "ajax.php?cmd=display_attachments",
	   data: {"section_id":section_id,
	   		xref_id:xref_id},
	   success: function(data) {
	   		$('#'+holder_name+'').html(data);
	   		if(xref_id==0)
	   		{
	   			mrr_attachers_filler();	
	   		}
	   }
	 });
}

function delete_attachment(id) {
	if(confirm("Are you sure you want to delete this attachment?")) {
		$('#attachment_row_'+id).remove();
		
		 $.ajax({
		   type: "POST",
		   url: "ajax.php?cmd=delete_attachment",
		   data: {"id":id},
		   success: function(data) {
		   		
		   }
	 	});
	}
}


function loader_toggle(element_id, show_flag) {
	loader_id = element_id+"_loader"
	
	
	if(!$('#'+loader_id).length) {
		$('#'+element_id).append("<img id='"+loader_id+"' src='images/loader.gif' border='0' style='display:none'>");
	}
	
	if(show_flag) {
		$('#'+loader_id).show();
	} else {
		$('#'+loader_id).hide();
	}
	
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}

function play_video(video_id) {
	window.location = 'video.php?id=' + video_id;
}

function stop_animated(use_this) {
	$(use_this).attr('animated',0);
	$(use_this).attr('src',$(use_this).attr('original_src'));
	clearTimeout(t);
	
}

function show_animated(use_this) {
	
	tcount = $(use_this).attr('tcount');
	guid = $(use_this).attr('guid');
	
	if(tcount > 1 && $(use_this).attr('animated') == 0) {
		$(use_this).attr('animated',1);
		disp_img(1, guid, tcount, use_this);
	
	}
}
c = 1;
use_this = '';
tcount = '';
guid = '';
t = '';
function disp_img(c, guid, tcount, use_this) {
	new_this = use_this;
	if($(new_this).attr('animated') == 0) {
		return;
	}
   var img_src = 'thumbnails/'+guid+"_thumbnail_0" + c + ".jpg";
   //alert(c + ' | ' + tcount + ' | ' + guid + ' | ' + img_src + ' | ' + use_this);
   $(use_this).attr('src',img_src);
   
   if (c == tcount - 1) {
      c = 0;
   }   
   counter = c + 1;
   
   
   if($(new_this).attr('animated') == 1) {
   	t = setTimeout("disp_img(counter, guid, tcount, new_this)", 500);
	}
}


function formatItem(row) {
	return row[0] + "<br><i>" + row[1] + "</i>";
}
/*
function formatItem(row) {
	return row[0] + "<br><i>" + row[1] + "</i>";
}
*/

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + '$' + num + '.' + cents);
}
function formatMRRNumber(num,percent_flag) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + '' + num + '.' + cents + ((percent_flag==1)?'%':''));
}

function main_overlay_display(ajax_page, search_val) {
	$('#video_player_object').html("<img src='images/loader.gif'>");
	

	$.ajax({
		url: "ajax.php?cmd="+ajax_page,
		data: {search_id: search_val},
		type: "POST",
		cache:false,
		dataType: "xml",
		error: function() {
			$.prompt("Error loading AJAX page - error from includes/functions.js");
		},
		success: function(xml) {
			if($(xml).find('rslt').text() == '0') {
				$.prompt($(xml).find('rsltmsg').text());
				return;
			}

			$('#video_player_object').html($(xml).find('SearchResultHTML').text());
			$('.tablesorter').tablesorter();
		}
	});	
	
	$("#player_modal").overlay({
	    expose: { 
	        color: '#333', 
	        loadSpeed: 200, 
	        opacity: 0.9 
	    }, 
	    api:true,
	    top:75,
	    onClose: function() {			
			$('#video_player_object').html("");
		}
	}).load();
}

function mrr_search_component_history() {
	
	namer=$('#mrr_name_search').val();
	addr1=$('#mrr_addr1_search').val();
	addr2=$('#mrr_addr2_search').val();
	city=$('#mrr_city_search').val();
	state=$('#mrr_state_search').val();
	zip=$('#mrr_zip_search').val();
	
	$.ajax({
		url: 'ajax.php?cmd=mrr_search_components',
		data: {
				"mrr_name":namer,
				"mrr_addr1":addr1,
				"mrr_addr2":addr2,
				"mrr_city":city,
				"mrr_state":state,
				"mrr_zip":zip				
			},
		type: 'POST',
		cache:false,
		dataType: 'xml',
		success: function(xml) {
			if($(xml).find('html').text() == '') 
			{
				$.prompt("Error using search info '"+namer+"', '"+addr1+"', '"+addr2+"', '"+city+"', '"+state+"', and  '"+zip+"'.");
			} 
			else 
			{
				$('#video_player_object').html("<img src='images/loader.gif'>");
								
				$('#video_player_object').html("<div style='width:790px; height:500px; overflow:auto;'>"+$(xml).find('html').text()+"</div>");	
				
				
				$('#video_player_object').dialog({
					minWidth: 800,
					modal: true,
					minHeight: 500,
					title: 'Search Results'
				});
				
				/*               	
               	$("#player_modal").overlay({
               	    expose: { 
               	        color: '#333', 
               	        loadSpeed: 200, 
               	        opacity: 0.9 
               	    }, 
               	    api:true,
               	    top:75,
               	    onClose: function() {
               			
               			$('#video_player_object').html("");
               		}
               	}).load();	
               	
               	
               	*/			
			}
		}
	});              	
}


function search_full_payment_history(cust_name) {
	if(cust_name == '') {
		$.prompt("Please select a customer before trying to pull a history");
		return;
	}	
	
	$('#video_player_object').html("<img src='images/loader.gif'>");

	payment_html = load_payment_history(cust_name, 0, 1000);
	
	

	$('#video_player_object').html(payment_html);	
	
	$("#player_modal").overlay({
	    expose: { 
	        color: '#333', 
	        loadSpeed: 200, 
	        opacity: 0.9 
	    }, 
	    api:true,
	    top:75,
	    onClose: function() {
			
			$('#video_player_object').html("");
		}
	}).load();
}

function search_full_cust_history(cust_name) {
	
	if(cust_name == '') {
		$.prompt("Please select a customer before trying to pull a history");
		return;
	}
	
	
	$('#video_player_object').html("<img src='images/loader.gif'>");
	
	invoice_html = search_results('invoice', 'Invoice', cust_name);
	so_html = search_results('sales_order', 'Sales Order', cust_name);
	quote_html = search_results('quote', 'Quote', cust_name);
	payment_html = load_payment_history(cust_name, 0);
	
	

	$('#video_player_object').html(invoice_html + '<br>' + so_html + '<br>' + quote_html + '<br>' + payment_html);	
	
	$("#player_modal").overlay({
	    expose: { 
	        color: '#333', 
	        loadSpeed: 200, 
	        opacity: 0.9 
	    }, 
	    api:true,
	    top:75,
	    onClose: function() {
			
			$('#video_player_object').html("");
		}
	}).load();
}

function get_amount(str_amount) {
	
	if(str_amount == undefined) str_amount = '';
	//str_amount = str_amount.toString();
	
	tmp_amount = str_amount.replace("$","");
	tmp_amount = tmp_amount.replace(/,/g,'');
	if(isNaN(tmp_amount) || tmp_amount == '') tmp_amount = 0;
	
	return parseFloat(tmp_amount);
}

function view_customer_credits(customer_name) {
	
	
	$('#video_player_object').html("<img src='images/loader.gif'>");
	
	payment_html = load_payment_history(customer_name, 1);

	$('#video_player_object').html(payment_html);	
	
	$("#player_modal").overlay({
	    expose: { 
	        color: '#333', 
	        loadSpeed: 200, 
	        opacity: 0.9 
	    }, 
	    api:true,
	    top:75,
	    onClose: function() {
			
			$('#video_player_object').html("");
		}
	}).load();
}

function add_toolbar_hover() {
	$('.toolbar_button').hover(
		function() {
			$(this).addClass('toolbar_button_hover');
		},
		function() {
			$(this).removeClass('toolbar_button_hover');
		}
	);
}


// Convert numbers to words
// copyright 25th July 2006, by Stephen Chapman http://javascript.about.com
// permission to use this Javascript on your web page is granted
// provided that all of the code (including this copyright notice) is
// used exactly as shown (you can change the numbering system if you wish)

// American Numbering System
var th = ['','thousand','million', 'billion','trillion'];
// uncomment this line for English Number System
// var th = ['','thousand','million', 'milliard','billion'];

var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine']; 
var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen']; 
var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety']; 
function toWords(s){s = s.toString(); s = s.replace(/[\, ]/g,''); if (s != String(parseFloat(s))) return 'not a number'; var x = s.indexOf('.'); if (x == -1) x = s.length; if (x > 15) return 'too big'; var n = s.split(''); var str = ''; var sk = 0; for (var i=0; i < x; i++) {if ((x-i)%3==2) {if (n[i] == '1') {str += tn[Number(n[i+1])] + ' '; i++; sk=1;} else if (n[i]!=0) {str += tw[n[i]-2] + ' ';sk=1;}} else if (n[i]!=0) {str += dg[n[i]] +' '; if ((x-i)%3==0) str += 'hundred ';sk=1;} if ((x-i)%3==1) {if (sk) str += th[(x-i-1)/3] + ' ';sk=0;}} if (x != s.length) {var y = s.length; str += 'and '; for (var i=x+1; i<y; i++) str += dg[n[i]] +' ';} return str.replace(/\s+/g,' ');}
	
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

function view_item(line_number) {
	// called from invoice.php, sales_order.php, quote.php to view pull up an item to view / edit the details of it
	item_name = $('#item_'+line_number).val()
	if(item_name == '') return;
	
	
	window.open("manage_inventory.php?item_name="+item_name);
}

function edit_customer() {
	// usually called from the invoice/sales order/quote pages to open/edit the selected customer
	
	if($('#customer_name').val() == '') {
		$.prompt("Please enter a customer before attempting to edit them");
		return;
	}
	
	window.open('manage_builders.php?id=0&cust=1&searchbox='+$('#customer_name').val());		//manage_customers.php?
}
function edit_builder() {
	// usually called from the invoice/sales order/quote pages to open/edit the selected customer
	
	if($('#builder_name').val() == '') {
		$.prompt("Please enter a builder before attempting to edit them");
		return;
	}
	
	window.open('manage_builders.php?id=0&searchbox='+$('#customer_name').val());
}

function edit_vendor() {
	// usually called from the invoice/sales order/quote pages to open/edit the selected customer
	
	if($('#vendor_name').val() == '') {
		$.prompt("Please enter a vendor before attempting to edit them");
		return;
	}
	
	window.open('manage_vendors.php?searchbox='+$('#vendor_name').val());
}

function validate_price_override(use_this, user_id) {
	// called from the invoice/so/quote pages to check against the price overrides to make sure
	// the user has access to do them
	
	line_number = $(use_this).attr('line_number');
	
	//alert($(use_this).val());
	
	// make sure our base price tables are current
	update_base_price(line_number);
	
	
	line_number = $(use_this).attr('line_number');

	base_price = get_price(line_number, true);
	new_amount = get_amount($(use_this).val());

	//alert(new_amount + ' | ' + base_price);

	if(new_amount >= base_price) {
		// new price is more expensive than base price, let it go through with no problems
		$('#price_override_by_id_'+line_number).val(user_id);
	} else {
		// new price is less than what the base price is, so get confirmation
		discount_percent = (1 - (new_amount / base_price)) * 100;
		if(access_level > 80) {
			// admin, let them change the price, but get a confirmation first
			prompt_txt = "The price you've entered '<span class='alert2'>" + formatCurrency(new_amount) + "</span>' is below the price set for this customer of";
			prompt_txt += " '<span class='alert2'>" + formatCurrency(base_price) + "</span>' by '<span class='alert2'>" + discount_percent.toFixed(2) + "%</span>' ";
			prompt_txt += " <p>Item: " + $('#item_'+line_number).val() + "<p>";
			prompt_txt += "Are you sure you want to do this?";
			$.prompt(prompt_txt, {
					buttons: {Yes: true, No:false},
					submit: function(v, m, f) {
						if(v) {
							
						} else {
							new_amount = base_price;
						}
						$('#unit_price_' + line_number).val(formatCurrency(new_amount));
						calc_line(line_number);
						$('#price_override_by_id_'+line_number).val(user_id);
						
					}
				}
			);
			return;
		} else {
			
			// non admin
			prompt_txt = "The price you've entered '<span class='alert2'>" + formatCurrency(new_amount) + "</span>' is below the price set for this customer of";
			prompt_txt += " '<span class='alert2'>" + formatCurrency(base_price) + "</span>' by '<span class='alert2'>" + discount_percent.toFixed(2) + "%</span>' ";
			prompt_txt += " <p>Item: " + $('#item_'+line_number).val() + "<p>";
			prompt_txt += " <p>Requires admin approval<p>";
			prompt_txt += " <table><tr><td>Username</td><td><input name='admin_override_username' id='admin_override_username'></td></tr>";
			prompt_txt += " <tr><td>Password</td><td><input name='admin_override_password' type='password'></td></tr></table>";
			$.prompt(prompt_txt, {
					loaded:function() {
						$('#admin_override_username').focus();
					},
					buttons: {Yes: true, No:false},
					submit: function(v, m, f) {
						if(v) {
							// selected yes, verify admin login information
							$.ajax({
								url: "ajax.php?cmd=verify_access_level",
								data: {username:f.admin_override_username,
									password:f.admin_override_password,
									check_access_level:80},
								type: "POST",
								cache:false,
								async:false,
								dataType: "xml",
								success: function(xml) {
									if($(xml).find('rslt').text() == '0') {
										$.prompt($(xml).find('rsltmsg').text());
										new_amount = base_price;
									} else {
										// good to go
										$('#price_override_by_id_'+line_number).val($(xml).find('userid').text());
									}
								}
							});
						} else {
							// selected no, use the base price instead
							new_amount = base_price;
						}
						$('#unit_price_' + line_number).val(formatCurrency(new_amount));
						calc_line(line_number);
						
					}
				}
			);
			return;
			//$.prompt("Cannot set price lower than '" + formatCurrency(base_price) + "'");
			new_amount = base_price;
		}
	}
	$(use_this).val(formatCurrency(new_amount));
	calc_line(line_number);	
}

function price_override_handler(use_this) {
	// handle when the price override's checkbox state is changed
	// called from the invoice/so/quote page
	
	line_number = $(use_this).attr('line_number');
	
	$(use_this).hide();
	
	if($(use_this).attr('checked')) {
		// make sure this user has access to do a price override
		$('#unit_price_'+line_number).removeClass('ronly');
		$('#unit_price_'+line_number).attr('readonly','');
	} else {
		$('#unit_price_'+line_number).addClass('ronly');
		$('#unit_price_'+line_number).attr('readonly','readonly');
		update_base_price(line_number);
		unit_price = get_price(line_number, true);
		
		//alert(unit_price);
		$('#unit_price_'+line_number).val(formatCurrency(unit_price));
	}
	calc_line($(use_this).attr('line_number'));
	
	$(use_this).show();
}

function update_base_price(line_number) {
	// function to check to see if our array for customer prices has been set, if not, then load them via AJAX
	// called from the invoice/so/quote page
	
	item_id = $('#item_id_'+line_number).val();
	if(custom_min_qty[item_id] == undefined) {
		// our base price isn't loaded for this item -- load it now
		lookup_item(line_number, true);
	}
}

function get_price(line_number, return_base_price) {
	// called from invoice/so/quote page
	qty = get_amount($('#qty_'+line_number).val());
	if(qty == '') return 0;
	
	item_id = $('#item_id_'+line_number).val();
	unit_price = get_amount($('#unit_price_'+line_number).val());
	//alert(custom_min_qty[item_id]);
	if(custom_min_qty[item_id] != undefined && (!$('#price_override_'+line_number).attr('checked') || return_base_price)) {
		
		for(item in custom_min_qty[item_id]) {
			if(qty >= item) {
				unit_price = get_amount(custom_min_qty[item_id][item]);
			}
			//alert(custom_min_qty[item_id][item]);
		}
	}
	
	return unit_price;
}

function prepare_price_override_fields() {
	$('.price_override').each(function() {
		line_number = $(this).attr('line_number');
		if($(this).attr('checked')) {				
			$('#unit_price_'+line_number).removeClass('ronly');
			$('#unit_price_'+line_number).attr('readonly','');
		} else {
			$('#unit_price_'+line_number).addClass('ronly');
			$('#unit_price_'+line_number).attr('readonly','readonly');
		}
	});	
}

var dirtyflag;
function save_changes_check() {
	// check to see if any fields change, if they do, prompt the user to save before changing pages
	$("input:not(input[searchbox]), textarea, select").change(function() {
		dirtyflag = true;
	});
	
	window.onbeforeunload = function() {
		if(dirtyflag) return 'You have unsaved changes';
	};
}
function save_changes_check_mrr() {
	// check to see if any fields change, if they do, prompt the user to save before changing pages
	$("input:not( input[searchbox],input[name='new_sub_name'],input[class='skip_save_check']),textarea:not(textarea[class='new_sub_name']), select:not(select[class='skip_save_check'])").change(function() {
		dirtyflag = true;		//
	});
	
	window.onbeforeunload = function() {
		if(dirtyflag) return 'You have unsaved changes';
	};
}

function timeout_check() {
	// check to see if the user needs to be logged out due to inactivity
	
	$.ajax({
		url: 'ajax.php?cmd=timeout_check',
		data: {},
		type: 'POST',
		cache:false,
		dataType: 'xml',
		success: function(xml) {
			if($(xml).find('TimeoutBool').text() == '0') {
				// user did not time out, reset the timeout check
				time_until_timeout = $(xml).find('TimeoutLeft').text();
				var session_timeout = setTimeout(timeout_check,(time_until_timeout * 1000));
			} else if($(xml).find('TimeoutBool').text() == '1') {
				// user timed out, redirect
				window.location = 'login.php?timeout=1';
			}
		}
	});
}

function check_dupliate_name(table_name, check_name, check_id) {
	// function to make sure 
	
	var duplicate_name;
	
	$.ajax({
		url: 'ajax.php?cmd=check_dupliate_name',
		data: {table_name:table_name,
				check_name:check_name,
				check_id:check_id},
		type: 'POST',
		cache:false,
		async:false,
		dataType: 'xml',
		success: function(xml) {
			if($(xml).find('DuplicateBool').text() == '1') {
				// this is a duplicate name
				duplicate_name = true;
				$.prompt("The ID '"+check_name+"' has alrady been used (as a '" + $(xml).find('LocationUsed').text()+"')");
			} else {
				// name is okay
				duplicate_name = false;
			}
		}
	});
	
	return duplicate_name;
}

function verify_positive_number(obj) {
	// makes sure a number is both 'A', a number, and 'B', positive
	if(get_amount($(obj).val()) < 0 || isNaN(get_amount($(obj).val()))) {
		$.prompt("You cannot have a negative number in this field, please enter either '0', or a positive value.");
		$(obj).val(0);
		
		return false;
	} else {
		return true;
	}
}
function verify_positive_number_invoice(obj) {
	// makes sure a number is both 'A', a number, and 'B', positive
	if(isNaN(get_amount($(obj).val()))) {
		$.prompt("You must have a number in this field. Please enter either '0', or a numeric value.");
		$(obj).val(0);
		
		return false;
	} else {
		return true;
	}
}

function adjust_inv_submit_local(v, m, f) {
	if(v) {
		var adjust_qty = f.adjust_qty;
		var adjust_cost = f.adjust_cost;
		var adjust_reason = f.adjust_reason;
		var local_id = f.inventory_location_id;
		var use_inventory_id = f.inventory_id_holder_qoh;
		var z_cntr= f.inventory_location_cntr;
		
		if(get_amount(f.inventory_location_id) == 0 || isNaN(get_amount(f.inventory_location_id))) {
			$('#adjust_error_holder').html("<span class='alert'>Notice:</span> No Site Location Found. <span class='alert'>(Try logging in to Location)</span>");
			return false;
		}
		
		if(get_amount(f.adjust_qty) == 0 || isNaN(get_amount(f.adjust_qty))) {
			$('#adjust_error_holder').html("<span class='alert'>Notice:</span> Please enter a valid <span class='alert'>Adjustment Qty</span>");
			return false;
		}
		
		if(f.adjust_reason == '') {
			$('#adjust_error_holder').html("<span class='alert'>Notice:</span> Please enter the <span class='alert'>reason</span> for this adjustment");
			return false;
		}
		
		$('#adjust_error_holder').html("<img src='images/loader.gif'>");
		
		$.ajax({
			url:"ajax.php?cmd=adjust_inventory",
			data: {inventory_id: use_inventory_id,
				adjust_qty: get_amount(adjust_qty),
				adjust_cost: get_amount(adjust_cost),
				adjust_reason: adjust_reason,
				"location_id":local_id
				},
			type:"POST",
			dataType: "xml",
			async: false,
			success: function(xml) {
				//$.prompt('Inventory adjustment sucessful');
				if($(xml).find('rslt').text() == '0') {
					$.prompt($(xml).find('rsltmsg').text());
				} else {
					if(z_cntr==0)
					{
						//$('#qty_on_hand').html($(xml).find('QtyOnHand').text());
						$('#qty_on_hand_'+use_inventory_id).html($(xml).find('QtyOnHand').text());
						$('#line_holder_'+use_inventory_id).effect('pulsate', {}, 'slow');
					}
					else
					{
						//$('#qty_on_hand').html($(xml).find('QtyOnHand').text());
						$('#qty_on_hand_'+use_inventory_id+'_'+local_id).html($(xml).find('QtyOnHand').text());
						$('#line_holder_'+use_inventory_id+'_'+local_id).effect('pulsate', {}, 'slow');
					}
				}
			}
					
		});
		
		return true;
		
	} else {
		return true;
	}
}
function adjust_inv_submit(v, m, f) {
	if(v) {
		var adjust_qty = f.adjust_qty;
		var adjust_cost = f.adjust_cost;
		var adjust_reason = f.adjust_reason;
		var use_inventory_id = f.inventory_id_holder_qoh;
		
		if(get_amount(f.adjust_qty) == 0 || isNaN(get_amount(f.adjust_qty))) {
			$('#adjust_error_holder').html("<span class='alert'>Notice:</span> Please enter a valid <span class='alert'>Adjustment Qty</span>");
			return false;
		}
		
		if(f.adjust_reason == '') {
			$('#adjust_error_holder').html("<span class='alert'>Notice:</span> Please enter the <span class='alert'>reason</span> for this adjustment");
			return false;
		}
		
		$('#adjust_error_holder').html("<img src='images/loader.gif'>");
		
		$.ajax({
			url:"ajax.php?cmd=adjust_inventory",
			data: {inventory_id: use_inventory_id,
				adjust_qty: get_amount(adjust_qty),
				adjust_cost: get_amount(adjust_cost),
				adjust_reason: adjust_reason
				},
			type:"POST",
			dataType: "xml",
			async: false,
			success: function(xml) {
				//$.prompt('Inventory adjustment sucessful');
				if($(xml).find('rslt').text() == '0') {
					$.prompt($(xml).find('rsltmsg').text());
				} else {
					//$('#qty_on_hand').html($(xml).find('QtyOnHand').text());
					$('#qty_on_hand_'+use_inventory_id).html($(xml).find('QtyOnHand').text());
					$('#line_holder_'+use_inventory_id).effect('pulsate', {}, 'slow');
				}
			}
					
		});
		
		return true;
		
	} else {
		return true;
	}
}
function inventory_adjust(id) {
	if(id == 0) {
		$.prompt("You must save your inventory item before adjusting the qty on hand");
		return;
	}	
	ahtml = "<table>";
	ahtml += "<tr><td colspan='2' style='text-align:center'><h3>Inventory Adjustment</h3></td></tr>";
	ahtml += "<tr><td colspan='2'><div id='adjust_error_holder'></div></td></tr>";
	ahtml += "<tr><td>Adjustment Qty</td><td><input name='adjust_qty' id='adjust_qty' value='0' class='veryshort'></td></tr>";
	ahtml += "<tr><td>Adjust Cost</td><td><input name='adjust_cost' id='adjust_cost' value='0' class='veryshort'></td></tr>";
	ahtml += "<tr><td>Reason</td><td><textarea name='adjust_reason' id='adjust_reason' style='width:250px;height:60px'></textarea></td></tr>";
	ahtml += "</table><input type='hidden' id='inventory_id_holder_qoh' name='inventory_id_holder_qoh' value='"+id+"'> ";
	
	$.prompt(ahtml, {submit:adjust_inv_submit,
					buttons: {Ok:true, Cancel:false}
	});
}
function inventory_adjust_local(id,locid,z_cntr) {
	if(id == 0) {
		$.prompt("You must save your inventory item before adjusting the qty on hand");
		return;
	}
	ahtml = "<table>";
	ahtml += "<tr><td colspan='2' style='text-align:center'><h3>Inventory Adjustment from Location "+locid+"</h3></td></tr>";
	ahtml += "<tr><td colspan='2'><div id='adjust_error_holder'></div></td></tr>";
	ahtml += "<tr><td>Adjustment Qty</td><td><input name='adjust_qty' id='adjust_qty' value='0' class='veryshort'></td></tr>";
	ahtml += "<tr><td>Adjust Cost</td><td><input name='adjust_cost' id='adjust_cost' value='0' class='veryshort'></td></tr>";
	ahtml += "<tr><td>Reason</td><td><textarea name='adjust_reason' id='adjust_reason' style='width:250px;height:60px'></textarea></td></tr>";
	ahtml += "</table><input type='hidden' id='inventory_id_holder_qoh' name='inventory_id_holder_qoh' value='"+id+"'>";
	ahtml += "<input type='hidden' id='inventory_location_id' name='inventory_location_id' value='"+locid+"'> ";
	ahtml += "<input type='hidden' id='inventory_location_cntr' name='inventory_location_cntr' value='"+z_cntr+"'> ";
	
	$.prompt(ahtml, {submit:adjust_inv_submit_local,
					buttons: {Ok:true, Cancel:false}
	});
}

function mrr_master_pack_adjustments(cnt,id1,mrr_qty1,mrr_cost1,mrr_reason1,id2,mrr_qty2,mrr_cost2,mrr_reason2)
{
	var part1=mrr_inventory_adjust(id1,mrr_qty1,mrr_cost1,mrr_reason1);	
	var part2=mrr_inventory_adjust(id2,mrr_qty2,mrr_cost2,mrr_reason2);
	
	if(part1 > 0 && part2 > 0)
	{
		$.prompt('Master Inventory adjustment sucessful');
		$('#mrr_adjuster_'+cnt).html('***');	
	}
	else
	{
		if(part1 >0)
		{
			$.prompt('Inventory adjustment sucessful for Master Pack only, not the Items');
		}
		if(part2 >0)
		{
			$.prompt('Inventory adjustment sucessful for Items only, not the Master Pack.');
		}
	}
}
function mrr_inventory_adjust(id,mrr_qty,mrr_cost,mrr_reason) {
	if(id > 0)
	{
		//alert('Hitting '+id+' for adjustments. '+mrr_qty+'. '+mrr_cost+'. '+mrr_reason+'');
		var adjust_qty = mrr_qty;
		var adjust_cost = mrr_cost;
		var adjust_reason = mrr_reason;
		var use_inventory_id = id;
		
		$.ajax({
			url:"ajax.php?cmd=adjust_inventory_mrr",
			data: {inventory_id: use_inventory_id,
				adjust_qty: adjust_qty,
				adjust_cost: adjust_cost,
				adjust_reason: adjust_reason
				},
			type:"POST",
			dataType: "xml",
			async: false,
			success: function(xml) {
				//$.prompt('Inventory adjustment sucessful');				
			}
					
		});
		return 1;			
	}
	return 0;	
}

function open_sales_order_check(xml) {
	// called from invoice/so/po pages to check if the load_customer has any open sales orders
	// display a prompt if they do
	
	so_count = 0;
	so_html = "<table width='100%'>";
	so_html += "<tr>";
	so_html += "<td nowrap><b>Sales Order Number</b></td>";
	so_html += "<td nowrap><b>Date</b></td>";
	so_html += "<td nowrap><b>Total</b></td>";
	so_html += "</tr>";
	$(xml).find('SalesOrder').each(function() {
		so_count++;
		so_html += "<tr>";
		so_html += "<td><a href='sales_order.php?sales_order_id="+$(this).find('SOID').text()+"' target='view_so_"+$(this).find('SONumber').text()+"'>"+$(this).find('SONumber').text()+"</a></td>";
		so_html += "<td>"+$(this).find('SODate').text()+"</td>";
		so_html += "<td>"+$(this).find('SOTotal').text()+"</td>";
		so_html += "</tr>";
	});
	so_html += "</table>";
	
	if(so_count > 0) $.prompt("Notice: This customer has "+so_count+" open Sales Order(s)<p>"+so_html);	
}

function show_overlay(content) {
					
	$('#video_player_object').html(content);
	$("#player_modal").overlay({
	    expose: { 
	        color: '#333', 
	        loadSpeed: 200, 
	        opacity: 0.9 
	    }, 
	    api:true,
	    top:75,
	    onClose: function() {
			
			$('#video_player_object').html("");
		}
	}).load();
}

//js for chart of accounts select with input/google-search
function mrr_coa_selector_picker(obj_id)
{			
	 var combo1 = $('#'+obj_id+'_coa_selector');
	 var valuer = $(combo1).find(":selected").text();
	 $('#'+obj_id).val(valuer);
	 $(combo1).val(0);
}
		
function load_coa_list(use_this) {
	
	if($(use_this).attr('has_loaded') == 1) {
		return;
	}
	
	$(use_this).html("<option>Loading...</option>");
	$(use_this).attr('has_loaded','1');
	$.ajax({
		url: "ajax.php?cmd=mrr_chart_of_accounts_select",
		data: { "search_mode":1	},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			 
			 temp_box = "<option value=0></option>"+$(xml).find('COA').text();
			 $(use_this).html(temp_box);
		}
	});	
	
}

$.fn.extend({
	autocomplete_coa: function(options) {
		var temp_box="";
		var obj_id = $(this).attr('id');
		$(this).autocomplete('ajax.php?cmd=search_chart');
		
		 temp_box+="<select onclick='load_coa_list(this);' onChange='mrr_coa_selector_picker(\""+obj_id+"\")' id='"+obj_id+"_coa_selector' style='width:19px;'>";
		 temp_box+="<option value=0></option>";	
		 temp_box+="</select>";		
		 $('#'+obj_id).after(temp_box)

	}
});
	
//js for print reports...will get used on several pages
	
	function print_report() {
		mrr_id=$('#mrr_report_id').val();
		
		if(mrr_id == 0) {
			$.prompt("You must have a Report created before you can print it");
			return;
		}
		
		print_icon_holder = "print_icon";
		
		$('#'+print_icon_holder).attr('src','images/loader.gif');
		
		$.ajax({url:'print_report.php?id='+mrr_id,
			data: {},
			type: "POST",
			dataType:"xml",
			error: function() {
				$.prompt("General Error printing PDF, please try again");
				$('#'+print_icon_holder).attr('src','images/printer.png');
			},
			success:function(xml) { 
				window.open($(xml).find("PDFName").text(),"_interLink",''); 
				$('#'+print_icon_holder).attr('src','images/printer.png');
			}
		});	
	}
	function fax_report() {
		mrr_id=$('#mrr_report_id').val();
		
		if(mrr_id == 0) {
			$.prompt("You must have a Report created before you can fax it");
			return;
		}
		
		var txt = 'Please enter the full Fax Number to send this Report to:<br>(For Example: enter "16154102019" for 1-615-410-2019.)';
		txt = txt + '<input name="fax_to" id="fax_to" class="long">';
		//txt = txt + '<br><br>Enter a Subject that you would like to use for this Fax<br>';
		//txt = txt + '<input name="fax_subject" id="fax_subject" class="long">';
		txt = txt + '<br><br>Enter any notes you would like to include in the Fax<br>';
		txt = txt + "<textarea name='fax_notes' id='fax_notes' style='width:400px;height:75px'></textarea>";

		function mycallbackform(v,m,f){
			if(v) {
					// send now
					f.fax_to=$('#fax_to').val();
			      	f.fax_notes=$('#fax_notes').val();
			      
				      if(f.fax_to != '') {
						
						mysubject='';
					
						$('#fax_icon').attr('src','images/loader.gif');
						$.ajax({url:'print_report.php?id='+mrr_id+'&fax_code=1&email_to='+f.fax_to+'&email_subject='+mysubject+'',
							dataType:"xml",
							type: "post",
							data: {
								email_notes: f.fax_notes
							},
							error: function() {
								$.prompt("General Error sending Fax, please try again");
								$('#fax_icon').attr('src','images/email.png');
							},
							success:function(xml) { 
								if($(xml).find("EmailResult").text() == 0) {
									$.prompt("Error sending Fax: " + $(xml).find("EmailResultText").text());
								} else {
									$.noticeAdd({text: "Success - fax sent."});
								}
								$('#fax_icon').attr('src','images/email.png');
							}
						});
				     }
			}
		}
		function loadedfunction() {
			$('#fax_to').val(customer_fax);
			$('#fax_to').focus();
		}
		
		$.prompt(txt,{
		      overlayspeed: 'fast',
		      loaded: loadedfunction,
		      buttons: { Ok: true,  Cancel: false },
		      submit: function(v,m,f){
				mycallbackform(v,m,f);
			 }
		});		//"Send Later": "later",
	}
	function email_report(mrr_email,mrr_name) {
		mrr_id=$('#mrr_report_id').val();
		
		if(mrr_id == 0) {
			$.prompt("You must have a Report created before you can E-Mail it");
			return;
		}

		var txt = 'Please enter the E-Mail address to send this invoice to ';
		txt = txt + '<input name="email_to" id="email_to" class="long">';
		txt = txt + '<br><br>Enter a Subject that you would like to use for this E-Mail<br>';
		txt = txt + '<input name="email_subject" id="email_subject" class="long">';
		txt = txt + '<br><br>Enter a Reply Email Address to use for this E-Mail<br>';
		txt = txt + '<input name="email_reply_addr" id="email_reply_addr" value="'+mrr_email+'" class="long">';
		txt = txt + '<br><br>Enter a Reply Name that you would like to use for this E-Mail<br>';
		txt = txt + '<input name="email_reply_name" id="email_reply_name" value="'+mrr_name+'" class="long">';	
		txt = txt + '<br><br>Enter any notes you would like to include in the E-Mail<br>';
		txt = txt + "<textarea name='email_notes' id='email_notes' style='width:400px;height:75px'></textarea>";

		function mycallbackform4(v,m,f){
			if(v) {
					// send now
				     f.email_to=$('#email_to').val();
			      	f.email_notes=$('#email_notes').val();
			      	
			      	f.email_subject=$('#email_subject').val();
			      	f.email_reply_addr=$('#email_reply_addr').val();
			      	f.email_reply_name=$('#email_reply_name').val();
			      
				     if(f.email_to != '') {
						customer_email = f.email_to;
						var mysubject=""+f.email_subject+"";
						mysubject.replace(" ","+");
						
						var myreply_addr=""+f.email_reply_addr+"";
						myreply_addr.replace(" ","+");
						
						var myreply_name=""+f.email_reply_name+"";
						myreply_name.replace(" ","+");
					
						$('#email_icon').attr('src','images/loader.gif');
						$.ajax({url:'print_report.php?id='+mrr_id+'&email_to='+f.email_to+'&email_subject='+mysubject+'&reply_addr='+myreply_addr+'&reply_name='+myreply_name+'',
							dataType:"xml",
							type: "post",
							data: {
								email_notes: f.email_notes
							},
							error: function() {
								$.prompt("General Error sending E-Mail, please try again");
								$('#email_icon').attr('src','images/email.png');
							},
							success:function(xml) { 
								if($(xml).find("EmailResult").text() == 0) {
									$.prompt("Error sending E-Mail: " + $(xml).find("EmailResultText").text());
								} else {
									$.noticeAdd({text: "Success - email sent."});
								}
								$('#email_icon').attr('src','images/email.png');
							}
						});
				     }
			}
		}
		
		function mrr_history_loader(dtfrom,dtto)
		{				
			//window.location = "report_reconciled_history.php?date_from="+dtfrom+"&date_to="+dtto+"&build_report=1";	
			window.location = "report_reconciled_history.php";
		}
		
		function loadedfunction() {
			
			//$('#email_to').val(customer_email);
			$('#email_to').focus();
		}
		
		$.prompt(txt,{
		      overlayspeed: 'fast',
		      loaded: loadedfunction,
		      buttons: { Ok: true, Cancel: false },
		      submit: function(v,m,f){
				mycallbackform4(v,m,f);
			 }
		});
	}

	function mrr_will_call_option(moder,item)
	{
		$.prompt("The "+moder+" Option has been disabled for this 'Will Call' "+item+".<br>To "+moder+", "+item+" cannot be saved as 'Will Call'.");
	}	
	function mrr_history_loader(dtfrom,dtto)
	{				
		window.location = "report_reconciled_history.php?date_from="+dtfrom+"&date_to="+dtto+"&build_report=1";	
	}

	function search_inventory_counters(itemid)
	{
		$('#video_player_object').html("<img src='images/loader.gif'>");
     	
     	mrr_results=search_results('mrr_inventory_counters', 'Inventory Counter', ''+itemid+'');
     
     	$('#video_player_object').html(mrr_results);	
     	
     	$("#player_modal").overlay({
     	    expose: { 
     	        color: '#333', 
     	        loadSpeed: 200, 
     	        opacity: 0.9 
     	    }, 
     	    api:true,
     	    top:75,
     	    onClose: function() {
     			
     			$('#video_player_object').html("");
     		}
     	}).load();	
	}
	function search_inventory_price_levels(itemid)
	{
		$('#video_player_object').html("<img src='images/loader.gif'>");
     	
     	mrr_results=search_results('mrr_inventory_price_levels', 'Inventory Pricing', ''+itemid+'');
     
     	$('#video_player_object').html(mrr_results);	
     	
     	$("#player_modal").overlay({
     	    expose: { 
     	        color: '#333', 
     	        loadSpeed: 200, 
     	        opacity: 0.9 
     	    }, 
     	    api:true,
     	    top:75,
     	    onClose: function() {
     			
     			$('#video_player_object').html("");
     		}
     	}).load();	
	}
	
	function mrr_change_operation_location(mrr_id,operation)
	{
		if(mrr_id == 0) {
			$.prompt("You must save this form before you can change the location.  Or, change location and recreate this form.");
			return;
		}
		var sel_bx='';
		
		$.ajax({
     		url: "ajax.php?cmd=mrr_change_op_location_box",
     		data: { 
     				
     			},
     		type: "POST",
			cache:false,
			async: false,
			dataType: "xml",
     		error: function() {
				$.prompt("General Error finding locations for this form");
			},
			success:function(xml) { 
				if($(xml).find("html").text() == 0) {
					$.prompt("Error finding Locations...");
				}
				else
				{
					sel_bx=$(xml).find("html").text();
				}
			}
     	});	
				
		var txt = "Please select the new location: "+sel_bx+"<br>";
		
		function mycallbackform(v,m,f){
			if(v) {
					// send now
				      if(f != undefined && f.new_location_id != '') {
						var new_location = f.new_location_id;	
						
						//alert('Location ID = '+new_location+'.');					
						
						$.ajax({
                         		url: "ajax.php?cmd=mrr_change_op_location_id",
                         		data: { 
                         				"new_location_id":new_location,
                         				"op_id":mrr_id,
                         				"op_mode":operation
                         			},
                         		type: "POST",
                         		cache:false,
                         		dataType: "xml",
                         		error: function() {
								$.prompt("General Error changing location id for this form");
							},
							success:function(xml) { 
								if($(xml).find("newLocation").text() == 0) {
									$.prompt("Error changing Location ID to " + new_location + ".");
								} else {
									$.noticeAdd({text: "Success - Location ID has been changed for this form."});
									window.location.reload( true );
								}
							}
                         	});	
				     }
			}
		}
				
		function loadedfunction() {
			
			$('#new_location_id').focus();
		}
		
		$.prompt(txt,{
		      callback: mycallbackform,
		      overlayspeed: 'fast',
		      loaded: loadedfunction,
		      buttons: { Ok: true, Cancel: false }
		});
	}
		
	function mrr_warning_feature_locaked_msg(moder)
	{
		lab='';
		feats='';
		if(moder==1)
		{
			lab='Purchase Order';
			feats='Receive or Delete';
		}
		if(moder==2)
		{
			lab='Invoice';
			feats='Delete';
		}
		if(moder==3)
		{
			lab='Sales Order';
			feats='Delete';
		}
		if(moder==4)
		{
			lab='Quote';
			feats='Delete';
		}
		
		$.prompt("You must be in the proper location to <span class='alert'>"+feats+"</span> this <span class='alert'>"+lab+"</span>");	
	}
	
	function get_qoh(item_id, element_id) {
		// check to see if we already have some result, if we do, exit out
		if($(element_id).html() != '') return;
		
		$.ajax({
			url: "ajax.php?cmd=ajax_qty_on_hand&item_id="+item_id,
			type: "get",
			dataType: "html",
			success: function(html) {
				$(element_id).html(html);
			}
		});
		
	}