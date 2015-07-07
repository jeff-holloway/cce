function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  delete el;
  return s;
}

function email_attachment(id) 
{
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

function getURLParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function get_html_translation_table(table, quote_style) 
{
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

function htmlentities (string, quote_style) 
{
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

function html_entity_decode( string, quote_style ) 
{
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
function display_files_associated(holder_name2,section_id, xref_id) 
{
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

function create_upload_section(element_holder, section_id, xref_id) 
{
	
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


function view_error_file(id) 
{
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

function view_attached_file(section_id, xref_id, id) 
{
	
	$.ajax({
	   type: "POST",
	   dataType: "xml",
	   async: false,
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
function mrr_view_attached_file(section_id, id,moder) 
{
	
	$.ajax({
	   type: "POST",
	   dataType: "xml",
	   async: false,
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

function display_files(section_id, xref_id) 
{
	 
	 holder_name="attachment_holder";
	 
	 display_files_adapted(holder_name,section_id, xref_id);
}
function display_files_adapted(holder_name,section_id, xref_id) 
{
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

function delete_attachment(id) 
{
	$( "#dialog-file_removal" ).dialog({
		width: 'auto',
		modal: true,
		open: function() {
			
		},
          buttons: {
          	"Yes": function() 
          	{
          		$('#attachment_row_'+id).remove();
          		
          		$.ajax({
	   				type: "POST",
	   				url: "ajax.php?cmd=delete_attachment",
	   				data: {"id":id},
	   				success: function(data) {
	   						
	   				}
 				});         		
          		
          		$( this ).dialog( "close" );
          	},
          	"No": function() 
          	{               		
          		$( this ).dialog( "close" );
          	}
          }
	});
}
	

function loader_toggle(element_id, show_flag) 
{
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

function isValidEmailAddress(emailAddress) 
{
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}

function play_video(video_id) 
{
	window.location = 'video.php?id=' + video_id;
}

function stop_animated(use_this) 
{
	$(use_this).attr('animated',0);
	$(use_this).attr('src',$(use_this).attr('original_src'));
	clearTimeout(t);
	
}

function show_animated(use_this) 
{
	
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
function disp_img(c, guid, tcount, use_this) 
{
	new_this = use_this;
	if($(new_this).attr('animated') == 0) 
	{
		return;
	}
   	var img_src = 'thumbnails/'+guid+"_thumbnail_0" + c + ".jpg";
   	//alert(c + ' | ' + tcount + ' | ' + guid + ' | ' + img_src + ' | ' + use_this);
   	$(use_this).attr('src',img_src);
   
   	if(c == tcount - 1) 
   	{
   	   c = 0;
   	}   
   	counter = c + 1;
   	   
   	if($(new_this).attr('animated') == 1) 
   	{
   		t = setTimeout("disp_img(counter, guid, tcount, new_this)", 500);
	}
}


function formatItem(row) 
{
	return row[0] + "<br><i>" + row[1] + "</i>";
}
/*
function formatItem(row) {
	return row[0] + "<br><i>" + row[1] + "</i>";
}
*/

function formatCurrency(num) 
{
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
function formatMRRNumber(num,percent_flag) 
{
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

function main_overlay_display(ajax_page, search_val) 
{
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
			//$('.tablesorter').tablesorter();
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

function mrr_search_component_history() 
{
	
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



function get_amount(str_amount) 
{
	
	if(str_amount == undefined) str_amount = '';
	//str_amount = str_amount.toString();
	
	tmp_amount = str_amount.replace("$","");
	tmp_amount = tmp_amount.replace(/,/g,'');
	if(isNaN(tmp_amount) || tmp_amount == '') tmp_amount = 0;
	
	return parseFloat(tmp_amount);
}

var dirtyflag;
function save_changes_check() 
{
	// check to see if any fields change, if they do, prompt the user to save before changing pages
	$("input:not(input[searchbox]), textarea, select").change(function() {
		dirtyflag = true;
	});
	
	window.onbeforeunload = function() {
		if(dirtyflag) return 'You have unsaved changes';
	};
}
function save_changes_check_mrr() 
{
	// check to see if any fields change, if they do, prompt the user to save before changing pages
	$("input:not( input[searchbox],input[name='new_sub_name'],input[class='skip_save_check']),textarea:not(textarea[class='new_sub_name']), select:not(select[class='skip_save_check'])").change(function() {
		dirtyflag = true;		//
	});
	
	window.onbeforeunload = function() {
		if(dirtyflag) return 'You have unsaved changes';
	};
}

function timeout_check() 
{
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

function msgbox(msg, title) 
{
	
	if(title == undefined) title = 'Notice';
	
	$( "<div title='"+title+"'>"+msg+"</div>" ).dialog({
	     modal: true,
	     buttons: {
	     	"Okay": function() {
	    	 		$( this ).dialog( "close" );
	    	 		return true;
	     	}
	     }
	});  
	return false;   
}

function show_notice(msg) 
{
	$.noticeAdd({text: msg});
}

function update_cce_message(id,msg_type)
{
	type_msg="";
	if(msg_type==0)	type_msg="Tagline";
	if(msg_type==1)	type_msg="CCE";
	if(msg_type==2)	type_msg="Customer";
	if(msg_type==3)	type_msg="Store Location";
	
	msg_sub=$("#cce_msg_subject_"+id+"").val();
	msg_body=$("#cce_msg_body_"+id+"").val();
			
	$.ajax({
	   	type: "POST",
	   	url: "ajax.php?cmd=mrr_update_cce_message",
	   	cache:false,
	   	dataType: "xml",	   	
	   	data: {
	   		"id":id,
	   		"sub":msg_sub,
	   		"body":msg_body
	   	},
	   	error: function() {
			msgbox("General error updating "+type_msg+" Message. Please try again.");
		},
	   	
     	success: function(xml) {   		
	   		show_notice("Success - Updated "+type_msg+" Message.");  
	   			   		
	   		$("#cce_sub_"+id+"").html(msg_sub);
	   		$("#cce_msg_"+id+"").html(msg_body);
	   		
	   		//obj = '#'+$("#cce_message_editor_"+id).find('textarea').attr('id');	
	   		//console.log("Update Function: Removing tinymce: V2 " + obj);
          	//tinymce.remove(obj);  
	   		
	   		//console.log("Adding Back tinymce: V2" + obj);
			//tiny_mce_init(obj);						
	   		
	   		
	   		 	
	   		//fetch_cce_messages();	 
	   		//fetch_tagline_filler();	    			   		
	   	}
 	}); 	
}
function fetch_tagline_filler()
{
	$.ajax({
	   	type: "POST",
	   	url: "ajax.php?cmd=load_cce_tagline",
	   	cache:false,
	   	dataType: "xml",	   	
	   	data: {
	   	},
	   	error: function() {
			msgbox("General error updating CCE Tagline. Please try again.");
		},	   	
     	success: function(xml) {   		
	   		mrr_tab=$(xml).find('mrrTab').text();
	   		$("#tagline_filler").html(mrr_tab);	
	   		$('.buttonize').button();   		   		 		   		
	   	}
 	});	
}
function fetch_cce_messages()
{
	$.ajax({
	   	type: "POST",
	   	url: "ajax.php?cmd=load_cce_messages",
	   	cache:false,
	   	dataType: "xml",	   	
	   	data: {
	   	},
	   	error: function() {
			msgbox("General error updating CCE Message. Please try again.");
		},	   	
     	success: function(xml) {   		
	   		mrr_tab=$(xml).find('mrrTab').text();
	   		//console.log("reloading: " + mrr_tab);
	   		$("#cce_system_message_display").html(mrr_tab);	
	   		$('.buttonize').button();   		   		 		   		
	   	}
 	});
}

function allow_cce_message_edit(id,msg_type)
{
	type_msg="";
	if(msg_type==0)	type_msg="Tagline";
	if(msg_type==1)	type_msg="CCE";
	if(msg_type==2)	type_msg="Customer";
	if(msg_type==3)	type_msg="Store Location";
	
	obj = '#'+$("#cce_message_editor_"+id).find('textarea').attr('id');

	$( "#cce_message_editor_"+id+"" ).dialog({
		modal: true,
		inline: true,
          width: 700,
          height: 450,
          open: function() {
			
			//console.log("OPEN: Removing tinymce: V3 " + obj);
			tinymce.remove(obj);			
			//console.log($(obj).attr('aria-hidden'));			
			//console.log("OPEN: initing tinymce on : " + obj);
			tiny_mce_init(obj);
			
			$('.buttonize').button();
			/*
			$(document).on('focusin', function(e) {
			    if ($(event.target).closest(".mce-window").length) {
					e.stopImmediatePropagation();
				}
			});
			*/
          },
          close: function() {
          	//console.log("Removing tinymce: " + obj);
          	tinymce.remove(obj);
          },
          title: 'Edit '+type_msg+' Message',
          buttons: {
          	"Update Message": function() 
          	{     
          		$(obj).val(tinymce.get('cce_msg_body_' + id).getContent());
          		
          		//console.log("UPDATE: Removing tinymce: V2 " + obj);
          		//tinymce.remove(obj);
          		//tiny_mce_init(obj);
          		
          		update_cce_message(id,msg_type);
          	 	$( this ).dialog( "close" );             	 		
          	},
          	"Cancel": function() 
          	{
          		$( this ).dialog( "close" );
          	}
          }
	});  
     
}
function allow_cce_message_edit2(id)
{
	
	obj = '#'+$("#cce_message_editor_"+id).find('textarea').attr('id');

	$( "#cce_message_editor_"+id+"" ).dialog({
		modal: true,
		inline: true,
          width: 700,
          height: 450,
          open: function() {
		
			
			console.log($(obj).attr('aria-hidden'));

			$('.buttonize').button();
			
			console.log("initing tnymce on : " + obj);
			tiny_mce_init(obj);
			/*
			$(document).on('focusin', function(e) {
			    if ($(event.target).closest(".mce-window").length) {
					e.stopImmediatePropagation();
				}
			});
			*/			
          },
          close: function() {
          	console.log("Removing tinymce: " + obj);
          	tinymce.remove(obj);
          },
          title: 'Edit Tagline',
          buttons: {
          	"Update Tagline": function() 
          	{     
          		$(obj).val(tinymce.get('cce_msg_body_' + id).getContent());
          		update_cce_message(id,0);
          	 	$( this ).dialog( "close" );             	 		
          	},
          	"Cancel": function() 
          	{
          		$( this ).dialog( "close" );
          	}
          }
	});  
     
}

function tiny_mce_init(id) {
	
     tinymce.init({
     		
     	// General options
     	selector: id,
     	theme : 'modern',
     	height: 100,
          plugins: [
                   'advlist print autolink link image lists charmap preview hr anchor pagebreak spellchecker',
                   'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                   'table contextmenu directionality emoticons template paste textcolor'
             ],     
          toolbar: 'nonbreaking undo redo | styleselect | bold italic fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fullpage print | forecolor backcolor emoticons', 
          nonbreaking_force_tab: true,
          style_formats: [
                  {title: 'Bold text', inline: 'b'},
                  {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                  {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                  {title: 'Example 1', inline: 'span', styles: {color: '#00ff00'}},
                  {title: 'Example 2', inline: 'span', styles: {color: '#0000ff'}},
                  {title: 'Table styles'},
                  {title: 'Table row 1', selector: 'tr', styles: {color: '#ff0000'}}
          ]
     });
}


function show_waiting_files_status() 
{
	$.ajax({
		url: 'ajax.php?cmd=load_waiting_files',
		type: 'GET',
		cache:false,		
		dataType: 'xml',
		success: function(xml) {
			waiting_count = $(xml).find('FileEntry').length;
			
			if(waiting_count == 0) {
				$('#waiting_file_status').hide();
			} else {
				
				mrr_tab=$(xml).find('mrrTab').text();
				$('#mrr_waiting_file_display').html(mrr_tab); 
          		//$('#mrr_waiting_file_display select').selectmenu('destroy');
          		$('#mrr_waiting_file_display select').selectmenu();
          		$('#mrr_waiting_file_display .linedate').datepicker();
				
				$('#waiting_file_status').html("You have " + waiting_count + " file"+(waiting_count != 1 ? "s" : "")+" waiting to be assigned.<br><input type='button' class='buttonize btn btn-default add_new_btn' onclick='display_all_awaiting_files()' value='Assign Now'>");
				$('#waiting_file_status').show();
				$('#waiting_file_status .buttonize').button();
				$('.buttonize').button();
			}
			
			
		}
	});
}

function mrr_sel_doc_section(typeid,typename,subid,subname)
{
	$('#document_type').html(typename);
     $('#document_sub').html(subname);
     						
     $('#document_type_id_rename').val(typeid);
     $('#document_sub_id_rename').val(subid);
	
	$('#temp_sub_sel').hide();
}

function mrr_sel_doc_cust_store(custid,custname,storeid,storename)
{
	$('#display_customer_lock').html(custname);
     $('#display_store_lock').html(storename);
     						
     $('#document_cust_id_rename').val(custid);
     $('#document_store_id_rename').val(storeid);
	
	$('#cust_store_sel').hide();
}

function mrr_file_renamer(id,moder)
{
	if(!moder)		moder=0;
	
	var dialog, form,              
     dialog = $( "#dialog-form_file_rename" ).dialog({
          autoOpen: false,
		width: 'auto',
          modal: true,
          open: function() {
          	if(id > 0)
          	{
          		$.ajax({
          			url: "ajax.php?cmd=fetch_file_info",
          			dataType: "xml",
          			type: "post",
          			data: {
          				"file_id": id
          			},
          			error: function() {
          				msgbox("General error loading Document Information. Please try again");
          			},
          			success: function(xml) {
          				
          				docid=parseInt($(xml).find('DocID').text());
          				docnamer=$(xml).find('DocName').text();
          				
          				doctype=parseInt($(xml).find('DocType').text());
          				docsub=parseInt($(xml).find('DocSub').text());
          				
          				doctypename=$(xml).find('DocTypeName').text();
          				docsubname=$(xml).find('DocSubName').text();
          				
          				
          				docdate=$(xml).find('DocDate').text();
          				doccust=parseInt($(xml).find('DocCust').text());
          				docstore=parseInt($(xml).find('DocStore').text());
          				
          				doccustname=$(xml).find('DocCustName').text();
          				docstorename=$(xml).find('DocStoreName').text();
          				
          				doccustlock=parseInt($(xml).find('DocCustLock').text());
          				docstorelock=parseInt($(xml).find('DocStoreLock').text());
          				
          				doctypelist=$(xml).find('DocTypeList').text();
          				doccustlist=$(xml).find('DocCustList').text();
          				          				
          				if(docid==0)
          				{
          					msgbox("Document could not be located to rename. Please try again");
          				}
          				else
          				{
     						$('#document_id').val(docid);
     						$('#document_name').val(docnamer);
     						$('#old_doc_file_name').html(docnamer);
     						
     						$('#document_date_rename').val(docdate);
     						
     						$('#document_type').html(doctypename);
     						$('#document_sub').html(docsubname);
     						
     						$('#document_cust_id_rename').val(doccust);
     						$('#document_store_id_rename').val(docstore);
     						
     						$('#document_type_id_rename').val(doctype);
     						$('#document_sub_id_rename').val(docsub);
     						
     						$('#temp_sub_sel_box').html(doctypelist);
     						$('#temp_sub_sel').hide();
     						
     						$('#cust_store_sel_box').html(doccustlist);
     						$('#cust_store_sel').hide();
     						
     						$('#display_customer_lock').html(''+doccustname+'');
     						if(doccustlock > 0)
     						{
     								
     							$('#display_customer_lock').html('<b>Locked!</b>');    							
     						}
     						
     						$('#display_store_lock').html(''+docstorename+'');
     						if(docstorelock > 0)
     						{
     								
     							$('#display_store_lock').html('<b>Locked!</b>');    							
     						}
     						
     						
     						$('#document_date_rename').datepicker();
          				}	
          			}
          		});      	
          	}
          },
          buttons: {
          	"Okay":  function() {	rename_document(moder);	dialog.dialog( "close" );  	}
          }
     });     
     dialog.dialog( "open" );
	//$('.linedate').datepicker();
}
function rename_document(moder)
{
	id=$('#document_id').val();
     namer=$('#document_name').val();
     
     dater=$('#document_date_rename').val();
     doccust=$('#document_cust_id_rename').val();
     docstore=$('#document_store_id_rename').val();
     doctype=$('#document_type_id_rename').val();
     docsub=$('#document_sub_id_rename').val();     	
     
	$.ajax({
		url: "ajax.php?cmd=rename_document",
		dataType: "xml",
		type: "post",
		data: {
			"file_id": id,
			
			"date":dater,
			"custid":doccust,
			"storeid":docstore,
			"typeid":doctype,
			"subid":docsub,
			
			"new_name":namer
		},
		error: function() {
			msgbox("General error updating Document Information. Please try again");
		},
		success: function(xml) {			
			show_notice("Success - Renamed the Document.");	
			if(moder==1)
			{
				location.reload(); 
			}
			else
			{
				refresh_auditor2_assignment();
				refresh_auditor2_files();
			}
		}
	});
}


function display_all_awaiting_files()
{	
	var dialog, form,              
     dialog = $( "#dialog-waiting-files" ).dialog({
          autoOpen: false,
		width: 'auto',
          modal: true,
          open: function() {
          	
          	$("#dialog-waiting-files select").selectmenu('destroy');
			$("#dialog-waiting-files select").selectmenu();
          	
			$('.linedate').datepicker();
			$(".tooltip").tooltip();
          	
          },
          buttons: {
          	"Please click here to proceed":  function() {	show_waiting_files_status(); dialog.dialog( "close" );  	}
          }
     });
     
     dialog.dialog( "open" );
	//init_upload();
}

function assign_files() 
{
	txt = "<div title='Assign Files'>";
	txt += "Coming soon";
	txt += "</div>";
	$( txt ).dialog({
	     modal: true,
	     buttons: {
	     	"Close": function() {
	    	 		$( this ).dialog( "close" );
	    	 		return true;
	     	}
	     }
	});  
}

function mrr_delete_waiting_file(id)
{
	$(function() {
		$( "#dialog_delete_file" ).dialog({
			
               modal: true,
               title: 'Confirm Delete',
               buttons: {
               	"Okay": function() 
               	{     
               		$.ajax({
               		   type: "POST",
               		   url: "ajax.php?cmd=delete_attachment",
               		   data: {"id":id},
               		   error: function() {
               				msgbox("General error removing file. Please try again.");
               			},
               		   success: function(data) {               		   		
               		   		show_notice("Success - Removed the file from the list.");		
               		   }
               	 	});  
               	 	
               	 	$( this ).dialog( "close" ); 
               	 	$('#attachment_row_'+id).remove();
                    	 	
     	   			proc_cntr=parseInt($('#tot_files_processed').val());
     	   			wait_cntr=parseInt($('#tot_files_waiting').val());
     	   			
     	   			proc_cntr++;
     	   			if(proc_cntr >= wait_cntr)	
     	   			{
     	   				close_all_dialogs();
     	   				show_waiting_files_status();
     	   			}
               	},
               	"Cancel": function() 
               	{
               		$( this ).dialog( "close" );
               	}
               }
     	});  
     });
}

function mrr_update_waiting_file(id,done_flag)
{
	file_date=$("#file_"+id+"_display_date").val();
	file_name=$("#file_"+id+"_public_name").val();
	//file_access=$("#file_"+id+"_access_level").val();
	//file_user=$("#file_"+id+"_user_id").val();
	file_merchant=$("#file_"+id+"_merchant_id").val();
	file_store=$("#file_"+id+"_store_id").val();
	file_template=$("#file_"+id+"_template_id").val();	//template item (or group id if needed).
	file_sub=$("#file_"+id+"_sub_id").val();			//sub item 
	
	//if(file_sub > 0 && file_sub!=file_template)		file_template=file_sub;		//only save the actual template item (sub or not)	
	
	file_processed=done_flag;	
	
	//"access_level":file_access,
	//"user_id":file_user,
	
	//alert(file_date);
		
	$.ajax({
	   	type: "POST",
	   	url: "ajax.php?cmd=mrr_update_file_details",
	   	cache:false,
	   	async:false,
	   	dataType: "xml",	   	
	   	data: {
	   		"id":id,
	   		"processed_flag":file_processed,
	   		"display_date":file_date,
	   		"public_name":file_name,	   		
	   		"merchant_id":file_merchant,
	   		"template_id":file_template,
	   		"template_sub":file_sub,
	   		"store_id":file_store
	   	},
	   	error: function() {
			msgbox("General error updating file details. Please try again.");
		},	   	
     	success: function(xml) {   		
	   		//show_notice("Success - Updated file details.");	
	   		mrr_kill=parseInt($(xml).find('removeList').text());
	   		if(mrr_kill > 0)
	   		{
	   			$('#attachment_row_'+id).remove();  
	   			
	   			proc_cntr=parseInt($('#tot_files_processed').val());
	   			wait_cntr=parseInt($('#tot_files_waiting').val());
	   			
	   			proc_cntr++;
	   			if(proc_cntr >= wait_cntr)	close_all_dialogs();
	   		} 
	   		
	   		if(done_flag > 0) show_waiting_files_status();
	   	}
 	}); 	
}

function portlet_hide(obj) {
	
	var icon = $( obj ).find(".portlet-toggle");

	if($(icon).hasClass('ui-icon-minusthick')) {
		toggle_portlet(icon);
	}
}

function portlet_show(obj) {
	
	var icon = $( obj ).find(".portlet-toggle");
	// only fire the show event if the portlet is currently collapsed
	if($(icon).hasClass('ui-icon-plusthick')) {
		toggle_portlet(icon);
		//$(obj).removeClass('ui-icon-minusthick');
	}
}

function toggle_portlet(obj) 
{
	var icon = $( obj );
     //console.log("Current portlet class (before toggle): " + $(icon).attr('class'));
     $(icon).toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
     $(icon).closest( ".portlet" ).find( ".portlet-content" ).toggle('slow');		
     //console.log("Current portlet class (after toggle): " + $(icon).attr('class'));
}
  
$().ready(function() {
		
	$( ".column" ).sortable({
	      connectWith: ".column",
	      handle: ".portlet-header",
	      cancel: ".portlet-toggle",
	      placeholder: "portlet-placeholder ui-corner-all"
	});
	 
	$('.portlet_header').click(function() {
	 		//console.log('clicked');
	 		$(this).find('input').focus();
	});
	$( ".portlet" )
	      .addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
	      .find( ".portlet-header" )
	        .addClass( "ui-widget-header ui-corner-all" )
	        .prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");

		$('.no_collapse').find('span.ui-icon').remove();
	 
	$( ".portlet-toggle" ).click(function() {
	    		toggle_portlet($(this));
	});
		
	$('.default_closed .portlet-toggle').each(function() {
			toggle_portlet($(this));
	});
		
	//$('#search_universal').focus();
});



function display_uploaded_files(user,section,field_name)
{     	
	$("#uploaded_files_section").html('');
	
	$.ajax({
		url: "ajax.php?cmd=display_attachments",
		data: {
			'xref_id':user,
			'section_id':section,
			'mode':1,
			},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			
			mrr_tab=$(xml).find('mrrTab').text();
			$(""+field_name+"").html(mrr_tab);
		}
	});	
}
function show_user_image(user_id,field_name) 
{
	$.ajax({
		url: "ajax.php?cmd=get_user_image",
		type: "post",
		dataType: "xml",
		data: {
			"user_id": user_id
		},
		success: function(xml) 
		{				
			
			$(""+field_name+"").css('background-image', "url('"+$(xml).find('File').text()+"')");
		}
	});
}
function show_user_cert(user_id,field_name) 
{
	$.ajax({
		url: "ajax.php?cmd=get_user_cert",
		type: "post",
		dataType: "xml",
		data: {
			"user_id": user_id,
			"fldname":field_name
		},
		success: function(xml) 
		{	
			$(""+field_name+"").css('background-image', "url('"+$(xml).find('File').text()+"')");
		}
	});
}
function show_user_cert2(user_id,field_name) 
{
	$.ajax({
		url: "ajax.php?cmd=get_user_cert",
		type: "post",
		dataType: "xml",
		data: {
			"user_id": user_id
		},
		success: function(xml) 
		{	
			location.reload(); 
			//$(""+field_name+"").css('background-image', "url('"+$(xml).find('File').text()+"')");
		}
	});
}

//popup form functions...
function checkRegexp( o, regexp, n ) 
{
     if ( !( regexp.test( o.val() ) ) ) 
     {
     	o.addClass( "ui-state-error" );
     	//updateTips( n );
     	return false;
     } 
     else 
     {
     	return true;
     }
}

function checkLength( o, n, min, max ) 
{
	
     if ( o.val().length > max || o.val().length < min ) 
     {
     	o.addClass( "ui-state-error" );
     	msgbox("Length of " + n + " must be between " + min + " and " + max + ".");
     	return false;
     } 
     else 
     {
     	return true;
     }
}  
function go_home()
{
	window.location.href = "/";
}

function set_email_view_log(id)
{     	
	$.ajax({
			url: "ajax.php?cmd=set_email_view_log",
			data: {
				"email_id":0,
   				"file_id": id 				
				},
			type: "POST",
			async: false,
			cache:false,
			dataType: "xml",
			error: function() {
				//msgbox("General error updating template. Please try again.");
			},	
			success: function(xml) {     				         				
     			//msgbox("General error updating template. Please try again.");          			
			}
	});
}

function close_all_dialogs() {
	$(".ui-dialog-content").dialog("close");
}



//dynamic custom file uploader
function get_file_uploader(fieldname,labeler,sect,id,call_back)
{	//create custom file uploader with variable components for Ajax calls...mainly so uploader can group files with proper XREF_ID based on SECTION_ID.
	$.ajax({
			url: "ajax.php?cmd=get_file_uploader",
			data: {
				"field_name":fieldname,
				"label":labeler,
				"call_back":call_back,
				"section_id":sect,
   				"xref_id": id 				
				},
			type: "POST",
			cache:false,
			error: function() {
				msgbox("General error generating file uploader... Please try again.");
			},	
			success: function(data) {
	   			$('#'+fieldname+'_holder').html(data);
	   			init_upload();
				//$("select").selectmenu().selectmenu('menuWidget').addClass('overflow');
	   		}
	});
}


//important dates
function load_important_dates()
{
	$.ajax({
		url: "ajax.php?cmd=load_important_dates",
		data: {		
			},
		type: "POST",
		cache:false,
		dataType : 'xml',
		success : function(xml) {	
				
			mrr_tab=$(xml).find('mrrTab').text();					
			$('#important_dates').html(mrr_tab);			
			$('.buttonize').button();
		}
	});		
}
function cancelDate()
{
	load_important_dates();  
    	close_all_dialogs();
}
function updateDate() 
{
     var valid = true;
     
     val0=tinymce.get('date_desc').getContent();
     val1=tinymce.get('date_msg_remind1').getContent();
     val2=tinymce.get('date_msg_remind2').getContent();
     
     //alert('Box 0='+val0+', Box 1='+val1+' Box 2='+val2+'.');     
     
     if ( valid ) 
     { 	
     	$.ajax({
			url: "ajax.php?cmd=update_important_date",
			data: {
				'id':$('#date_id').val(),
				'date':$('#date_date').val(),
				'title':$('#date_title').val(),
				'msg':val0,
				'type':$('#date_type').val(),
				'date_remind1':$('#date_date_remind1').val(),
				'date_remind2':$('#date_date_remind2').val(),
				'email_remind1':$('#date_email_remind1').val(),
				'email_remind2':$('#date_email_remind2').val(),
				'msg_remind1':val1,
				'msg_remind2':val2     				
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			error: function() {
     			msgbox("General error updating important date. Please try again");
     		},
			success: function(xml) {     
				show_notice('Important Date Updated.');
				load_important_dates();
				cancelDate();
			}
		});			
     }
     return valid;
}
function get_important_date_details(id)
{
	$.ajax({
		url: "ajax.php?cmd=get_important_date_details",
		type: "post",
		dataType: "xml",
		data: {
			'id':id			
		},
		error: function() {
			msgbox("General error retrieving important date details. Please try again");
		},
		success: function(xml) {
			if($(xml).find('id').text() == '0')	
			{
				show_notice('Could not locate Important Date settings....');
			}
			else
			{
				$('#date_id').val( $(xml).find('id').text() );
				$('#date_date').val( $(xml).find('Date').text() );
				$('#date_title').val( $(xml).find('Sub').text() );
				$('#date_desc').val( $(xml).find('Desc').text() );
				$('#date_type').val( parseInt($(xml).find('Type').text()) );
				$('#date_date_remind1').val( $(xml).find('Rem1Date').text() );
				$('#date_date_remind2').val( $(xml).find('Rem2Date').text() );
				$('#date_email_remind1').val( $(xml).find('Rem1Email').text() );
				$('#date_email_remind2').val( $(xml).find('Rem2Email').text() );
				$('#date_msg_remind1').val( $(xml).find('Rem1Msg').text() );
				$('#date_msg_remind2').val( $(xml).find('Rem2Msg').text() );    		
				
				
     			//$rval.="<arch><![CDATA[".$row['archived']."]]></arch>";
     			//$rval.="<user><![CDATA[".$row['user_id']."]]></user>";
     			//$rval.="<added><![CDATA[".$row['linedate_added']."]]></added>";
     			//$rval.="<deleted><![CDATA[".$row['deleted']."]]></deleted>";
     			
				tiny_mce_init('#date_desc');
     			tiny_mce_init('#date_msg_remind1');
     			tiny_mce_init('#date_msg_remind2');
			}
		}
	});		
}	
function edit_important_date(id,moder)
{
	if(moder==2)
	{
		$( "#dialog_delete_date" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{                        
                         $.ajax({
               			url: "ajax.php?cmd=delete_important_date",
               			type: "post",
               			dataType: "xml",
               			data: {
               				"id":id
               			},
               			error: function() {
               				msgbox("General error removing important date. Please try again");
               			},
               			success: function(xml) 
               			{				
               				show_notice('Important Date has been removed.');   
               				load_important_dates();
               			}
               		});       
               		$( this ).dialog( "close" );      	 		
               	},
               	"Cancel": function() 
               	{
               		$( this ).dialog( "close" );
               	}
               }
          });  
	}
	if(moder==1)
	{				
		$( "#dialog_date_form" ).dialog({
			width: 'auto',
			modal: true,
			close: function() {
				tinymce.remove('#date_desc');
				tinymce.remove('#date_msg_remind1');
				tinymce.remove('#date_msg_remind2');
			},
			open: function() {
				$('.buttonize').button();
               	$('.datepicker' ).datepicker();
     			//$('select').selectmenu();
     			$('#date_type').selectmenu('destroy');
     			$('#date_type').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			$('.tooltip').tooltip();
     			     			
     			get_important_date_details(id);
			},
               buttons: {
               	"Save Date": function() 
               	{
               		updateDate();
               		$( this ).dialog( "close" );
               	},
               	"Cancel": function() 
               	{
               		cancelDate();
               		$( this ).dialog( "close" );
               	}
               }
		});
	}	
	if(moder==3)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_important_date",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":1
     			},
     			error: function() {
     				msgbox("General error archiving important date. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('Important Date has been archived.');   
     				load_important_dates();
     			}
     		}); 	
	}
	if(moder==4)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_important_date",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":0
     			},
     			error: function() {
     				msgbox("General error un-archiving important date. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('Important Date has been un-archived.');   
     				$('.unarchive_date_'+id+'').hide();
     				load_important_dates();
     			}
     		}); 	
	}		
}

//merchants
function load_merchant_program()
{
	$.ajax({
		url: "ajax.php?cmd=display_merchant_program",
		data: {		
			},
		type: "POST",
		cache:false,
		dataType : 'xml',
		success : function(xml) {	
				
			mrr_tab=$(xml).find('mrrTab').text();					
			//$('.company_name_holder').html(mrr_tab);	
			$('.banner_txt').html(mrr_tab);	
		}
	});		
}
function load_merchants()
{
	$.ajax({
		url: "ajax.php?cmd=load_merchants",
		data: {		
			},
		type: "POST",
		cache:false,
		dataType : 'xml',
		success : function(xml) {	
				
			mrr_tab=$(xml).find('mrrTab').text();					
			$('#merchant_customers').html(mrr_tab);	
			$('#merchant_customers').show();			
			$('.buttonize').button();
			
			//$('.tablesorter').tablesorter();
		}
	});		
}
function cancelMerchant()
{
	load_merchants();  
	close_all_dialogs(); 
}
function updateMerchant()
{
	var valid = true;
	
     if ( valid ) 
     {
     	$.ajax({
			url: "ajax.php?cmd=update_merchant",
			data: {    				
				'id':$('#ms_id').val(),
				'merchant':$('#ms_merchant').val(),
				'address1':$('#ms_address1').val(),
				'address2':$('#ms_address2').val(),
				'city':$('#ms_city').val(),
				'state':$('#ms_state').val(),
				'zip':$('#ms_zip').val(),
				'program':$('#ms_program_title').val(),
				'subtitle':$('#ms_program_subtitle').val(),
				
				'title':$('#ms_contact_title').val(),
				'first':$('#ms_contact_first_name').val(),
				'last':$('#ms_contact_last_name').val(),
				'phone1':$('#ms_contact_phone1').val(),
				'phone2':$('#ms_contact_phone2').val(),
				'email':$('#ms_contact_email').val(),
				
				'msb_auditor':$('#ms_msb_name').val(),
				'msb_ref_number':$('#ms_msb_ref').val(),
				'msb_cell':$('#ms_msb_cell').val(),
				'msb_phone':$('#ms_msb_phone').val(),
				'msb_email':$('#ms_msb_email').val(),
				'msb_addr':$('#ms_msb_addr').val(),
				
				'irs_addr':$('#ms_irs_addr').val(),
				'irs_agent':$('#ms_irs_agent').val(),
				'irs_empid':$('#ms_irs_employ_id').val(),
				'irs_email':$('#ms_irs_email').val(),
				'irs_phone':$('#ms_irs_phone').val(),
				'irs_cell':$('#ms_irs_cell').val(),
				'irs_case':$('#ms_irs_case').val(),
								
				'phone3':$('#ms_contact_phone3').val(),
				'phone4':$('#ms_contact_phone4').val(),
				
				'logo':$('#ms_logo').val(),
				
				'co_user_id':$('#ms_co_user_id').val(),
				'grp_user_id':$('#ms_grp_user_id').val(),
				
				'parent_id':$('#ms_parent_id').val(),
				'template_id':$('#ms_template_id').val()			
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			error: function() {
     			msgbox("General error updating Customer. Please try again");
     		},
			success: function(xml) {     
				show_notice('Customer Updated.'); 	
				cancelMerchant();   
				update_bread_crumb_trail();						
			}
		});	
     }
     return valid;
}
function updateMerchant_auto()
{
	var valid = true;
	
     if ( valid ) 
     {
     	$.ajax({
			url: "ajax.php?cmd=update_merchant",
			data: {    				
				'id':$('#ms_id').val(),
				'merchant':$('#ms_merchant').val(),
				'address1':$('#ms_address1').val(),
				'address2':$('#ms_address2').val(),
				'city':$('#ms_city').val(),
				'state':$('#ms_state').val(),
				'zip':$('#ms_zip').val(),
				'program':$('#ms_program_title').val(),
				'subtitle':$('#ms_program_subtitle').val(),
				
				'title':$('#ms_contact_title').val(),
				'first':$('#ms_contact_first_name').val(),
				'last':$('#ms_contact_last_name').val(),
				'phone1':$('#ms_contact_phone1').val(),
				'phone2':$('#ms_contact_phone2').val(),
				'email':$('#ms_contact_email').val(),
				
				'msb_auditor':$('#ms_msb_name').val(),
				'msb_ref_number':$('#ms_msb_ref').val(),
				'msb_cell':$('#ms_msb_cell').val(),
				'msb_phone':$('#ms_msb_phone').val(),
				'msb_email':$('#ms_msb_email').val(),
				'msb_addr':$('#ms_msb_addr').val(),
				
				'irs_addr':$('#ms_irs_addr').val(),
				'irs_agent':$('#ms_irs_agent').val(),
				'irs_empid':$('#ms_irs_employ_id').val(),
				'irs_email':$('#ms_irs_email').val(),
				'irs_phone':$('#ms_irs_phone').val(),
				'irs_cell':$('#ms_irs_cell').val(),
				'irs_case':$('#ms_irs_case').val(),
								
				'phone3':$('#ms_contact_phone3').val(),
				'phone4':$('#ms_contact_phone4').val(),
				
				'logo':$('#ms_logo').val(),
				
				'co_user_id':$('#ms_co_user_id').val(),
				'grp_user_id':$('#ms_grp_user_id').val(),
				
				'parent_id':$('#ms_parent_id').val(),
				'template_id':$('#ms_template_id').val()			
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			error: function() {
     			msgbox("General error updating Customer. Please try again");
     		},
			success: function(xml) {     
				
				//show_notice('Customer Updated.'); 	
				//cancelMerchant();
				new_merch_id=parseInt($(xml).find('rslt').text());
				if(new_merch_id==0)
				{
					msgbox("General error updating Customer to add user. Please try again");
				}
				else
				{
					$('#ms_id').val( new_merch_id );
					update_bread_crumb_trail();	
				}				
			}
		});	
     }
     return valid;
}


function edit_merchant(id,moder)
{
	//if(id==0)		id=parseInt($('#bct_merchant_id').html());		//only get selected...  			parseInt($('#bct_store_id').html();
	
	if(moder==2)
	{
		$( "#dialog_delete_merchant" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{                        
                         $.ajax({
               			url: "ajax.php?cmd=delete_merchant",
               			type: "post",
               			dataType: "xml",
               			data: {
               				"id":id
               			},
               			error: function() {
               				msgbox("General error removing Customer. Please try again");
               			},
               			success: function(xml) 
               			{				
               				show_notice('Customer has been removed.');   
               				load_merchants();
               				update_bread_crumb_trail();	
               			}
               		});       
               		$( this ).dialog( "close" );      	 		
               	},
               	"Cancel": function() 
               	{
               		$( this ).dialog( "close" );
               	}
               }
          });  
	}		
	if(moder==1)
	{				
		$( "#dialog_merchant_form" ).dialog({
			width: 'auto',
			modal: true,
			open: function() {
				$('.buttonize').button();
               	$('.datepicker' ).datepicker();
               	
     			$('#ms_state').selectmenu('destroy');
     			$('#ms_state').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			$('#ms_template_id').selectmenu('destroy');
     			$('#ms_template_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			$('#ms_parent_id').selectmenu('destroy');
     			$('#ms_parent_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			$('.tooltip').tooltip();
     			
     			get_merchant_details(id);
     			
     			if(id==0)
     			{
     				$('#ms_co_user_id').val('0');     				
     				$('#ms_grp_user_id').val('0');  	
     				$('#ms_id').val('0');
     				show_logo_image(0, "#logo_image_holder" );
     			}
     			
			},
               buttons: {
               	"Update Customer": function() 
               	{
               		updateMerchant();
               		update_bread_crumb_trail();	
               		$( this ).dialog( "close" );
               	},
               	"Cancel": function() 
               	{
               		cancelMerchant();
               		$( this ).dialog( "close" );
               	}
               }
		});
	}	
	if(moder==3)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_merchant",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":1
     			},
     			error: function() {
     				msgbox("General error archiving Customer. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('Customer has been archived.');   
     				load_merchants();
     				update_bread_crumb_trail();	
     			}
     		}); 	
	}	
	if(moder==4)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_merchant",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":0
     			},
     			error: function() {
     				msgbox("General error un-archiving Customer. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('Customer has been un-archived.');
     				$('.unarchive_merchant_'+id+'').hide();
     				   
     				load_merchants();
     				update_bread_crumb_trail();	
     			}
     		}); 	
	}		
} 

function copy_store_location_from_custom()
{
	id=parseInt($('#bct_merchant_id').html());
		
	$.ajax({
		url: "ajax.php?cmd=get_merchant_details",
		type: "post",
		dataType: "xml",
		data: {
			'id':id			
		},
		error: function() {
			msgbox("General error retrieving Customer details to copy. Please try again");
		},
		success: function(xml) {
			if($(xml).find('id').text() == '0')	
			{
				show_notice('Could not locate Customer settings to copy....');
			}
			else
			{
				$('#mst_store_name').val( $(xml).find('Merchant').text()   );
				$('#mst_address1').val( $(xml).find('Addr1').text() );
				$('#mst_address2').val( $(xml).find('Addr2').text() );
				$('#mst_city').val( $(xml).find('City').text() );
				$('#mst_state').val( $(xml).find('State').text() );
				$('#mst_zip').val( $(xml).find('Zip').text() );
												
				$('#mst_contact_title').val( $(xml).find('Title').text() );
				$('#mst_contact_first_name').val( $(xml).find('First').text() );
				$('#mst_contact_last_name').val( $(xml).find('Last').text() );
				$('#mst_contact_phone1').val( $(xml).find('Phone1').text() );
				$('#mst_contact_phone2').val( $(xml).find('Phone2').text() );
				$('#mst_contact_phone3').val( $(xml).find('Phone3').text() );
				$('#mst_contact_phone4').val( $(xml).find('Phone4').text() );
				$('#mst_contact_email').val( $(xml).find('Email').text() );
				
				$('#mst_msb_name').val( $(xml).find('MSBname').text() );
				$('#mst_msb_ref').val( $(xml).find('MSBref').text() );
				$('#mst_msb_cell').val( $(xml).find('MSBcell').text() );
				$('#mst_msb_phone').val( $(xml).find('MSBphone').text() );
				$('#mst_msb_email').val( $(xml).find('MSBemail').text() );	
				$('#mst_msb_addr').val( $(xml).find('MSBaddress').text() );
				
				$('#mst_irs_addr').val( $(xml).find('IRSaddress').text() );
				$('#mst_irs_agent').val( $(xml).find('IRSname').text() );
				$('#mst_irs_employ_id').val( $(xml).find('IRSref').text() );
				$('#mst_irs_email').val( $(xml).find('IRSemail').text() );
				$('#mst_irs_phone').val( $(xml).find('IRSphone').text() );
				$('#mst_irs_cell').val( $(xml).find('IRScell').text() );
				$('#mst_irs_case').val( $(xml).find('IRScase').text() );
			}
		}
	});	
	
} 
function quick_fill_program_info()
{
	if($('#ms_merchant').val()!="")
	{	
		if($('#ms_program_title').val()=="")		$('#ms_program_title').val(  $('#ms_merchant').val()  );
		if($('#ms_program_subtitle').val()=="")		$('#ms_program_subtitle').val( 'AML Compliance Portal' );	
	}
}


function get_merchant_details(id)
{
	$.ajax({
		url: "ajax.php?cmd=get_merchant_details",
		type: "post",
		dataType: "xml",
		data: {
			'id':id			
		},
		error: function() {
			msgbox("General error retrieving Customer details. Please try again");
		},
		success: function(xml) {
			if($(xml).find('id').text() == '0')	
			{
				show_notice('Could not locate Customer settings....');
			}
			else
			{
				$('#ms_id').val( $(xml).find('id').text() );
				$('#ms_merchant').val( $(xml).find('Merchant').text() );
				$('#ms_parent_id').val( parseInt($(xml).find('ParentID').text()) );
				$('#ms_address1').val( $(xml).find('Addr1').text() );
				$('#ms_address2').val( $(xml).find('Addr2').text() );
				$('#ms_city').val( $(xml).find('City').text() );
				$('#ms_state').val( $(xml).find('State').text() );
				$('#ms_zip').val( $(xml).find('Zip').text() );
				
				$('#ms_program_title').val( $(xml).find('ProgramTitle').text() );
				$('#ms_program_subtitle').val( $(xml).find('ProgramSubtitle').text() );
												
				$('#ms_contact_title').val( $(xml).find('Title').text() );
				$('#ms_contact_first_name').val( $(xml).find('First').text() );
				$('#ms_contact_last_name').val( $(xml).find('Last').text() );
				$('#ms_contact_phone1').val( $(xml).find('Phone1').text() );
				$('#ms_contact_phone2').val( $(xml).find('Phone2').text() );
				$('#ms_contact_phone3').val( $(xml).find('Phone3').text() );
				$('#ms_contact_phone4').val( $(xml).find('Phone4').text() );
				$('#ms_contact_email').val( $(xml).find('Email').text() );
				
				$('#ms_logo').val( $(xml).find('Logo').text() );
				$('#ms_template_id').val( parseInt($(xml).find('Template').text()) );
				
				$('#ms_co_user_id').val( parseInt($(xml).find('COuser').text()) );
				$('#ms_grp_user_id').val( parseInt($(xml).find('Groupuser').text()) );
				
				$('#ms_msb_name').val( $(xml).find('MSBname').text() );
				$('#ms_msb_ref').val( $(xml).find('MSBref').text() );
				$('#ms_msb_cell').val( $(xml).find('MSBcell').text() );
				$('#ms_msb_phone').val( $(xml).find('MSBphone').text() );
				$('#ms_msb_email').val( $(xml).find('MSBemail').text() );	
				$('#ms_msb_addr').val( $(xml).find('MSBaddress').text() );
				
				$('#ms_irs_addr').val( $(xml).find('IRSaddress').text() );
				$('#ms_irs_agent').val( $(xml).find('IRSname').text() );
				$('#ms_irs_employ_id').val( $(xml).find('IRSref').text() );
				$('#ms_irs_email').val( $(xml).find('IRSemail').text() );
				$('#ms_irs_phone').val( $(xml).find('IRSphone').text() );
				$('#ms_irs_cell').val( $(xml).find('IRScell').text() );
				$('#ms_irs_case').val( $(xml).find('IRScase').text() );
				
				$('#dialog_merchant_form select').selectmenu('destroy');
				$('#dialog_merchant_form select').selectmenu();
				
				get_file_uploader('logo_image_holder','Customer Logo',9, $(xml).find('id').text() ,'show_logo_image');
				
				show_logo_image($(xml).find('id').text(), "#logo_image_holder" );
				update_bread_crumb_trail();
				
				$('#cust_display_portlet').html('');
				$('#store_display_portlet').html('');
				//$('#user_display_portlet').html('');
				
				init_upload();
				
				$('#ms_template_id').selectmenu('destroy');
     			$('#ms_template_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
				
				$('#ms_parent_id').selectmenu('destroy');
     			$('#ms_parent_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			$('#ms_state').selectmenu('destroy');
     			$('#ms_state').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			     			  			
				$('#ms_co_user_id').selectmenu('destroy');
     			$('#ms_co_user_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
				
				$('#ms_grp_user_id').selectmenu('destroy');
     			$('#ms_grp_user_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			if(id==0)
     			{     			
     				load_dynamic_user_select('#ms_co_user_id_box','ms_co_user_id',-1,0,'Select Compliance Officer','');
          			load_dynamic_user_select('#ms_grp_user_id_box','ms_grp_user_id',-1,0,'Select Group Manager','');
     			} 
     			
				
				//$("select").selectmenu().selectmenu('menuWidget').addClass('overflow');
				
				load_user_list();
			}
		}
	});	
} 
function get_merchant_details_display(id)
{	//this function displays a customer info section based on selection...
	//if(isNaN(id)) return;
	$('#cust_cid').html('&nbsp;');
	$('#cust_template').html('&nbsp;');
	$('#cust_title').html('&nbsp;');
	$('#cust_subtitle').html('&nbsp;');
	$('#cust_name').html('&nbsp;');
	$('#cust_addr1').html('&nbsp;');
	//$('#cust_addr2').html('&nbsp;');
	$('#cust_city').html('&nbsp;');
	$('#cust_state').html('&nbsp;');
	$('#cust_zip').html('&nbsp;');
	$('#cust_phone').html('&nbsp;');
	$('#cust_fax').html('&nbsp;');
	
	$('#cust_logo').attr('src','images/no-profile-image.png');
	//$('#cust_edit_logo').attr('href',);
	
	$('#cust_co').html('&nbsp;');
	$('#cust_pass').html('&nbsp;');
	$('#cust_email').html('&nbsp;');
	$('#cust_co_image').attr("src", 'images/no-profile-image.png');
	//$('#cust_edit_photo').attr('href',);
	
	$('#sidebar_company_logo').attr('src','images/no-profile-image.png');
	
	$('#merchant_edit_button').html('');
	
	
	if(id > 0)
	{
     	$.ajax({
     		url: "ajax.php?cmd=get_merchant_details",
     		type: "post",
     		dataType: "xml",
     		data: {
     			'id':id			
     		},
     		error: function() {
     			msgbox("General error retrieving Customer details. Please try again");
     		},
     		success: function(xml) {
     			if($(xml).find('id').text() == '0')	
     			{
     				show_notice('Could not locate Customer settings....');
     			}
     			else
     			{				
     				$('#cust_cid').html($(xml).find('id').text());
     				$('#cust_template').html($(xml).find('TemplateName').text());
     				$('#cust_title').html($(xml).find('ProgramTitle').text());
     				$('#cust_subtitle').html($(xml).find('ProgramSubtitle').text());
     				$('#cust_name').html($(xml).find('Merchant').text());
     				$('#cust_addr1').html($(xml).find('Addr1').text());
     				//$('#cust_addr2').html($(xml).find('Addr2').text());
     				$('#cust_city').html($(xml).find('City').text());
     				$('#cust_state').html($(xml).find('State').text());
     				$('#cust_zip').html($(xml).find('Zip').text());
     				$('#cust_phone').html($(xml).find('Phone3').text());
     				$('#cust_fax').html($(xml).find('Phone4').text());
     				
     				if($(xml).find('Logo').text() == '') {
     					$('#cust_logo').attr('src', 'images/no-profile-image.png');
     				} else {
     					$('#cust_logo').attr('src',$(xml).find('Logo').text());
     				}
     				//$('#cust_edit_logo').attr('href',);
     				
     				$('#sidebar_company_logo').attr('src',$(xml).find('Logo').text());
     				
     				$('#cust_co').html($(xml).find('COuserName').text());
     				
     				$('#cust_cell').html($(xml).find('COuserCell').text());
     				$('#cust_phone2').html($(xml).find('COuserPhone').text());
     				
     				$('#cust_pass').html('**********');
     				$('#cust_email').html($(xml).find('COuserEmail').text());
     				$('#cust_co_image').attr("src", $(xml).find('COuserImage').text());
     				$('#cust_co_image').attr("alt", $(xml).find('COuserImage').text());
     				//$('#cust_edit_photo').attr('href',);
     				
     				$('#merchant_edit_button').html($(xml).find('EditButton').text());
     				     				
					init_upload();
					$("select").selectmenu().selectmenu('menuWidget').addClass('overflow');
					
					load_user_list();
     			}
     		}
     	});	
	}
} 

function show_store_image(store_id,field_name) 
{
	//if(store_id==0)		store_id=parseInt($('#mst_id').val());
	
	$.ajax({
		url: "ajax.php?cmd=get_store_image",
		type: "post",
		dataType: "xml",
		data: {
			"store_id": store_id
		},
		success: function(xml) 
		{				
			mrr_use_file=$(xml).find('File').text();
			if(store_id == 0)	mrr_use_file="images/no-profile-image.png";	
			
			$(""+field_name+"").css('background-image', "url('"+mrr_use_file+"')");
			//if($(xml).find('rslt').text()=="1")		$('#ms_logo').val(''+mrr_use_file+'');
		}
	});
}

function show_logo_image(merchant_id,field_name) 
{
	//if(merchant_id==0)		merchant_id=parseInt($('#ms_id').val());
	
	$.ajax({
		url: "ajax.php?cmd=get_logo_image",
		type: "post",
		dataType: "xml",
		data: {
			"merchant_id": merchant_id
		},
		success: function(xml) 
		{				
			mrr_use_file=$(xml).find('File').text();
			if(merchant_id == 0)	mrr_use_file="images/no-profile-image.png";	
			
			$(""+field_name+"").css('background-image', "url('"+mrr_use_file+"')");
			if($(xml).find('rslt').text()=="1")		$('#ms_logo').val(''+mrr_use_file+'');
		}
	});
}


//store locations
function load_stores()
	{
		$.ajax({
			url: "ajax.php?cmd=load_stores",
			data: {		
				},
			type: "POST",
			cache:false,
			dataType : 'xml',
			success : function(xml) {	
					
				mrr_tab=$(xml).find('mrrTab').text();					
				$('#store_locations').html(mrr_tab);			
				$('#store_locations').show();	
				$('.buttonize').button();
			}
		});		
	}
function cancelStore()
{
	load_stores();  
	close_all_dialogs(); 
}
function updateStore()
{
	var valid = true;
	
     if ( valid ) 
     {
     	$.ajax({
			url: "ajax.php?cmd=update_store_location",
			data: {    				
				'id':$('#mst_id').val(),
				'store_name':$('#mst_store_name').val(),
				'store_number':$('#mst_store_number').val(),
				'merchant_id':$('#mst_merchant_id').val(),
				'address1':$('#mst_address1').val(),
				'address2':$('#mst_address2').val(),
				'city':$('#mst_city').val(),
				'state':$('#mst_state').val(),
				'zip':$('#mst_zip').val(),
				'title':$('#mst_contact_title').val(),
				'first':$('#mst_contact_first_name').val(),
				'last':$('#mst_contact_last_name').val(),
				'phone1':$('#mst_contact_phone1').val(),
				'phone2':$('#mst_contact_phone2').val(),
				'phone3':$('#mst_contact_phone3').val(),
				'phone4':$('#mst_contact_phone4').val(),
				'email':$('#mst_contact_email').val(),
				
				'msb_auditor':$('#mst_msb_name').val(),
				'msb_ref_number':$('#mst_msb_ref').val(),
				'msb_cell':$('#mst_msb_cell').val(),
				'msb_phone':$('#mst_msb_phone').val(),
				'msb_email':$('#mst_msb_email').val(),
				'msb_addr':$('#mst_msb_addr').val(),
				
				'irs_addr':$('#mst_irs_addr').val(),
				'irs_agent':$('#mst_irs_agent').val(),
				'irs_empid':$('#mst_irs_employ_id').val(),
				'irs_email':$('#mst_irs_email').val(),
				'irs_phone':$('#mst_irs_phone').val(),
				'irs_cell':$('#mst_irs_cell').val(),
				'irs_case':$('#mst_irs_case').val(),
				
				'cm_user_id': $('#mst_cm_user_id').val(),
				
				'template_id':$('#mst_template_id').val()			
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			error: function() {
     			msgbox("General error updating store location. Please try again");
     		},
			success: function(xml) {     
				show_notice('Store Location Updated.'); 	
				cancelStore();  	
				update_bread_crumb_trail();					
			}
		});	
     }
     return valid;
}

function updateStore_auto()
{
	var valid = true;
	
     if ( valid ) 
     {
     	$.ajax({
			url: "ajax.php?cmd=update_store_location",
			data: {    				
				'id':$('#mst_id').val(),
				'store_name':$('#mst_store_name').val(),
				'store_number':$('#mst_store_number').val(),
				'merchant_id':$('#mst_merchant_id').val(),
				'address1':$('#mst_address1').val(),
				'address2':$('#mst_address2').val(),
				'city':$('#mst_city').val(),
				'state':$('#mst_state').val(),
				'zip':$('#mst_zip').val(),
				'title':$('#mst_contact_title').val(),
				'first':$('#mst_contact_first_name').val(),
				'last':$('#mst_contact_last_name').val(),
				'phone1':$('#mst_contact_phone1').val(),
				'phone2':$('#mst_contact_phone2').val(),
				'phone3':$('#mst_contact_phone3').val(),
				'phone4':$('#mst_contact_phone4').val(),
				'email':$('#mst_contact_email').val(),
				
				'msb_auditor':$('#mst_msb_name').val(),
				'msb_ref_number':$('#mst_msb_ref').val(),
				'msb_cell':$('#mst_msb_cell').val(),
				'msb_phone':$('#mst_msb_phone').val(),
				'msb_email':$('#mst_msb_email').val(),
				'msb_addr':$('#mst_msb_addr').val(),
				
				'irs_addr':$('#mst_irs_addr').val(),
				'irs_agent':$('#mst_irs_agent').val(),
				'irs_empid':$('#mst_irs_employ_id').val(),
				'irs_email':$('#mst_irs_email').val(),
				'irs_phone':$('#mst_irs_phone').val(),
				'irs_cell':$('#mst_irs_cell').val(),
				'irs_case':$('#mst_irs_case').val(),
				
				'cm_user_id': $('#mst_cm_user_id').val(),
				
				'template_id':$('#mst_template_id').val()			
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			error: function() {
     			msgbox("General error updating store location. Please try again");
     		},
			success: function(xml) {     
				//show_notice('Store Location Updated.'); 	
				//cancelStore();  
				new_store_id=parseInt($(xml).find('rslt').text());
				if(new_store_id==0)
				{
					msgbox("General error updating store location to add user. Please try again");
				}
				else
				{
					$('#mst_id').val( new_store_id );
					//update_bread_crumb_trail();
				}									
			}
		});	
     }
     return valid;
}


function edit_store_location(id,moder)
{
	//if(id==0)		id=parseInt($('#bct_store_id').html());		//only get selected...  			
	
	if(moder==2)
	{
		$( "#dialog_delete_store_location" ).dialog({
               modal: true,
               buttons: {
               	"Okay": function() 
               	{    
                         $.ajax({
               			url: "ajax.php?cmd=delete_store_location",
               			type: "post",
               			dataType: "xml",
               			data: {
               				"id":id
               			},
               			error: function() {
               				msgbox("General error removing store location. Please try again");
               			},
               			success: function(xml) 
               			{				
               				show_notice('Store location has been removed.');   
               				load_stores();
               				update_bread_crumb_trail();	
               			}
               		});       
               		$( this ).dialog( "close" );      	 		
               	},
               	"Cancel": function() 
               	{
               		$( this ).dialog( "close" );
               	}
               }
          });  
	}
	if(moder==1)
	{			
		$( "#dialog_store_form" ).dialog({
			width: 'auto',
			modal: true,
			open: function() {
				$('.buttonize').button();
               	$('.datepicker' ).datepicker();
               	
     			$('#mst_state').selectmenu('destroy');
     			$('#mst_state').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			$('#mst_template_id').selectmenu('destroy');
     			$('#mst_template_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			$('.tooltip').tooltip();
     			
     			get_store_location_details(id);
     			
     			
     			if(id==0)
     			{
     				$('#mst_cm_user_id').val('0');  	
     				$('#mst_id').val('0');
     				show_store_image(0, "#logo_image_holder" );
     			}
     			/*
     			"Copy Customer Information": function() 
               	{
               		copy_store_location_from_custom(); 
               	},
     			*/
			},
               buttons: {
               	
               	"Update Store": function() 
               	{
               		updateStore();
               		update_bread_crumb_trail();	
               		$( this ).dialog( "close" );
               	},
               	
               	"Cancel": function() 
               	{
               		cancelStore();
               		$( this ).dialog( "close" );
               	}
               }
		});
	}	
	if(moder==3)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_store_location",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":1
     			},
     			error: function() {
     				msgbox("General error archiving store location. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('Store Location has been archived.');   
     				load_stores();
     				update_bread_crumb_trail();	
     			}
     		}); 	
	}
	if(moder==4)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_store_location",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":0
     			},
     			error: function() {
     				msgbox("General error un-archiving store location. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('Store Location has been un-archived.');   
     				$('.unarchive_store_'+id+'').hide();
     				
     				load_stores();
     				update_bread_crumb_trail();	
     			}
     		}); 	
	}
} 

function get_store_location_details(id)
{
	$.ajax({
		url: "ajax.php?cmd=get_store_location_details",
		type: "post",
		dataType: "xml",
		data: {
			'id':id			
		},
		error: function() {
			msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			if($(xml).find('id').text() == '0')	
			{
				show_notice('Could not locate Store settings....');
			}
			else
			{
				$('#mst_id').val( $(xml).find('id').text() );
				$('#mst_store_name').val( $(xml).find('StoreName').text() );
				$('#mst_store_number').val( $(xml).find('StoreNumber').text() );
				$('#mst_address1').val( $(xml).find('Addr1').text() );
				$('#mst_address2').val( $(xml).find('Addr2').text() );
				$('#mst_city').val( $(xml).find('City').text() );
				$('#mst_state').val( $(xml).find('State').text() );
				$('#mst_zip').val( $(xml).find('Zip').text() );
				$('#mst_contact_title').val( $(xml).find('Title').text() );
				$('#mst_contact_first_name').val( $(xml).find('First').text() );
				$('#mst_contact_last_name').val( $(xml).find('Last').text() );
				$('#mst_contact_phone1').val( $(xml).find('Phone1').text() );
				$('#mst_contact_phone2').val( $(xml).find('Phone2').text() );
				$('#mst_contact_phone3').val( $(xml).find('Phone3').text() );
				$('#mst_contact_phone4').val( $(xml).find('Phone4').text() );
				$('#mst_contact_email').val( $(xml).find('Email').text() );
				$('#mst_template_id').val( parseInt($(xml).find('Template').text()) );
				$('#mst_merchant_id').val( parseInt($(xml).find('Merchant').text()) );
								
				$('#mst_msb_name').val( $(xml).find('MSBname').text() );
				$('#mst_msb_ref').val( $(xml).find('MSBref').text() );
				$('#mst_msb_cell').val( $(xml).find('MSBcell').text() );
				$('#mst_msb_phone').val( $(xml).find('MSBphone').text() );
				$('#mst_msb_email').val( $(xml).find('MSBemail').text() );
				$('#mst_msb_addr').val( $(xml).find('MSBaddress').text() );
				
				$('#mst_irs_addr').val( $(xml).find('IRSaddress').text() );
				$('#mst_irs_agent').val( $(xml).find('IRSname').text() );
				$('#mst_irs_employ_id').val( $(xml).find('IRSref').text() );
				$('#mst_irs_email').val( $(xml).find('IRSemail').text() );
				$('#mst_irs_phone').val( $(xml).find('IRSphone').text() );
				$('#mst_irs_cell').val( $(xml).find('IRScell').text() );
				$('#mst_irs_case').val( $(xml).find('IRScase').text() );
				
				$('#mst_cm_user_id').val(parseInt($(xml).find('CMuser').text()))
				
				$('#dialog_store_form select').selectmenu('destroy');
				$('#dialog_store_form select').selectmenu();
								
				get_file_uploader('store_image_holder','Store Image',10, $(xml).find('id').text() ,'show_store_image');
				
				show_store_image($(xml).find('id').text(), "#store_image_holder" );
				
				init_upload();
				
				$('#mst_state').selectmenu('destroy');
     			$('#mst_state').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			$('#mst_cm_user_id').selectmenu('destroy');
     			$('#mst_cm_user_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     			if(id==0)
     			{
     				load_dynamic_user_select('#mst_cm_user_id_box','mst_cm_user_id',-1,0,'Select Compliance Manager','');	
     			} 
     			
				//$("select").selectmenu().selectmenu('menuWidget').addClass('overflow');
				
				update_bread_crumb_trail();
				
				$('#cust_display_portlet').html('');
				$('#store_display_portlet').html('');
				//$('#user_display_portlet').html('');
				
				load_user_list();
			}
		}
	});		
} 
function get_store_location_details_display(id)
{	//display version of info...
	
	$('#store_loc_logo').attr('src',  'images/no-profile-image.png');		//store image
	$('#store_loc_logo_edit').attr('href','');				
	
	$('#store_loc_number').html('&nbsp;');
	$('#store_loc_name').html('&nbsp;');
	$('#store_loc_addr1').html('&nbsp;');
	$('#store_loc_addr2').html('&nbsp;');
	$('#store_loc_city').html('&nbsp;');
	$('#store_loc_state').html('&nbsp;');
	$('#store_loc_zip').html('&nbsp;');
	$('#store_loc_phone').html('&nbsp;');
	$('#store_loc_fax').html('&nbsp;');
	
	$('#store_loc_cm').html('&nbsp;');
	$('#store_loc_email').html('&nbsp;');
	$('#store_loc_pass').html('&nbsp;');
	$('#store_loc_image').attr('src', 'images/no-profile-image.png' );		//CM user image
	$('#store_loc_image_edit').attr('href','');
	$('#store_loc_image_edit').click('');
	
	$('#store_loc_edit_button').html('&nbsp;');	
	
	if(id > 0)
	{
     	$.ajax({
     		url: "ajax.php?cmd=get_store_location_details",
     		type: "post",
     		dataType: "xml",
     		data: {
     			'id':id			
     		},
     		error: function() {
     			msgbox("General error retrieving store details. Please try again");
     		},
     		success: function(xml) {
     			if($(xml).find('id').text() == '0')	
     			{
     				show_notice('Could not locate Store settings....');
     			}
     			else
     			{
     				$('#store_loc_logo').attr('src', $(xml).find('StoreImage').text() );
     				$('#store_loc_logo_edit').attr('href','');				
     				
     				$('#store_loc_number').html($(xml).find('StoreNumber').text() );
     				$('#store_loc_name').html($(xml).find('StoreName').text());
     				$('#store_loc_addr1').html($(xml).find('Addr1').text());
     				$('#store_loc_addr2').html("<br>"+$(xml).find('Addr2').text());
     				$('#store_loc_city').html($(xml).find('City').text());
     				$('#store_loc_state').html($(xml).find('State').text());
     				$('#store_loc_zip').html($(xml).find('Zip').text());
     				$('#store_loc_phone').html($(xml).find('Phone3').text());
     				$('#store_loc_fax').html($(xml).find('Phone4').text());
     				
     				$('#store_loc_cm_cell').html($(xml).find('CMuserCell').text());
     				$('#store_loc_cm_phone').html($(xml).find('CMuserPhone').text());
     				
     				$('#store_loc_cm').html($(xml).find('CMuserName').text());
     				$('#store_loc_email').html($(xml).find('CMuserEmail').text());
     				$('#store_loc_pass').html('**********');
     				$('#store_loc_image').attr('src', $(xml).find('CMuserImage').text() );
     				$('#store_loc_image_edit').attr('href',$(xml).find('CMuserEdit').text());
     				$("#store_loc_image_edit").click($(xml).find('CMuserEdit2').text());
     				
     				$('#store_loc_edit_button').html($(xml).find('EditButton').text());
     				
     				init_upload();
					$("select").selectmenu().selectmenu('menuWidget').addClass('overflow');
					
					load_user_list();
     			}
     		}
     	});	
	}	
} 

function get_financial_inst_details_display()
{	//display of FI info...	
	$('#fi_name').html('&nbsp;');
	$('#fi_addr').html('&nbsp;');
	
	$('#fi_relation').html('&nbsp;');
	$('#fi_phone').html('&nbsp;');
	$('#fi_cell').html('&nbsp;');
	$('#fi_email').html('&nbsp;');
	
	$('#fi_audit').html('&nbsp;');
	$('#fi_aud_phone').html('&nbsp;');	
	$('#fi_aud_cell').html('&nbsp;');
	$('#fi_aud_email').html('&nbsp;');
	$('#fi_addrx').html('&nbsp;');
	$('#fi_aud_refer').html('&nbsp;');
	
	$('#irs_addr').html('&nbsp;');
	$('#irs_agent').html('&nbsp;');
	$('#irs_phone').html('&nbsp;');
	$('#irs_cell').html('&nbsp;');
	$('#irs_email').html('&nbsp;');
	$('#irs_emp_id').html('&nbsp;');
	$('#irs_case').html('&nbsp;');
	
	id=1;
	
	if(id > 0)
	{
     	$.ajax({
     		url: "ajax.php?cmd=get_financial_inst_details",
     		type: "post",
     		dataType: "xml",
     		data: {		
     		},
     		error: function() {
     			msgbox("General error retrieving Financial Information details. Please try again");
     		},
     		success: function(xml) {
     			if($(xml).find('FIName').text()== '' && $(xml).find('FIAuditor').text()== ''  && $(xml).find('IRSname').text()== '')	
     			{
     				show_notice('Could not locate Financial Information....');
     			}
     			else
     			{
     				$('#fi_name').html( $(xml).find('FIName').text() );
                    	$('#fi_addr').html( $(xml).find('FIAddr').text() );                    	
                    	$('#fi_relation').html( $(xml).find('FIRelation').text() );
                    	$('#fi_phone').html( $(xml).find('FIPhone').text() );
                    	$('#fi_cell').html( $(xml).find('FICell').text() );
                    	$('#fi_email').html( $(xml).find('FIEmail').text() );
                    	
                    	$('#fi_audit').html( $(xml).find('FIAuditor').text() );
                    	$('#fi_aud_phone').html( $(xml).find('FIAudPhone').text() );	
                    	$('#fi_aud_cell').html( $(xml).find('FIAudCell').text() );
                    	$('#fi_aud_email').html( $(xml).find('FIAudEmail').text() );
                    	$('#fi_addrx').html( $(xml).find('FIAudAddr').text() );
                    	$('#fi_aud_refer').html( $(xml).find('FIAudRefer').text() );
                    	
                    	$('#irs_addr').html( $(xml).find('IRSAddr').text() );
                    	$('#irs_agent').html( $(xml).find('IRSname').text() );
                    	$('#irs_phone').html( $(xml).find('IRSPhone').text() );
                    	$('#irs_cell').html( $(xml).find('IRSCell').text() );
                    	$('#irs_email').html( $(xml).find('IRSEmail').text() );
                    	$('#irs_emp_id').html( $(xml).find('IRSempid').text() );
                    	$('#irs_case').html( $(xml).find('IRScase').text() );
     			}
     		}
     	});	
	}	
} 


function get_user_id_display(id)
{
	$('#cce_user_first').html('&nbsp;');
	$('#cce_user_last').html('&nbsp;');
	$('#cce_user_locations').html('&nbsp;');
	
	$('#cce_user_title').html('&nbsp;');
	$('#cce_user_name').html('&nbsp;');
	$('#cce_user_pass').html('&nbsp;');
	
	$('#cce_user_cell').html('&nbsp;');
	$('#cce_user_phone').html('&nbsp;');
	$('#cce_user_level').html('&nbsp;');
	
	$('#cce_user_logs').html('&nbsp;');
	
	
	if(id > 0)
	{
		$.ajax({
			url: "ajax.php?cmd=get_user_details",
			data: {
				   	'id':id				
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('rslt').text() == '0')	
     			{
     				show_notice('Error finding user info.  Please try again.');
     			}
     			else
     			{
     				$('#cce_user_first').html($(xml).find('UserFirst').text());
					$('#cce_user_last').html($(xml).find('UserLast').text());
					$('#cce_user_locations').html($(xml).find('StoreName').text());
					
					$('#cce_user_title').html($(xml).find('UserTitle').text());
					$('#cce_user_name').html($(xml).find('UserName').text());
					$('#cce_user_pass').html('**********');
					
					$('#cce_user_cell').html($(xml).find('UserCell').text());
					$('#cce_user_phone').html($(xml).find('UserPhone').text());
					$('#cce_user_level').html($(xml).find('LevelName').text());
					
					$('#cce_user_logs').html($(xml).find('Logs').text());    				
     			}	    				
			}
		});
	}			
}

function view_merchant_archived()
{
	$( "#dialog_merchant_archive_dispay" ).dialog({
		width: 'auto',
		modal: true,
		open: function() {
			
			load_merchant_archive_items();
		},
          buttons: {          	
          	"Close": function() 
          	{
          		$( this ).dialog( "close" );
          	}
          }
	});	
}
function load_merchant_archive_items()
{
	$.ajax({
		url: "ajax.php?cmd=load_merchant_archive",
		data: {			    				
			},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			
			if($(xml).find('rslt').text() == '0')	
			{
				show_notice('Error finding Customer Archive.  Please try again.');
			}
			else
			{
				mrr_tab=$(xml).find('mrrTab').text();
				$('#merchant_archive_dispay').html(mrr_tab);
			}
		}
	});	
}

function load_cust_search() {
	$.ajax({
		url: "ajax.php?cmd=search_custs_filter",
		data: {
			'search_cust':$('#search_cust').val()     				
			},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			
			if($(xml).find('Cust').text() == '0')	
			{
				show_notice('Error finding Customers.  Please try again.');
			}
			else
			{
				mrr_tab=$(xml).find('mrrTab').text();
				$('#merchant_customers').html(mrr_tab);
				//$('#cust_display_portlet').html(mrr_tab);
				load_user_list();  
			}			
			
		}
	});
}

function load_cust_search_v2() {
	$.ajax({
		url: "ajax.php?cmd=search_custs_filter_v2",
		data: {
			'search_cust':$('#search_cust').val(),
			"mode_id":1   				
			},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			
			if($(xml).find('Cust').text() == '0')	
			{
				show_notice('Error finding Customers.  Please try again.');
			}
			else
			{
				mrr_tab=$(xml).find('mrrTab').text();
				$('#merchant_customers').html(mrr_tab);
			}			
			
		}
	});
}
function pick_selected_item_v2(user,merchant,store)
{	
	$.ajax({
		url: "ajax.php?cmd=pick_selected_item",
		type: "post",
		dataType: "xml",
		async:false,
		data: {
			"user_id":user,
			"merchant_id":merchant,
			"store_id":store,
			"mode_id":1
		},
		error: function() {
			//msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			update_bread_crumb_trail_v2();			
		}
	});	
}
function update_bread_crumb_trail_v2a()
{
	$.ajax({
		url: "ajax.php?cmd=update_bread_crumb_trail",
		type: "post",
		dataType: "xml",
		data: {
					
		},
		error: function() {
			//msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			mrr_tab=$(xml).find('mrrTab').text();					
			$('#bread_crumb_trail').html(mrr_tab);
			
			mycomp=parseInt($('#bct_merchant_id').html());
			//mystore=parseInt($('#bct_store_id').html());
			//myuser=parseInt($('#bct_user_id').html());
			
			get_merchant_details_display(mycomp);
			
			load_cust_search_v2();	
		}
	});	
}
function update_bread_crumb_trail_v2()
{
	$.ajax({
		url: "ajax.php?cmd=update_bread_crumb_trail",
		type: "post",
		dataType: "xml",
		data: {
					
		},
		error: function() {
			//msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			mrr_tab=$(xml).find('mrrTab').text();					
			$('#bread_crumb_trail').html(mrr_tab);
			
			//location.reload(); 
			if(doc_pg > 0)  window.location.href = "/documents.php?id="+doc_pg+"";
		}
	});	
}

function load_co_slot_info()
{
	$.ajax({
		url: "ajax.php?cmd=load_co_slot_info",
		data: {    				
			},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			
			if($(xml).find('Cust').text() == '0')	
			{
				show_notice('Error finding search compliance manager(s).  Please try again.');
			}
			else
			{
				mrr_tab=$(xml).find('mrrTab').text();
				$('#cm_slots').html(mrr_tab);
			}			
			
		}
	});
}     
     
function pick_selected_item(user,merchant,store)
{	
	$.ajax({
		url: "ajax.php?cmd=pick_selected_item",
		type: "post",
		dataType: "xml",
		data: {
			"user_id":user,
			"merchant_id":merchant,
			"store_id":store	
		},
		error: function() {
			//msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			update_bread_crumb_trail();	
			
			$('#cust_display_portlet').html('');
			$('#store_display_portlet').html('');
			//$('#user_display_portlet').html('');
			
		}
	});	
}


function debread_crumb_trail(moder)
{	
	$.ajax({
		url: "ajax.php?cmd=debread_crumb_trail",
		type: "post",
		dataType: "xml",
		data: {
			"mode":moder		
		},
		error: function() {
			//msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			update_bread_crumb_trail();				
		}
	});	
}

function update_bread_crumb_trail()
{
	$.ajax({
		url: "ajax.php?cmd=update_bread_crumb_trail",
		type: "post",
		dataType: "xml",
		data: {
					
		},
		error: function() {
			//msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			mrr_tab=$(xml).find('mrrTab').text();					
			$('#bread_crumb_trail').html(mrr_tab);
			
			mycomp=parseInt($('#bct_merchant_id').html());
			mystore=parseInt($('#bct_store_id').html());
			myuser=parseInt($('#bct_user_id').html());
			
			
			$('.merch_cust').show();
			$('.store_locals').show();
			$('.cce_users').show();
			
			$('#create-new-store').hide();	//this button is by the store section...
			
			$('#create-store').hide();		//this button is by the top search section...			
			
			$('#mrr_merchant_display').hide();
			$('#mrr_store_display').hide();
			$('#mrr_user_display').hide();
			
			if(mycomp > 0)	
			{
				$('.merch_cust').hide();
				$('.merchant_cid_'+mycomp+'').show();
				
				$('.store_locals').hide();
				$('.store_merch_'+mycomp+'').show();
				
				$('.cce_users').hide();
				$('.cce_user_merch_'+mycomp+'').show();				
				
				$('#create-new-store').show();
				
				$('#create-store').show();
				
				$('#mrr_merchant_display').show();
				
				portlet_show(".cust_info");
			} else {
				portlet_hide(".cust_info");
			}
			
			if(mystore > 0)	
			{
				$('.store_locals').hide();
				$('.store_location_id_'+mystore+'').show();
				
				$('.cce_users').hide();
				$('.cce_user_store_'+mystore+'').show();
				
				$('#mrr_store_display').show();
				
				portlet_show(".store_location");
			} else {
				portlet_hide(".store_location");
			}
			
			if(myuser > 0)	
			{
				$('.cce_users').hide();
				$('.user_id_'+myuser+'').show();
				
				$('#mrr_user_display').show();
				
				portlet_show(".edit_user");
			} else {
				portlet_hide(".edit_user");
			}
						
			if(doc_pg > 0)
			{
				load_merchant_program();
     			get_merchant_details_display(mycomp);
     			load_cust_search_v2();
     			show_waiting_files_status();
     			load_dynamic_sidebar();
     			//$('#bread_crumb_trail').hide();
			}
			else
			{
				load_dynamic_user_select('#ms_co_user_id_box','ms_co_user_id',0,0,'Select Compliance Officer','');
          		load_dynamic_user_select('#ms_grp_user_id_box','ms_grp_user_id',0,0,'Select Group Manager','');
          		load_dynamic_user_select('#mst_cm_user_id_box','mst_cm_user_id',0,0,'Select Compliance Manager','');
				
				
				//$('#bread_crumb_trail').show();
				fetch_cce_messages();
     			$('.buttonize').button();
     			load_merchant_program();
     			get_merchant_details_display(mycomp);
     			get_store_location_details_display(mystore);
     			get_user_id_display(myuser);
     			load_cust_search();
     			load_important_dates();
     			show_waiting_files_status();
     			refresh_auditor2_assignment();
     			refresh_auditor2_files();
     			load_dynamic_sidebar();	
			}
		}
	});	
} 
function update_bread_crumb_trail_stripped()
{
	$.ajax({
		url: "ajax.php?cmd=update_bread_crumb_trail",
		type: "post",
		dataType: "xml",
		data: {
					
		},
		error: function() {
			//msgbox("General error retrieving store details. Please try again");
		},
		success: function(xml) {
			mrr_tab=$(xml).find('mrrTab').text();					
			$('#bread_crumb_trail').html(mrr_tab);
			
			mycomp=parseInt($('#bct_merchant_id').html());
			mystore=parseInt($('#bct_store_id').html());
			myuser=parseInt($('#bct_user_id').html());
			
			//fetch_cce_messages();
     		//$('.buttonize').button();
			load_merchant_program();
     		get_merchant_details_display(mycomp);
     		load_cust_search_v2();
     		show_waiting_files_status();
     		load_dynamic_sidebar();
			
			$('#bread_crumb_trail').show();
			
			//refresh_auditor2_assignment();
			//refresh_auditor2_files();
		}
	});	
} 

function view_attached_file_simulator(section_id, xref_id, id) 
{	
	$.ajax({
	   type: "POST",
	   dataType: "xml",
	   async: false,
	   url: "ajax.php?cmd=view_attached_file",
	   data: {"section_id":section_id,
	   		xref_id:xref_id,
	   		file_id: id},
	   success: function(xml) {
	   		if($(xml).find('rslt').text() == '0') {
	   			alert($(xml).find('rsltmsg').text());
	   		} else {
	   			
	   			$('#file_'+id+'_path').html(''+$(xml).find('filename').text()+'');
	   		}
	   }
	 });
}

function update_auditor2_list(fileid,moder)
{
	$.ajax({
		url: "ajax.php?cmd=update_auditor2_assignment",
		data: {
			  	"file_id":fileid,
			  	"viewable":moder			
			},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			refresh_auditor2_assignment();
			refresh_auditor2_files();
		}
	});
}	
function refresh_auditor2_assignment()
{
	$('#assigned_files_section').html('Loading...');	
	
	$.ajax({
		url: "ajax.php?cmd=refresh_auditor2_assignment",
		data: {
			  							},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			
			if($(xml).find('rslt').text() == '0')	
			{
				show_notice('Error finding documents to assign to Auditor2.  Please try again.');
			}
			else
			{
				mrr_tab=$(xml).find('mrrTab').text();
				$('#assigned_files_section').html(mrr_tab);
				$('.buttonize').button();
			}			
			
		}
	});
}

function refresh_auditor2_files()
{
	$('#auditor2_files_section').html('Loading...');	
	
	$.ajax({
		url: "ajax.php?cmd=refresh_auditor2_files",
		data: {
			  				
			},
		type: "POST",
		cache:false,
		dataType: "xml",
		success: function(xml) {
			
			if($(xml).find('rslt').text() == '0')	
			{
				show_notice('Error finding documents assigned to Auditor2.  Please try again.');
			}
			else
			{
				mrr_tab=$(xml).find('mrrTab').text();
				$('#auditor2_files_section').html(mrr_tab);
				$('.buttonize').button();
			}			
			
		}
	});
}	

function mrr_download_all_docs(display_flag)
{
	file_array = new Array();
	
	$('.auditor_download').each(function() 
	{
		file_array.push($(this).attr('attachment_id'));
    	});

	$.ajax({
		url: "ajax.php?cmd=zip_download_files",
		type: "post",
		dataType: "xml",
		data: {
			"file_array": file_array
		},
		error: function() {
			msgbox("General error prepping files. Please try again");
		},
		success: function(xml) {
			if($(xml).find('rslt').text() == '0') {
				msgbox($(xml).find('rslgmsg').text());
			} else {
				window.location = $(xml).find('file').text();
			}
		}
	});
	
	if(display_flag > 0) {
		console.log(file_array.toString());
	}	
}



	function select_user_id(user_id,edit_mode)
	{
		if(user_id==0)		return;
		
		var dialog, form;
		
		
		button_obj = {};
		if(edit_mode == '') {
			button_obj['Update'] = function() {
				save_user(user_id);
				if(user_added_mode > 0)		dialog.dialog( "close" );
			};
			button_obj['Delete'] = function() {
				confirm_delete(user_id);
			};
		}
		
		button_obj['Close'] = function() {
			dialog.dialog( "close" );
		};
		
		
		
          dialog = $( "#dialog-form-profile" ).dialog({
               autoOpen: false,
			width: 500,
               modal: true,
               buttons: button_obj,
               open: function(event, ui) {
               	console.log("user settings opened");
               						
					load_dynamic_user_customer_select('user_merchant_box','merchant_id',0,0,'All',''); 
					load_dynamic_user_store_select('user_store_box','store_id',0,0,'All','');
					
					//$('#user_access_level').selectmenu('destroy');
     				$('#user_access_level').selectmenu().selectmenu('menuWidget').addClass('overflow');
                    	
                    	//	//$('#merchant_id').selectmenu('destroy');
     				$('#merchant_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     			
     				//	//$('#store_id').selectmenu('destroy');
     				$('#store_id').selectmenu().selectmenu('menuWidget').addClass('overflow');	
     			
               }
          });
		
		$.ajax({
			url: "ajax.php?cmd=display_user_settings_form",
			data: {
				'user_id':user_id,
				'edit_mode':edit_mode
				},
			type: "POST",
			cache:false,
			dataType : 'html',
			success : function(data) {							
				if(data == '')	
     			{
     				show_notice('Error finding user.  Please try again.');
     			}
     			else
     			{
					$('.mrr_user_info_display').html(data);
					dialog.dialog( "open" );
					init_upload();
					
					update_bread_crumb_trail();
										
					$('#cust_display_portlet').html('');
					$('#store_display_portlet').html('');
					//$('#user_display_portlet').html('');
					
					refresh_auditor2_assignment();
					refresh_auditor2_files();
					
					load_user_list();
					
					loaded_user_id = user_id;
					
					//$("select").selectmenu().selectmenu('menuWidget').addClass('overflow');
					
			
     			}				
			}
		});		
	}
     function load_store_search() {
     	$.ajax({
			url: "ajax.php?cmd=search_stores_filter",
			data: {
				'search_store':$('#search_store').val()     				
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('Store').text() == '0')	
     			{
     				show_notice('Error finding search Stores.  Please try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$('#store_display_portlet').html(mrr_tab);
     			}			
				
			}
		});
     }
     function load_doc_search() {
     	$.ajax({
			url: "ajax.php?cmd=search_docs_filter",
			data: {
				'search_doc':$('#search_doc').val()     				
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('File').text() == '0')	
     			{
     				show_notice('Error finding search documents.  Please try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$('#doc_display_portlet').html(mrr_tab);
     			}			
				
			}
		});
     }
     function load_user_search() {
     	portlet_show('.edit_user');
     	load_user_list();
     	
     	/*
     	$.ajax({
			url: "ajax.php?cmd=search_users_filter",
			data: {
				'search_universal':$('#search_universal').val()     				
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('File').text() == '0')	
     			{
     				show_notice('Error finding search results.  Please try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$('#user_display_portlet').html(mrr_tab);
					$('.tablesorter').tablesorter({widgets: ['zebra']});
     			}			
				
			}
		});
     	*/
     }
     function load_user_list() 
     {     	
     	$.ajax({
			url: "ajax.php?cmd=list_users_selected",
			data: {
					"user_search_filter": $('#search_universal').val()
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('File').text() == '0')	
     			{
     				show_notice('Error finding user list.  Please try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$('#user_list_display').html(mrr_tab);
     			}
     			page_resize_calc();
				
			}
		});
     }
     
     function toggle_user_perms()
     {               		
     	$('#user_perms_table').toggle();
     }
     function load_custom_user_access(divname,userid)
     {
     	$(''+divname+'').html('Loading...');
     	
     	$.ajax({
			url: "ajax.php?cmd=display_user_access_options",
			data: {
					"user_id":userid
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('File').text() == '0')	
     			{
     				show_notice('Error loading Custom User Access Settings.  Please try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$(''+divname+'').html(mrr_tab);
					
					$('#user_perms_table').hide();
     			}
			}
		});	
     }
     function save_user_access_items(levelid,userid,tempitemid,itemname,action_name,val)
     {     	
     	//find this item and set in user account settings (access level items)
     	
     	//$(".template_item_views").each(function() {
  			
  			stater=$(''+itemname+'').is(':checked');
  			  					
  			//if(stater==true || stater=="true")		lister= lister + "<br>View Template Item "+tempitemid+": Status="+stater+".";
  			  			
  			$.ajax({
          			url: "ajax.php?cmd=save_level_option",
          			data: {
          				"level_id":levelid,
          				"user_id":userid,
          				"template_id":tempitemid,          				
          				"name":action_name,
     	   				"state":stater,
     	   				"value":val     				
          				},
          			type: "POST",
          			cache:false,
          			async:false,
          			dataType: "xml",
          			error: function() {
     					msgbox("General error updating User Access Level Permision "+itemname+". Please try again.");
     				},	
          			success: function(xml) {
          				
          				if($(xml).find('rslt').text() == '0')	
               			{          				
               				msgbox("General error updating User Access Level Permission "+itemname+". Please try again.");
               			}
               			else
               			{	
               				//show_notice('User Access Level Permission has been saved.');
               			}	    				
          			}
          	});
          	/**/
		//});
		
     }
     
     function load_dynamic_sidebar() 
     {     	
		$('.dynamic_sidebar').html('');
		
     	$.ajax({
			url: "ajax.php?cmd=load_dynamic_sidebar&doc_page_id="+getURLParameterByName('id'),
			data: {
					
				},
			type: "POST",
			cache:false,
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('mrrTab').text() == '')	
     			{
     				show_notice('No extra sidebar menu items found.  Please try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$('.dynamic_sidebar').html(mrr_tab);
					
     			}			
				
			}
		});
     }
     
     function load_dynamic_user_customer_select(use_div_name,field,pre,cd,prompted,class_text) 
     {     	
		$(''+use_div_name+'').html('');		//user_merchant_box
			
     	$.ajax({
			url: "ajax.php?cmd=load_dynamic_user_customer_select",
			data: {
					"field_name":field,
					"id":pre,
					"cd":cd,
					"prompt":prompted,
					"class_text":class_text
				},
			type: "POST",
			cache:false,	
			async:false,		
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('mrrTab').text() == '')	
     			{
     				show_notice('Unable to create User Customer Select Box.  Please log in and try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$(''+use_div_name+'').html(mrr_tab);
					
					//$('#'+field+'').selectmenu('destroy');
     				//$('#'+field+'').selectmenu().selectmenu('menuWidget').addClass('overflow');					
     			}					
			}
		});
     }
     function load_dynamic_user_store_select(use_div_name,field,pre,cd,prompted,class_text) 
     {     	
		$(''+use_div_name+'').html('');		//user_store_box
				
     	$.ajax({
			url: "ajax.php?cmd=load_dynamic_user_store_select",
			data: {
					"field_name":field,
					"id":pre,
					"cd":cd,
					"prompt":prompted,
					"class_text":class_text
				},
			type: "POST",
			cache:false,	
			async:false,		
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('mrrTab').text() == '')	
     			{
     				show_notice('Unable to create User Customer Select Box.  Please log in and try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$(''+use_div_name+'').html(mrr_tab);
					
					//$('#'+field+'').selectmenu('destroy');
     				//$('#'+field+'').selectmenu().selectmenu('menuWidget').addClass('overflow');					
     			}					
			}
		});
     }
     
     function load_dynamic_user_select(use_div_name,field,pre,cd,prompted,class_text) 
     {     	
		$(''+use_div_name+'').html('');
		
		//ms_co_user_id_box
		//ms_grp_user_id_box
		//mst_cm_user_id_box
				
     	$.ajax({
			url: "ajax.php?cmd=load_dynamic_user_select",
			data: {
					"field_name":field,
					"id":pre,
					"cd":cd,
					"prompt":prompted,
					"class_text":class_text
				},
			type: "POST",
			cache:false,	
			async:false,		
			dataType: "xml",
			success: function(xml) {
				
				if($(xml).find('mrrTab').text() == '')	
     			{
     				show_notice('Unable to create User Select Box.  Please log in and try again.');
     			}
     			else
     			{
     				mrr_tab=$(xml).find('mrrTab').text();
					$(''+use_div_name+'').html(mrr_tab);
										
					if(use_div_name=="ms_co_user_id_box")
					{
						$('#ms_co_user_id').selectmenu('destroy');
     					$('#ms_co_user_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     					
     					//if(pre < 0)	$('#ms_co_user_id').selectmenu("value", 0);     					
					}
					if(use_div_name=="ms_grp_user_id_box")
					{
						$('#ms_grp_user_id').selectmenu('destroy');
     					$('#ms_grp_user_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     					
     					//if(pre < 0)	$('#ms_grp_user_id').selectmenu("value", 0);  
					}
					if(use_div_name=="mst_cm_user_id_box")
					{
						$('#mst_cm_user_id').selectmenu('destroy');
     					$('#mst_cm_user_id').selectmenu().selectmenu('menuWidget').addClass('overflow');
     					
     					//if(pre < 0)	$('#mst_cm_user_id').selectmenu("value", 0);  
					}
     			}			
				
			}
		});
     }
     
function edit_user_account(id,moder)
{
	if(id==0)		id=parseInt($('#bct_user_id').html());		//only get selected...  			
	
	if(moder==2)
	{
		//function is not included here...  
	}
	if(moder==1)
	{			
		//function is not included here...
	}	
	if(moder==3)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_user",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":1
     			},
     			error: function() {
     				msgbox("General error archiving user. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('User has been archived.');  
     				
     			}
     		}); 	
	}
	if(moder==4)
	{
		 $.ajax({
     			url: "ajax.php?cmd=archive_user",
     			type: "post",
     			dataType: "xml",
     			data: {
     				"id":id,
     				"value":0
     			},
     			error: function() {
     				msgbox("General error un-archiving user. Please try again");
     			},
     			success: function(xml) 
     			{				
     				show_notice('User has been un-archived.');   
     				$('.unarchive_user_'+id+'').hide();
     			}
     		}); 	
	}
	
	
} 

	function page_resize_calc() {
		// called on initial page load and any time the page/window is resized
			
			// on the main customer profile page, resize the center container to be the max width possible
			// excluding the left nav bar
			if($(window).width() > 750) {
				var right_column_width = $(window).width() - $('.mrr_waiting_files_container').width() - 40;
			} else {
				var right_column_width = $(window).width() - 5;
			}
			$('.main_center_container').css('width', right_column_width + 'px')
			
			// calculate the right most position of the div element by taking it's left position + width
			var container_left = $('.main_center_container').position().left;
			var container_width = parseFloat($('.main_center_container').css('width'));
			var container_right = container_left + container_width;
			
			// reposition the welcome heading to be right justified with the right most center column
			if($('.welcome_heading').length > 0) {
				var welcome_left = $('.welcome_heading').position().left;
				var welcome_width = parseFloat($('.welcome_heading').css('width'));
				var welcome_right = welcome_left + welcome_width;
				
				var welcome_right_margin_adjustment = welcome_right - container_right + 40;
				$('.welcome_heading').css('padding-right', welcome_right_margin_adjustment + 'px');
			}
			
			// reposition the tagline to be right justified with the right most center column
			if($('.mrr_tagline').length > 0) {
				var tagline_left = $('.mrr_tagline').position().left;
				var tagline_width = parseFloat($('.mrr_tagline').css('width'));
				var tagline_right = tagline_left + tagline_width;
				var tagline_right_margin_adjustment = tagline_right - container_right;
				if($('.mrr_tagline .fa').length == 0) {
					tagline_right_margin_adjustment += 20;
				}
				$('.mrr_tagline').css('padding-right', tagline_right_margin_adjustment + 'px');
			}
			
			// adjust with width of the message (box on the main customer profile page) based on the width of the other boxes
			$('#portlet_WelcomeMsg').css('width', parseFloat($('#portlet_AccInfo').css('width')) + 'px');
			
			// the main customer search box on the customer profile page
			// this section adjusts the headers and spacing to make it look as nice as possible
			// and to keep everything on one line.
			if($('.search_cust_body').length > 0) {
				
				// make the width of the title bar match the contents (since it's shorter
				// due to the scroll bar in the content box that isn't on the title bar).
				$('.search_cust_header').css('width', $('.search_cust_body').width() + 'px');
				
				// calculate the total used columns (since it probably won't be the full width)
				// any unused column space will be added to the customer name column
				var twidth = 0;
				$('.search_cust_header th').each(function() {
					twidth += $(this).width();
				});

				// figure out how much extra space we have to give to the customer name field
				width_adjustment = $('.search_cust_header tr').width() - twidth;

				new_cname_width = $('.search_cust_header th.search_box_dba').width() + width_adjustment - 20 + 'px';		//removed the cname field as a column and switched to dba (store) name.
				//new_address_width = $('.search_cust_header th.search_box_addr').width() + width_adjustment_addr - 20 + 'px';		//removed the cname field as a column and switched to dba (store) name.

				//console.log("total width: " + twidth + " | width adjustment: " + width_adjustment + " | width adjustment_cname: " + width_adjustment_cname + " | new_cname_width: " + new_cname_width + " | new address width: " + new_address_width);
				
				// make the adjustment to the customer name field in the search box
				$('.search_box_dba').css('width', new_cname_width);		//removed the cname field as a column and switched to dba (store) name.
				
			}
	}