<?php
class Shipping
{
	private $api = 'https://auspost.com.au/api/';
	private $auth_key = '1LmjtHeMtfbRtsZ4UaFq7zI71AO5aLW2';
    const MAX_HEIGHT = 35; //only applies if same as width
	const MAX_WIDTH = 35; //only applies if same as height
	const MAX_WEIGHT = 20; //kgs
	const MAX_LENGTH = 105; //cms
	const MAX_GIRTH = 140; //cms
	const MIN_GIRTH = 16; //cms
 
    public function getRemoteData($url)
	{	print_r($url);
		$crl = curl_init();
		$timeout = 5;
		curl_setopt ($crl, CURLOPT_HTTPHEADER, array('AUTH-KEY: 1LmjtHeMtfbRtsZ4UaFq7zI71AO5aLW2'));
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($crl, CURLOPT_URL, $url);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		$contents = curl_exec ($crl);
		curl_close ($crl);
		var_dump($contents);
		return json_decode($contents,true);
	}
 
    public function getShippingCost($data)
	{
		$edeliver_url = "{$this->api}postage/parcel/domestic/calculate.json";
		$edeliver_url = $this->arrayToUrl($edeliver_url,$data);		
		$results = $this->getRemoteData($edeliver_url);
 
		if (isset($results['error']))
			throw new Exception($results['error']['errorMessage']);
 
		return $results['postage_result']['total_cost'];
	}
 
    public function arrayToUrl($url,$array)
	{
		$first = true;
		foreach ($array as $key => $value)
		{
			$url .= $first ? '?' : '&amp;';
			$url .= "{$key}={$value}";
			$first = false; 	
		}	
		return $url;
	}
 
    public function getGirth($height,$width)
	{
		return ($width+$height)*2;
	}
}

	$shipping = new Shipping();
    $data = array(
		'from_postcode' => 4511,
		'to_postcode' => 4030,
		'weight' => 1,
		'height' => 10,
		'width' => 10,
		'length' => 10,
		'service_code' => 'AUS_PARCEL_REGULAR',
		'option_code' => 'AUS_SERVICE_OPTION_STANDARD',
		'suboption_code' => 'AUS_SERVICE_OPTION_EXTRA_COVER'
	);
    print_r($data);
	try{
	    echo $shipping->getShippingCost($data);
    }
    catch (Exception $e)
    {
        echo "oops: ".$e->getMessage();
    }

?>