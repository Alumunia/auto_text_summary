<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TKI</title>



    <!-- Custom Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
</head>
	<body>
	<div class="container">
		<?php
		error_reporting(E_ERROR | E_PARSE);
		include "./summarize.php";
		
		// scan nama file korpus
		$dir_corpus = "./corpus";
		$files 		= scandir($dir_corpus);
		$files		= array_slice($files, 2);
		//print_r($files);
		
		// hasil
		if(isset($_GET["filename"])) {
			$filename	 = $_GET["filename"];
			$compression = 1;
			$output 	 = summarize($filename);
			$title 		 = substr($filename, 0, -4);
		}

		?>
		</div>
		
			<div class="container" align="center"><form action="index.php" method="GET">
			<h2>Teks Asli</h2>
				<textarea style="width:1000px" rows="10" ><?php echo !empty($output['original'])? $output['original'] : "";?></textarea>
					
					
				<h2>Ringkasan</h2>
				<textarea  style="width:1000px" rows="10" ><?php echo !empty($output['summary'])? $output['summary'] : "";?></textarea>
				<br>
				<br>
				
							
							
								<select name="filename" >
									<option value="0">PILIH DOKUMEN </option>
									<?php
									foreach ($files as $key => $value) {
										$title = str_replace("_", " ", substr($value, 0, -4));
										if($filename == $value) {
											echo "<option value='$value' SELECTED>$title</option>";
										}
										else {
											echo "<option value='$value'>$title</option>";
										}
									}
									?>
								</select><br><br>
							

							<input style="width:300px" class="btn btn-primary btn-block" type="submit" value="RINGKAS DOKUMEN">
							
						</form>
						<br>
						<br>
						
			
				
				
				</div>
		

	</body>
</html>