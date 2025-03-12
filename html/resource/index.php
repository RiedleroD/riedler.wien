<!DOCTYPE html>
<?php
	$curpath=$_GET["path"];
	function endsWith($string, $endstring){
		$len = strlen($endstring); 
		if ($len == 0){
		    return true;
		}
		return (substr($string, -$len) === $endstring);
	}
	function getRelativePath($from, $to){// stealing from SO is fun: https://stackoverflow.com/a/2638272/10499494
		// some compatibility fixes for Windows paths
		$from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
		$to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
		$from = str_replace('\\', '/', $from);
		$to   = str_replace('\\', '/', $to);

		$from     = explode('/', $from);
		$to       = explode('/', $to);
		$relPath  = $to;

		foreach($from as $depth => $dir) {
		    // find first non-matching dir
		    if($dir === $to[$depth]) {
		        // ignore this directory
		        array_shift($relPath);
		    } else {
		        // get number of remaining dirs to $from
		        $remaining = count($from) - $depth;
		        if($remaining > 1) {
		            // add traversals up to first matching dir
		            $padLength = (count($relPath) + $remaining - 1) * -1;
		            $relPath = array_pad($relPath, $padLength, '..');
		            break;
		        } else {
		            $relPath[0] = './' . $relPath[0];
		        }
		    }
		}
		$relPath=implode('/', $relPath);
		if($relPath==NULL){
			$relPath="./";
		}
		return $relPath;
	}
	if($curpath==NULL){
		$curpath="./";
	}else{
		$curpath=getRelativePath(getcwd(),realpath($curpath));
		if(substr($curpath,0,2)===".."){
			echo "fuckoff";
			quit();
		}
	}
?>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Testing stuff</title>
		<style>
			a{
				display:block;
			}
		</style>
	</head>
	<body>
		<?php
			echo "<h2>$curpath</h2>";
			if(file_exists($curpath) && is_dir($curpath)){
				$scan_arr=array_diff(scandir($curpath),array('.'));
				foreach($scan_arr as $fn){
					$fp=$curpath.$fn;
					if(file_exists($fp)){
						if(is_dir($fp)){
							echo "<a href=\"./?path=$fp\">./$fn/</a>";
						}else{
							echo "<a href=\"$fp\">./$fn</a>";
						}
					}
				}
			}else{
				echo "nay";
			}
		?>
	</body>
</html>
