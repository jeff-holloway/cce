<?
//test file....

/*
NOTES:
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