<?php 
        function get_fastag_details(){
	        $start_date = date('Y-m-d 00:00:00',strtotime('-1 days'));
	        $end_date = date('Y-m-d 00:00:00');

	        $customer_id=XXXXX;
	        $api_key=XXXXX;

	        $url="https://fastagapi.icicibank.com/ISRCUSTAPI/Customer/GetTransactionsByPostedDate";

	        // SET INPUT DATA ARRAY
			$data = array(
				"CustomerId" => (int)$customer_id,
				"StartTransactionDate" => $start_date,
				"EndTransactionDate" => $end_date
			);
			$data = json_encode($data);

			// INPUT
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	        $url = sprintf("%s?%s", $url, http_build_query($data));

	        // SET HEADER
	        $header_api_id = "APIClient_ID:".$customer_id;
	        $header_api_key = "API_KEY:".$api_key;
	        $hader_content_type = "Content-Type:application/json";
	        $header = array($header_api_id,$header_api_key,$hader_content_type);
	        

		    // OPTIONS:
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		    // EXECUTE:
		    $result = curl_exec($curl);
		    
		    if(!$result){echo "Connection Failure";}
		    curl_close($curl);
		    $result = json_decode($result);

		    // OUTPUT
		   	$total_page = $result->TotalPages;
		    for($a = 1; $a<=$total_page; $a++)
		    {
		    	$data = array(
				"CustomerId" => (int)$customer_id,
				"StartTransactionDate" => $start_date,
				"EndTransactionDate" => $end_date,
				"PageNo" => $a
				);
		    	$this->get_page_entry($page_no,$url,$data,$header,$customer_details);
		    }
        }

    public function get_page_entry($page_no,$url,$input_data=array(),$header_data=array(),$customer_details=array())
	{
		$url = $url;

		// SET INPUT DATA ARRAY
		$data = $input_data;
		$data = json_encode($data);

		// INPUT
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $url = sprintf("%s?%s", $url, http_build_query($data));

        // SET HEADER
        $header = $header_data;


	    // OPTIONS:
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	    // EXECUTE:
	    $result = curl_exec($curl);
	    if(!$result){die("Connection Failure");}
	    curl_close($curl);
	    $result = json_decode($result);

	    // OUTPUT
	    $j = 1;
	    for($i = 0; $i<count($result->TransactionDetails); $i++)
	    {
	    	//GET FROM API TOLL TRANSACTION DETAILS
	    	$toll_data = array();
	   		$toll_data['processing_date'] = $result->TransactionDetails[$i]->ProcessingDateTime;
	   		$toll_data['transaction_date'] = $result->TransactionDetails[$i]->TransactionDateTime;
	   		$toll_data['amount'] = $result->TransactionDetails[$i]->TransactionAmount;
	   		$toll_data['TransactionId'] = $result->TransactionDetails[$i]->TransactionId;
	   		$toll_data['LaneCode'] = $result->TransactionDetails[$i]->LaneCode;
	   		$toll_data['PlazaCode'] = $result->TransactionDetails[$i]->PlazaCode;
	   		$toll_data['TransactionStatus'] = $result->TransactionDetails[$i]->TransactionStatus;
	   		$toll_data['TransactionReferenceNumber'] = $result->TransactionDetails[$i]->TransactionReferenceNumber;
	   		$toll_data['PlazaName'] = $result->TransactionDetails[$i]->PlazaName;
	   		$toll_data['VehicleNumber'] = $result->TransactionDetails[$i]->VehicleNumber;
	   	}
	}
?>
