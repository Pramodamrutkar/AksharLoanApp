<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Kolkata");	

// Site Url
if(! function_exists('assets_url'))
{
	function assets_url(){
		return base_url("/assets/");
	}
}
if(! function_exists('upload_url'))
{
	function upload_url(){
		return base_url("/uploads/");
	}
}

// Common Function
if(! function_exists('GenerateOTP'))
{
	function GenerateOTP(){
		return mt_rand(1000, 9999);
	}
}
if(! function_exists('getTableDD'))
{
	function getTableDD($array="",$key="",$val="",$selected="",$where="",$order="") {
		$options = "";
		if(count($array) > 0) {
			$arrTemp = array();
			foreach($array as $arrV) {
				$arrTemp[$arrV[$key]] =  $arrV[$val];
			}$options = getArrayDD($arrTemp,$selected);
		}	
		return $options;
	}
}
if(! function_exists('getArrayDD'))
{
	function getArrayDD($arr=array(),$selected="") {	
		$options = "";	
		if(!empty($arr) && sizeof($arr)>=1 && is_array($arr)) {		
			foreach($arr as $k=>$v) {
				if($v) {				
					$sel = "";				
					if($k == $selected) $sel=' selected="selected"';
					$options.= "\n<option value=\"".$k."\"".$sel.">".$v."</option>";	 
				}
			}
		}return $options; 
	}	
}
if(! function_exists('getTreeDD'))
{
	function getTreeDD($arrD=array(), $val="adminID", $text="menuTitle", $nmParent="parentID",$selected="",$disabled=false){
		$options = "";
		$arr = getDepthArray($arrD, $nmParent, $val);	
		if(!empty($arr) && sizeof($arr)>=1 && is_array($arr)) {		
			foreach($arr as $k=>$v) {
				if($v) {				
					$sel = "";
					$dis = "";								
					if($v["parentID"]==0 && $disabled==true){$dis='disabled="disabled"';}								
					if($v[$val] == $selected) $sel=' selected="selected"';
					$v[$text] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$v["depth"]).$v[$text]; 
					$options.= "\n<option value=\"".$v[$val]."\"".$sel." ".$dis." >".$v[$text]."</option>";	 
				}
			}
		}
		return $options; 
	}
}
if(! function_exists('getDepthArray')){
	
	function getDepthArray($result, $fldParent="", $fldId="", $parent=0, $level=0, $finalArr=array(),$rt=true,$prevUrl="")
	{
		if(sizeof($result)>0 && $fldParent && $fldId){
			foreach($result as $rs){
				if($rs[$fldParent]== $parent){
					$rs['depth']=$level++;
					if($parent) { $rs['url'] = $prevUrl."/".$rs["seoUri"]; }
					
					$finalArr[]=$rs;
					$rt=false;
					$finalArr = getDepthArray($result,$fldParent,$fldId,$rs[$fldId],$level,$finalArr,$rt);
					$level--;
				}
			}
		}
		return $rt ? $result : $finalArr;
	}
}
if(! function_exists('uploadFile'))
{
	function uploadFile($dir,$file){
		$ci =& get_instance();
		
		$oldFile = '';
		if(isset($_REQUEST[$file."O"]) && trim($_REQUEST[$file."O"])!=""){
			$oldFile = $_REQUEST[$file."O"];
		}		
		
		if(isset($_FILES[$file]["name"]) && trim($_FILES[$file]["name"])!=""){
			if(!is_dir('uploads/'.$dir)){
				mkdir('./uploads/'.$dir, 0777, true);
			}
		
			$config['upload_path']          = './uploads/'.$dir;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 10000;
			$config['max_width']            = 9024;
			$config['max_height']           = 7068;
			$new_name = time();			
			$config['file_name'] = $new_name;			
			$ci->upload->initialize($config);
			$ci->load->library('upload', $config);
			if(!$ci->upload->do_upload($file)){
				return "";
			}else{
				$fileData = $ci->upload->data();
				if($oldFile!="" && is_file(($config['upload_path'].'/'.$oldFile))){
					unlink($config['upload_path'].'/'.$oldFile);
				}
				
				return $fileData["file_name"];
			}
		}else{
			return $oldFile;
		}
	}		
}
if(! function_exists('resizeImage'))
{
	function resizeImage($img="",$dir="",$resize=false,$w=60,$h=60)
	{
		$AbsPath = FCPATH.'/uploads';
		$str = base_url('/image?imagePath='.$AbsPath.'/'.$dir.'/default.png&w='.$w.'&h='.$h.'');
		if($img)
		{
			$file = $AbsPath.'/'.$dir.'/'.$img;
			if(file_exists($file) && is_file($file)){
				if($resize)
				$str = base_url('/image?imagePath='.$file.'&w='.$w.'&h='.$h.'');
				else
				$str = base_url('/image?imagePath='.$file.'');
			}
		}return $str;
	}
}