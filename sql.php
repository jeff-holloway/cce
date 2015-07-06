<?
include('application.php');
include('header.php');


//$file = "temp/7349d2d3433aad89dc18f3582ee8ce3aDocebo SSO Services -551d79827a05a.fdf";
$file = "temp/1ca61b6d2ca873643a857d1dbd507293155807c2597856155807c259785b.pdf";

//<iframe width='800' height='500' src='$file' TYPE='application/vnd.adobe.pdfxml'>test</iframe>
echo "

	<iframe width='800' height='500' src='$file' TYPE='application/vnd.adobe.pdfxml'>test</iframe>
	
	<br><br>

	<OBJECT data='$file' TITLE='SamplePdf' WIDTH=800 HEIGHT=500>
	    <a href='$file'>shree</a> 
	</object>
";

die;

echo create_thumbnail('documents/Head shot 2-5526b9600a69d.jpg', 100, 100);

die;
$From = "info@cce.sherrodcomputers.com";
$FromName = $From;
$To = "chris@sherrodcomputers.com";
$ToName = $To;
$Subject = "Test message: " . time();
$Html = "Test body";
$Text = $Html;
$AttmFiles = "";

sendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);

die;

$fname = "CCE_WebappWireframe_Home (1)-552fd77c43c7b.png";
echo get_filename_without_unique($fname);
die;


$user_idst =strtolower('Employee.test2');
$time =time();//time php

//this value is configurable in admin area.
 
 $key_sso = $defaultsarray['docebo_api_key_custom'];
 $token =md5($user_idst.','.$time.','.$key_sso);

 $url ='http://capitalcomplianceexperts.docebosaas.com/lms/index.php?r=site/sso&login_user='.$user_idst.'&time='.$time.'&token='.$token;
 
 // echo value

 echo "<a href='".$url."' >$url</a>";
 
 
die;

$csv = "/temp/bab70eee955338f3ea3cb74e5f6fed9d12.10.16 Tristan 100-551dc5ee68abf.JPG,/temp/f1b9552918c2983247bdf8fe8057c82714.08.13 B and T riding edited-551eb3c31ac5d.jpg,/temp/90d0a2d59ccd0c1705f8e9bd9b43ba84Docebo SSO Services -551d79827a05a.pdf,/temp/18641f52ceb1122d7739d2647f658e6ecapitalcompliance_inner_1-551d797915801.jpg";

$farray = explode(",", $csv);

$zip = new ZipArchive();

$filename = getcwd()."/temp/".uniqid('', true).".zip";

if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
} else {
	echo "Creating zip!!<br>";
}




foreach($farray as $file) {
	$fname = basename($file);
	if(!$zip->addFile(getcwd().$file, $fname)) {
		echo "ERROR!!!!! ";
	}
	
	echo "$file";
	echo "<br>";
}

echo "numfiles: " . $zip->numFiles . "\n";
echo "status:" . $zip->status . "\n";
$zip->close();

die;

phpinfo();
die;

$fname = 'test.pdf';

d(pathinfo($fname));

die;

?>

<script>
	$().ready(function() {
		
		$( ".tooltip" ).tooltip();
		$( ".accordion" ).accordion();
		$('input[type=button]').button();
	});
	
	function test_dialog() {
		$("#dialog_holder").dialog();
		
	}
</script>



<div class="accordion">
  <h3>Dialog Box Example</h3>
  <div>
    <p>
    		<input type='button' value='Test Dialog Box' onclick='test_dialog()'>
    		<!--- // recommend a global hidden ID to dynamically work with // --->
		<div id="dialog_holder" title="Basic dialog" style='display:none'>
		  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
		</div>
    </p>
  </div>
  <h3>Textarea / Buttosn</h3>
  <div>
    <p>
    Test: <input name='testinput' class='tooltip' value='' placeholder='First name here' title='test popup tooltip'>
    </p>
  </div>
  <h3>Select Boxes</h3>
  <div>
    <p>
    Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
    Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
    ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
    lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
    </p>
    <ul>
      <li>List item one</li>
      <li>List item two</li>
      <li>List item three</li>
    </ul>
  </div>
  <h3>Tabs</h3>
  <div>
    <p>
    Cras dictum. Pellentesque habitant morbi tristique senectus et netus
    et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
    faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
    mauris vel est.
    </p>
    <p>
    Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
    Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
    inceptos himenaeos.
    </p>
  </div>
</div>




<?

include('footer.php');

die;



echo "<br>done";
?>