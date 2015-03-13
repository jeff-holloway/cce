function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  delete el;
  return s;
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


function get_amount(str_amount) {
	
	if(str_amount == undefined) str_amount = '';
	//str_amount = str_amount.toString();
	
	tmp_amount = str_amount.replace("$","");
	tmp_amount = tmp_amount.replace(/,/g,'');
	if(isNaN(tmp_amount) || tmp_amount == '') tmp_amount = 0;
	
	return parseFloat(tmp_amount);
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
//........................................................................



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

/*
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
*/