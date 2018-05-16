<?php

// Builds inventory files
$epicorFile = 'ABSOLUTE DIRECTORY PATH/inventory.json';
$inventoryFile = 'ABSOLUTE DIRECTORY PATH/all.json';
$exFile = 'ABSOLUTE DIRECTORY PATH/EX.json';
$wlFile = 'ABSOLUTE DIRECTORY PATH/WL.json';
$mgFile = 'ABSOLUTE DIRECTORY PATH/MG.json';
$countFile = 'ABSOLUTE DIRECTORY PATH/count.txt';

$ftp = "ftp://username:password!@website.com/folder1/folder2";

$result = file_get_contents($epicorFile);

$result = str_replace("\"value\":","\"items\":",$result);

$array = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true);
      
foreach($array['items'] as $i => $item) {
        
	if($array['items'][$i]['Part_CommercialBrand'] == NULL) {
		$array['items'][$i]['Part_CommercialBrand'] = "ANY_MAKE";
	}

	if($array['items'][$i]['Part_CommercialSubBrand'] == NULL) {
		$array['items'][$i]['Part_CommercialSubBrand'] = $array['items'][$i]['Part_CommercialStyle'];
	}

	if(substr($array['items'][$i]['Part_CommercialStyle'],0,2) == "EX") {
		$array['items'][$i]['Type'] = "Excavator";
		$array['items'][$i]['FilterName'] = "EX";
	}

	if(substr($array['items'][$i]['Part_CommercialStyle'],0,2) == "WL") {
		$array['items'][$i]['Type'] = "Wheel Loader";
		$array['items'][$i]['FilterName'] = "WL";
	}

	if(substr($array['items'][$i]['Part_CommercialStyle'],0,2) == "MG") {
		$array['items'][$i]['Type'] = "Motor Grader";
		$array['items'][$i]['FilterName'] = "MG";
	}

}

// Couldn't get count to work on the page, so thug lyfe.
file_put_contents($countFile,$i) or die("Could not save count file");

$inv = json_encode($array);

file_put_contents($inventoryFile,$inv) or die("Could not save Inventory file");
            
$exInv = array();
$wlInv = array();
$mgInv = array();
            
foreach($array['items'] as $i => $item) {
                
	if($array['items'][$i]['FilterName'] == "EX") {
		$exInv[] = $item;
	}

	if($array['items'][$i]['FilterName'] == "WL") {
		$wlInv[] = $item;
	}

	if($array['items'][$i]['FilterName'] == "MG") {
		$mgInv[] = $item;
	}                
                    
}

$exInv = '{"items": ' . json_encode($exInv) . '}';
file_put_contents($exFile,$exInv) or die("Could not save EX file");
            
$wlInv = '{"items": ' . json_encode($wlInv) . '}';       
file_put_contents($wlFile,$wlInv) or die("Could not save WL file");
            
$mgInv = '{"items": ' . json_encode($mgInv) . '}';       
file_put_contents($mgFile,$mgInv) or die("Could not save MG file");

// Builds dropdown file
$dropdownFile = "ABSOLUTE DIRECTORY PATH/inv-dropdowns.json";

$exoem = array();
$wloem = array();
$mgoem = array();

$exmodel = array();
$wlmodel = array();
$mgmodel = array();

