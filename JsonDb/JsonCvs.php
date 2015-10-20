<?php
class JsonDb_JsonCvs {
			
	public function toCvs($keys,$data){
	   if (count($data) == 0) {
		 return null;
	   }
	   ob_start();
	   $df = fopen("php://output", 'w');
	   fputcsv($df, array_keys($keys));
	   foreach ($array as $row) {
		  fputcsv($df, array_values($data));
	   }
	   fclose($df);
	   return ob_get_clean();
	}
	
	public function headerDownload($filename){
		$now = gmdate("D, d M Y H:i:s");
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT"); 
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}
}
