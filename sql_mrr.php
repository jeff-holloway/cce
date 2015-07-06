<? include('application.php'); ?>
<?

?>
<? include('header.php'); ?>
<?
	$fname = "CCE_WebappWireframe_Home (1)-552fd77c43c7b.png";
	echo get_filename_without_unique($fname);
	die;
	/*
	This is screen is missing some functionality (similar to Compliance Officer screen). The view for a single Store Location should look like this:
	https://docs.google.com/drawings/d/1461THo9KDIj7jK52mScuhWIo2Ra3FJ4ufUg0TCIJLnU/edit?usp=sharing. 
	The view for a Merchant with multiple Store Locations should like this:  
	https://docs.google.com/drawings/d/1fXCNQ5bWF-tanc0Ng8-J4FiF_kf04iBwt5243tNZcY0/edit?usp=sharing. 
	And clicking on one of the locations should look like this: 
	https://docs.google.com/drawings/d/1tSVhmBD7tjMj-qDIITMTFww02vZhNEkkJtWFisHEAnk/edit?usp=sharing or look the same as the single Store Location.
	*/
	
	$test_user="mrr3_test";
	$test_pass="";
	
 	echo "(".$test_user." : ".$test_pass.") Decoder Ring=<b>'".mrr_encryptor($test_pass,$test_user)."'</b>";
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
<?
include('footer.php');
die;

//test file....

//phpinfo();

/*
NOTES:
Uses MySQLi ... MySQL is deprecated past PHP 5.4.0

must use language file(s) {English and Spanish for now....
track user activity along the way.

Sections:
Login u/p, forgot password (by email), create new user account. (log user logins)
User setup (heirarchy used)
Template setup --document type(s)
Merchant/Customer setup




Merchant=Customer
MSB Template choosen for each customer...includes set of document types...
 

CID=customer ID
UID=DBA Name store location
CM=Compliance Manager
CO=Compliance Officer
Auditor
Auditor2 (only see auditor folder)



//Access Levels:
100=Super Admin (ALL ACCESS TO EVERYTHING)
90=CCE Employee (can access all levels below and some extra)
80=CCE Employee-Restricted (can access all levels below)
70=Merchant Account (Manager)------Under CCE Employee (no access to Industry News User node)
60=Merchant Compliance Officer
50=Merchant Auditor----------------Under Merchant Compliance Officer (no access to Merchant Location tree)
40=Merchant Location Manager-------Under Merchant Compliance Officer (no access to Merchant Auditor node)
30=Merchant Location Compliance Officer
20=Merchant Location Employee
10=Free Tools/Industry News User---Under CCE Employee (no access to Merchant Account Manager tree)
0=logged out....

===================================================================================CCE User Hierarchy=============================================================================================================



																										Merchant Location Compliance Officer  *
																										/															
																					Merchant Location Manager
																										\
																										Merchant Location Employee  *
																			


																				/
			CCE Employee 	- 	Merchant Account Manager 1 - Merchant Compliance Officer 1		-	Auditor(optional)  *
						\	(all CCEs can access)									\	
																			
																			
																										Merchant Location Compliance Officer  *
																										/															
																					Merchant Location Manager
																										\
																										Merchant Location Employee  *





		/					\
Super Admin					Free Tools/Industry News User	(all CCEs can access)  *
		\					/

																			
																										Merchant Location Compliance Officer  *
																										/															
																					Merchant Location Manager
																										\
																										Merchant Location Employee  *
																								
																				/
							Merchant Account Manager 2 - Merchant Compliance Officer 2		-	Auditor(optional)  *
							(all CCEs can access)									\

																										Merchant Location Compliance Officer  *
																										/														
																					Merchant Location Manager
																										\
																										Merchant Location Employee  *
						/
			CCE Employee 	|		
						\			
																										Merchant Location Compliance Officer  *
																										/												
							Merchant Account Manager 3 - Merchant Compliance Officer 3		-	Merchant Location Manager
																										\
																										Merchant Location Employee  *





*/
?>