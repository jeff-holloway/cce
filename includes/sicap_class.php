<?
// class to connect to the SICAP accounting system
// version 1 
// created on 6/7/2011
// Author: Chris Sherrod

class sicap_api_connector {
	
	// enter the information 
	var $api_connect_key 	= "13073958804ded4728e72231.05335648";
	var $api_root_url		= "http://10.1.10.2/"; //(i.e. http://192.168.0.1/sicap/";
	
	// set some default val
	var $postData 			= "";
	var $debug_post 		= false;
	var $show_output 		= false;
	var $version			= "1";
	
	function execute() {
		
		global $api_connect_key;
		
		$this->postData['api_key'] = $this->api_connect_key;
		$this->postData['cmd'] = $this->command;
		
		
		if($this->debug_post) {
			echo "<pre>";
			print_r($this->postData);
			echo "</pre>";
			die;
		}
		
		$url = $this->api_root_url."/api.php";
		
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL,$url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,10);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($curl_handle, CURLOPT_POST,1);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER,FALSE); // we don't want to stop the page from loading if there is ever an issue with the SSL
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $this->postData);
		$buffer = @curl_exec($curl_handle);
		curl_close($curl_handle);
		
		if($this->show_output) echo "<pre>$buffer</pre>";

		$xmlObject = new SimpleXMLElement($buffer);
		
		return $xmlObject;
	}
	
	function addParam($param_name, $param_value) {
		$this->postData[$param_name] = $param_value;
	}
}
?>