foreach($array['items'] as $i => $oem) {
        
	$category = substr($array['items'][$i]['Part_CommercialStyle'],0,2);
	$make = $array['items'][$i]['Part_CommercialBrand'];

	if($make == NULL) {
		$make = "ANY_MAKE";		
	}
        
	// Excavator
	if($category == "EX") {

		if($array['items'][$i]['Part_CommercialBrand'] == NULL) {
			$exoem[] = "ANY_MAKE";
		}

		else {
			$exoem[] = $array['items'][$i]['Part_CommercialBrand'];
		}

		if($array['items'][$i]['Part_CommercialSubBrand'] == NULL) {
			$exmodel[$make][] = $array['items'][$i]['Part_CommercialStyle'];
		}

		else {
			$exmodel[$make][] = $array['items'][$i]['Part_CommercialSubBrand'];
		}

	}

	// Loader
	if($category == "WL") {

		if($array['items'][$i]['Part_CommercialBrand'] == NULL) {
			$wloem[] = "ANY_MAKE";
		}

		else {
			$wloem[] = $array['items'][$i]['Part_CommercialBrand'];
		}

		if($array['items'][$i]['Part_CommercialSubBrand'] == NULL) {
			$wlmodel[$make][] = $array['items'][$i]['Part_CommercialStyle'];
		}

		else {
			$wlmodel[$make][] = $array['items'][$i]['Part_CommercialSubBrand'];
		}            

	}

	// Grader
	if($category == "MG") {

		if($array['items'][$i]['Part_CommercialBrand'] == NULL) {
			$mgoem[] = "ANY_MAKE";
		}

		else {
			$mgoem[] = $array['items'][$i]['Part_CommercialBrand'];
		}

		if($array['items'][$i]['Part_CommercialSubBrand'] == NULL) {
			$mgmodel[$make][] = $array['items'][$i]['Part_CommercialStyle'];
		}

		else {
			$mgmodel[$make][] = $array['items'][$i]['Part_CommercialSubBrand'];
		} 

	}
        
}
    
$exoem = array_unique($exoem);
$wloem = array_unique($wloem);
$mgoem = array_unique($mgoem);

$json = "{ \"items\": [{ \"type\": [";

if($exoem != NULL) {
	
	$json .= "{ \"name\": \"Excavator\", \"id\": \"0\", \"FilterName\": \"EX\"";
	$json .= ", \"oem\": [";
	
	foreach($exoem as $a => $oemval) {
		if($a == 0) {
		$json .= "{\"make\": " . json_encode($exoem[$a]) . ",";
	}

	else {
		$json .= ",{\"make\": " . json_encode($exoem[$a]) . ",";
	}
		
	$json .= "\"model\":";   
		
	foreach($exmodel as $b => $modval) {
		
		$$b = array_unique($modval);

		if($b == $exoem[$a]) {
			$json .= json_encode(array_values($$b));
		}
		
	}

	$json .= "}";

	}

	$json .= " ]}";
        
}

if($exoem != NULL && $wloem != NULL) {
	$json .= ",";
}


if($wloem != NULL) {
	
	$json .= "{ \"name\": \"Wheel Loader\", \"id\": \"1\", \"FilterName\": \"WL\"";
	$json .= ", \"oem\": [";
	
	foreach($wloem as $a => $oemval) {
		
	if($a == 0) {
		$json .= "{\"make\": " . json_encode($wloem[$a]) . ",";
	}

	else {
		$json .= ",{\"make\": " . json_encode($wloem[$a]) . ",";
	}
	$json .= "\"model\":";            
	foreach($wlmodel as $b => $modval) {
		
		$$b = array_unique($modval);
		
		if($b == $wloem[$a]) {
			$json .= json_encode(array_values($$b));
		}
	}

	$json .= "}";

	}

	$json .= " ]}";
        
}

if(($exoem != NULL || $wloem != NULL) && $mgoem != NULL) {
	$json .= ",";
}

if($mgoem != NULL) {
	
	$json .= "{ \"name\": \"Motor Grader\", \"id\": \"2\", \"FilterName\": \"MG\"";
	$json .= ", \"oem\": [";
	
	foreach($mgoem as $a => $oemval) {
		
		if($a == 0) {
			$json .= "{\"make\": " . json_encode($mgoem[$a]) . ",";
		}

		else {
			$json .= ",{\"make\": " . json_encode($mgoem[$a]) . ",";
		}
		
		$json .= "\"model\":";   
		
		foreach($mgmodel as $b => $modval) {
			$$b = array_unique($modval);
			
			if($b == $mgoem[$a]) {
				$json .= json_encode(array_values($$b));
			}
			
		}

		$json .= "}";

	}
        
	$json .= " ]}";
        
}

$json .= "] }] }";
        
    
file_put_contents($dropdownFile,$json) or die("Could not update Inventory Dropdowns");

?>