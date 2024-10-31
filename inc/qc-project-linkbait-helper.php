<?php 

defined('ABSPATH') or die("You can't access this file directly.");

//helper function
if ( ! function_exists( 'qcld_linkbait_xml2array' ) ) {
	function qcld_linkbait_xml2array ( $xmlObject){
		$out = [];
	    foreach ( $xmlObject as $index => $node ){
			if(is_object ( $node )){
				array_push($out,$node->__toString());
			}
		}
	    return $out;
	}
}

if ( ! function_exists( 'qcld_linkbait_algorithm' ) ) {
	function qcld_linkbait_algorithm($subject){ //Linkbait algorithm function //
		$dataarr = array();
		$data = simplexml_load_file(qcld_Linkbait_dir1.'/assets/data/algorithm.xml');
		$vrb = qcld_linkbait_xml2array($data->vrb);
		$adj = qcld_linkbait_xml2array($data->adj);
		$number = array('2','3','4','5','6','7','8','9','10','11','12','13','14','15');
		if(empty($vrb) && empty($adj)) 
			return; //if variables are empty
		
		for($i=0;$i<sizeof($vrb);$i++){
			for($k=0;$k<sizeof($adj);$k++){
				//creating sentence
				$num = array_rand($number,1);
				$sentence = $number[$num]." ".$vrb[$i]." of ".$adj[$k]." ".$subject;
				if(!in_array($sentence,$dataarr)){
					$dataarr[] = $sentence;
				}
			}
		}
		return $dataarr;
	}
}


//end helper function
?>