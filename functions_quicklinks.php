<?
//added functions to handle Quicklinks...MRR Added July 2015
function mrr_update_quick_links_main($id,$name,$url,$private=0,$merchant=0,$store=0,$m_list="",$s_list="",$rownum=0,$colnum=0,$poser=0)
{
	if($id==0)
	{	//create the new link first
		$sql="
			insert into quick_links 
				(id,
				linedate_added,
				user_id,
				access_level,
				row_num,
				col_num,
				position_id,
				active,
				deleted) 
			values 
				(NULL,
				NOW(),
				'".sql_friendly($_SESSION['user_id'])."',
				'".sql_friendly($_SESSION['access_level'])."',
				1,
				1,
				1,
				1,
				0)
		";
		simple_query($sql);	
		$id=get_mysql_insert_id();	 
	}
	
	//update the link.
	if($id > 0)
	{
		$sql="
			update quick_links set 
				
				link_name='".sql_friendly(trim($name))."',
				link_url='".sql_friendly(trim($url))."',
				merchant_id='".sql_friendly($merchant)."',
				store_id='".sql_friendly($store)."',
				merchant_id_list='".sql_friendly($m_list)."',
				store_id_list='".sql_friendly($s_list)."',
				private_link='".sql_friendly($private)."',
				row_num='".sql_friendly($rownum)."',
				col_num='".sql_friendly($colnum)."',
				position_id='".sql_friendly($poser)."'
				
			where id='".sql_friendly($id)."'
		";
		simple_query($sql);		
	}	
	return $id;
}
function mrr_delete_quick_links($id)
{
	$sql="update quick_links set deleted='1' where id='".sql_friendly($id)."'";
	simple_query($sql);	
}
function mrr_restore_quick_links($id)
{
	$sql="update quick_links set deleted='0' where id='".sql_friendly($id)."'";
	simple_query($sql);	
}
function mrr_get_quick_links($id)
{
	$sql="
		select quick_links.*,
			(select username from users where users.id=quick_links.user_id) as user_named
		from quick_links		
		where quick_links.id='".sql_friendly($id)."'
		";
	$data=simple_query($sql);	
	if($row = mysqli_fetch_array($data))
	{
		$res['id']=$row['id'];
		$res['access_level']=$row['access_level'];
		$res['username']=$row['user_named'];
		$res['user_id']=$row['user_id'];
		$res['linedate_added']=$row['linedate_added'];
		$res['link_name']=trim($row['link_name']);
		$res['link_url']=trim($row['link_url']);
		$res['merchant_id']=$row['merchant_id'];
		$res['store_id']=$row['store_id'];
		$res['merchant_id_list']=trim($row['merchant_id_list']);
		$res['store_id_list']=trim($row['store_id_list']);
		$res['private_link']=$row['private_link'];
		$res['row_num']=$row['row_num'];
		$res['col_num']=$row['col_num'];
		$res['position_id']=$row['position_id'];
	}	
	return $res;
}
function mrr_display_quick_links_user()
{
	$cur_user=$_SESSION['selected_user_id'];
	$cur_cust=$_SESSION['selected_merchant_id'];
	$cur_store=$_SESSION['selected_store_id'];
	
	if($cur_user==0)		$cur_user=$_SESSION['user_id'];
	
	$tab="";
	$cntr=0;
	
	$tab.="
		<table border='0' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
	";
	
	$merch_adder="";
	if($cur_cust > 0)
	{
		$merch_adder="
			 and (
			 	quick_links.merchant_id=0
			 	or
			 	quick_links.merchant_id='".sql_friendly($cur_cust)."'
			 	or
			 	LOCATE(',".sql_friendly($cur_cust).",',merchant_id_list) >0
			 )
		";	
	}
	
	$store_adder="";
	if($cur_cust > 0)
	{
		$store_adder="
			 and (
			 	quick_links.store_id=0
			 	or
			 	quick_links.store_id='".sql_friendly($cur_store)."'
			 	or
			 	LOCATE(',".sql_friendly($cur_store).",',store_id_list) >0
			 )
		";	
	}	
	
	$sql="
		select quick_links.id
		from quick_links		
		where quick_links.deleted=0
			".$merch_adder."
			".$store_adder."
			and (
				quick_links.private_link=0
				or
				(quick_links.private_link=1 && quick_links.user_id='".sql_friendly($cur_user)."')
			)
		order by quick_links.position_id asc,
			quick_links.row_num asc, 
			quick_links.col_num asc, 
			quick_links.link_name asc, 
			quick_links.id asc
		";
	$data=simple_query($sql);	
	while($row = mysqli_fetch_array($data))
	{
		$res=mrr_get_quick_links($row['id']);
		/*
		$res['id']=$row['id'];
		$res['username']=$row['user_named'];
		$res['user_id']=$row['user_id'];
		$res['linedate_added']=$row['linedate_added'];
		$res['link_name']=trim($row['link_name']);
		$res['link_url']=trim($row['link_url']);
		$res['merchant_id']=$row['merchant_id'];
		$res['store_id']=$row['store_id'];
		$res['merchant_id_list']=trim($row['merchant_id_list']);
		$res['store_id_list']=trim($row['store_id_list']);
		$res['private_link']=$row['private_link'];
		$res['row_num']=$row['row_num'];
		$res['col_num']=$row['col_num'];
		$res['position_id']=$row['position_id'];
		*/		
		
		$tab.="<td valign='top' align='left'><a href='".$res['link_url']."' target='_blank'><span class='mrr_quick_link'>- > ".$res['link_name']."</span></a></td>";		//
		
		$cntr++;
		if($cntr%3==0 && $cntr>0)	$tab.="</tr><tr>";	
	}
	$tab.="
		</tr>
		</table>		
	";				
	
	if($cntr==0)		$tab="";
	
	$tab.="<div align='right' style='width:100%;'><i class='fa fa-pencil' style='color:#e19918;' title='Click to edit Quick Links' onClick='quick_links_editor();'></i></div>";
	
	return $tab;
}
function mrr_display_quick_links_edit()
{
	$tab="";
	
	$cur_user=$_SESSION['selected_user_id'];
	$cur_cust=$_SESSION['selected_merchant_id'];
	$cur_store=$_SESSION['selected_store_id'];
	
	if($cur_user==0)		$cur_user=$_SESSION['user_id'];
	
	$merch_adder="";
	if($cur_cust > 0)
	{
		$merch_adder="
			 and (
			 	quick_links.merchant_id=0
			 	or
			 	quick_links.merchant_id='".sql_friendly($cur_cust)."'
			 	or
			 	LOCATE(',".sql_friendly($cur_cust).",',merchant_id_list) >0
			 )
		";	
	}
	
	$store_adder="";
	if($cur_cust > 0)
	{
		$store_adder="
			 and (
			 	quick_links.store_id=0
			 	or
			 	quick_links.store_id='".sql_friendly($cur_store)."'
			 	or
			 	LOCATE(',".sql_friendly($cur_store).",',store_id_list) >0
			 )
		";	
	}	
	
	$tab.="<div id='cce_quick_links_editor'><div id='cce_quick_links_editor_pad' style='display:none;'>";
	
	//new quick link form...
	
	//get_merchant_select_box($field,$pre=0,$cd=0,$prompt="",$classy="")
     $selbox1=get_merchant_select_box('quick_link_0_cust',$cur_cust,0,"ALL"," class='all_quick_link_input'");          
     
     //get_store_select_box($field,$pre=0,$merchant=0,$cd=0,$prompt="",$classy="")
     $selbox2=get_store_select_box('quick_link_0_store',$cur_store,$cur_cust,0,"ALL"," class='all_quick_link_input'");    
	
	$tab.="<div id='quick_links_0_block' class='all_quick_link_edits'>";
	$tab.=	"<div>
				&nbsp;
				&nbsp;
				&nbsp;				
				<span class='mrr_quick_links_spacer' style='color:#e19918;'>NEW</span>&nbsp;
			</div>";		
	$tab.=	"<span>Link Name</span> <input type='text' name='quick_link_0_name' id='quick_link_0_name' value=\"\" class='all_quick_link_input'>";
	$tab.=	"<span>Web Address</span> <input type='text' name='quick_link_0_url' id='quick_link_0_url' value=\"\" class='all_quick_link_input'><br>";
	$tab.=	"<span>Customer</span> ".$selbox1."<br>";	
	$tab.=	"<span>Store</span> ".$selbox2."<br>";		
	$tab.=	"<span>&nbsp;</span> <label>Make Private <input type='checkbox' name='quick_link_0_private' id='quick_link_0_private' value=\"1\"></label>";
	$tab.="</div>";
	
	
	$sql="
		select quick_links.*
		from quick_links		
		where quick_links.deleted=0
			".$merch_adder."
			".$store_adder."
			and (
				quick_links.private_link=0
				or
				(quick_links.private_link=1 && quick_links.user_id='".sql_friendly($cur_user)."')
			)
		order by quick_links.position_id asc,
			quick_links.row_num asc, 
			quick_links.col_num asc, 
			quick_links.link_name asc, 
			quick_links.id asc
		";
	$data=simple_query($sql);	
	while($row = mysqli_fetch_array($data))
	{		
		$allow_removal="";
		if($row['user_id']==$_SESSION['user_id'] || $row['access_level'] <= $_SESSION['access_level'])
		{
			$allow_removal="<i class='fa fa-trash' style='color:#e19918; font-size:14px;' title='Click to remove this merchant' onClick='edit_quick_links(".$row['id'].",3);'></i>";	
		}
		
		$selbox1=get_merchant_select_box('quick_link_'.$row['id'].'_cust',$cur_cust,0,"ALL"," class='all_quick_link_input' onChange='edit_quick_links(".$row['id'].",6);'");          
          $selbox2=get_store_select_box('quick_link_'.$row['id'].'_store',$cur_store,$cur_cust,0,"ALL"," class='all_quick_link_input' onChange='edit_quick_links(".$row['id'].",7);'");    
		
		$tab.="<div id='quick_links_".$row['id']."_block' class='all_quick_link_edits'>";
		$tab.=	"<div>
					<img src='common/images/prev_orange.png' alt='' border='0' style='cursor:pointer;height:16px' onClick='edit_quick_links(".$row['id'].",1);'>
					".$row['position_id']." 
					<img src='common/images/next_orange.png' alt='' border='0' style='cursor:pointer;;height:16px' onClick='edit_quick_links(".$row['id'].",2);'>
					<span class='mrr_quick_links_spacer'>".$allow_removal."</span>&nbsp;
				</div>";		
		$tab.=	"<span>Link Name</span> <input type='text' name='quick_link_".$row['id']."_name' id='quick_link_".$row['id']."_name' value=\"".$row['link_name']."\" class='all_quick_link_input' onBlur='edit_quick_links(".$row['id'].",4);'>";
		$tab.=	"<span>Web Address</span> <input type='text' name='quick_link_".$row['id']."_url' id='quick_link_".$row['id']."_url' value=\"".$row['link_url']."\" class='all_quick_link_input' onBlur='edit_quick_links(".$row['id'].",5);'><br>";
		$tab.=	"<span>Customer</span> ".$selbox1."<br>";		//".$row['merchant_id'].": ".$row['merchant_id_list']."
		$tab.=	"<span>Store</span> ".$selbox2."<br>";			//".$row['store_id'].": ".$row['store_id_list']."
		$tab.=	"<span>&nbsp;</span> <label>Make Private <input type='checkbox' name='quick_link_".$row['id']."_private' id='quick_link_".$row['id']."_private' value=\"1\"".($row['private_link'] > 0 ? " checked" : "")." onClick='edit_quick_links(".$row['id'].",8);'></label>";
		$tab.="</div>";
		
		//<input type='text' name='quick_link_".$row['id']."_cust' id='quick_link_".$row['id']."_cust' value=\"".$row['merchant_id'].": ".$row['merchant_id_list']."\">
		//<input type='text' name='quick_link_".$row['id']."_store' id='quick_link_".$row['id']."_store' value=\"".$row['store_id'].": ".$row['store_id_list']."\">
	}     
	$tab.="</div></div>";
	return $tab;
}
?>