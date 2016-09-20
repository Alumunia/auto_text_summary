<?php
	include "./indexer.php";
		
	function summarize ($filename) {
		// proses indexing
		$inv_index  = indexer();
	
		// load file dan daftar stopwords
		$load_file  = file_get_contents("./corpus/".$filename);
		$sentence 	= preg_split("/[.]+/", $load_file);
		$sentence 	= array_slice($sentence, 0, sizeof($sentence)-1); // buang array terakhir (kosong)

		$stopwords	= file_get_contents("./stopwords.txt");
		$stopwords	= preg_split("/[\s]+/", $stopwords);

		// jumlah kalimat yang diringkas
		$compression_rate 	= $compress/100; 
		$max_sentence  		= floor(sizeof($sentence)*$compression_rate);

		// inisialisasi
		$sentence_weight = array();

		// menghitung bobot tf.idf tiap kalimat
		foreach ($sentence as $key => $value) {
			// tokenisasi dengan membuang stopwords
			$word = preg_split("/[\d\W\s]+/", strtolower($value));
			$word = array_diff($word, $stopwords);		
			$word = array_values($word); // perbaiki indeks
			
			// inisialisasi bobot dan hitung frekuensi token
			$tf_idf 	= 0;
			$freq_word 	= array_count_values($word);

			// hitung bobot tf.idf
			foreach ($freq_word as $token => $tf) 
				$tf_idf += $tf * $inv_index[$token]['idf'];

			// simpan nilai bobot kalimat
			array_push($sentence_weight, $tf_idf);
			
		}
		
		echo "<br> <div align ='center'> <font size='6'><b> <span class='label label-default'>Nilai TF-IDF Tiap Kalimat </span></b> </font> </div><br> ";
		echo "<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
</style><table style='width:100%'>
		
		  <tr>
    <th>Urutan</th>
    <th>Kalimat</th>
    <th>TF.IDF</th>
    
   
  </tr>";
		
		foreach($sentence_weight as $kunci => $nilai)
		{	
		
			echo "<tr>". "<td>".$kunci."</td>"."<td>". $sentence[$kunci]." </td> "."<td>".$nilai."</td>"."</tr>";
		}
		echo"</table>";
		
		$jumlah_array = count($sentence_weight);
		if ( $jumlah_array <= 20) //menentukan jumlah centroid
		{$k = 4;}
		else
		{ $k = $jumlah_array-20;}
	
				function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
				$numbers = range($min, $max);
				shuffle($numbers);
				return array_slice($numbers, 0, $quantity);
}
	
	
	$angka_rand = array();
	$angka_rand = UniqueRandomNumbersWithinRange(0,$jumlah_array-1,$k); //memasukan indeks-indeks untuk centroid
	
	
	$centroid = array();
	
	
	
    for($i=0;$i<$k;$i++) //centroid berdasarkan indeks weight
	{
		$centroid [$i] = $sentence_weight[$angka_rand[$i]]; 
	}
	

	
	echo "<br><br> <div align ='center'> <font size='6'><b> <span class='label label-primary'>NILAI CENTROID </span></b> </font><br> <br> ";
		echo "<style>
	table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
</style><table style='width:100%'>
		
		  <tr>
    <th>Centroid</th>
    <th>Nilai</th>
    
    
   
  </tr>";
	
	foreach($centroid as $kunci2 => $nilai2)
	{
		
		echo "<tr>". "<td> <div align='center'> C".$kunci2." </div></td>"."<td> <div align='center'>". $nilai2." </div> </td> "."</tr>";
	}
	
	echo"</table>";
	
	$kelas_kalimat = array(); // kalimat ke -i, masuk ke kluster ke -c
	
		
	for ($x = 0; $x < $jumlah_array; $x++) { //iterasi pertama jarak objek ke kelas-kelas
    
	$jarak = array();
	
		for($j=0;$j<$k;$j++)
		{
			$jarak [$j] = sqrt(pow(($sentence_weight[$x]-$centroid[$j]),2));
			
		}
		$kelas_kalimat[0][$x]=array_search(min($jarak),$jarak); //indeks/cluster dari jarak terkecil dimasukan ke kalimat
		} 
		

	
	echo "<br><br> <div align ='center'> <font size='6'><b> <span class='label label-primary'>PENGELOMPOKAN KALIMAT KE DALAM KLUSTER, ITERASI 1</span></b> </font><br> <br> ";
		echo "<style>
	table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
</style><table style='width:100%'>
		
		  <tr>
    <th>Kalimat</th>
    <th>KLUSTER</th>
    
    
   
  </tr>";
	
	foreach($kelas_kalimat[0] as $kunci3 => $nilai3)
	{
		echo "<tr>". "<td> <div align='center'> Kalimat ke-".$kunci3." </div></td>"."<td> <div align='center'>C". $nilai3." </div> </td> "."</tr>";
	}
	
	echo "</table>";

	for($i = 0; $i < $k; $i++) //masukan nilai centroid baru setelah iterasi pertama
	{
		$centroid_baru = 0;
		$sementara = array();
		
		if(count(array_keys($kelas_kalimat[0],$i)) > 0){
		$sementara = array_keys($kelas_kalimat[0],$i);
		
		for($j = 0; $j < count($sementara) ;$j++)
		{   
			
			$centroid_baru = $centroid_baru + $sentence_weight[$sementara[$j]];
			 
		}
		
		$centroid [$i] = $centroid_baru/count($sementara);}
		
		else
			$centroid[$i] = $centroid[$i];
		
	}
	
		echo "<br><br> <div align ='center'> <font size='6'><b> <span class='label label-warning'>Nilai Centroid Baru</span></b> </font><br> <br> ";
		echo "<style>
	table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
</style><table style='width:100%'>
		
		  <tr>
    <th>Centroid</th>
    <th>Nilai</th>
    
    
   
  </tr>";
	
	foreach($centroid as $kunci4 => $nilai4)
	{
		
		echo "<tr>". "<td> <div align='center'> C".$kunci4." </div></td>"."<td> <div align='center'>". $nilai4." </div> </td> "."</tr>";
	}
	
	echo"</table>";
		
	$flag = 1;
	$a=1;
	
	
	while($flag <= 1)
	{

	for ($x = 0; $x < $jumlah_array; $x++) { //iterasi jarak objek ke kelas-kelas
    
	$jarak = array();
		$t=$x+2;

		
		for($j=0;$j<$k;$j++)
		{
			$jarak [$j] = sqrt(pow(($sentence_weight[$x]-$centroid[$j]),2));
			
		}
		$kelas_kalimat[$a][$x]=array_search(min($jarak),$jarak); //indeks/cluster dari jarak terkecil dimasukan ke kalimat
		}
		
		
		if($kelas_kalimat[$a] == $kelas_kalimat[$a-1])
		{	
	    $tmb = $a+1;
        		 	echo "<br><br> <div align ='center'> <font size='6'><b> <span class='label label-success' PENGELOMPOKAN KALIMAT KE DALAM KLUSTER, ITERASI ".$tmb."</span></b></font><br> <br> ";
		echo "<style>
	table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
</style><table style='width:100%'>
		
		  <tr>
    <th>Kalimat</th>
    <th>KLUSTER</th>
    
    
   
  </tr>";
	 
	 foreach($kelas_kalimat[$a] as $kunci6 => $nilai6)
	 {
		echo "<tr>". "<td> <div align='center'> Kalimat ke-".$kunci6." </div></td>"."<td> <div align='center'>C". $nilai6." </div> </td> "."</tr>";
	}
	
	echo "</table>";
			break;
	
		}
		else
		{

	for($i = 0; $i < $k; $i++) //masukan nilai centroid baru setelah iterasi
	{
		$centroid_baru = 0;
		$sementara = array();
		$sementara = array_keys($kelas_kalimat[$a],$i);
		
		if(count(array_keys($kelas_kalimat[$a],$i)) > 0){
		$sementara = array_keys($kelas_kalimat[$a],$i);
	
		for($j = 0; $j < count($sementara) ;$j++)
		{   
			
			$centroid_baru = $centroid_baru + $sentence_weight[$sementara[$j]];
			 
		}
		$centroid [$i] = $centroid_baru/count($sementara);}
		else
			$centroid [$i]=$centroid [$i];
		
	}
		

	}
		$a=$a+1;
		
	}
	

  	 
	 $berhasil = array();
	 $berhasil = array_count_values($kelas_kalimat[$a]);
	 
	 
	 echo "<br><br> <div align ='center'> <font size='6'><b> <span class='label label-primary'>JUMLAH KALIMAT DALAM CENTROID </span></b> </font><br> <br> ";
		echo "<style>
	table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
</style><table style='width:100%'>
		
		  <tr>
    <th>Kluster</th>
    <th>Jumlah Kalimat</th>
    
    
   
  </tr>";
	 
	 foreach($berhasil as $kunci7 => $nilai7)
	 {
		 echo "<tr>". "<td> <div align='center'> C".$kunci7." </div></td>"."<td> <div align='center'>". $nilai7." </div> </td> "."</tr>";
	 }
	 echo "</table>";
	 
	 
	$akhir =  array();
	$akhir = $berhasil;
	 
	 $kluster_hasil = array_search(max(array_count_values($kelas_kalimat[$a])),$berhasil);
	 
	 
	
	$berhasil = array_keys($kelas_kalimat[$a],$kluster_hasil);
	
	
		 echo "<br><br> <div align ='center'> <font size='6'><b> <span class='label label-success'>Kalimat Yang Terpilih</span></b> </font><br> <br> ";
		echo "<style>
	table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
</style><table  style='width:100%'>
		
		  <tr>
    <th>Kluster</th>
	<th>Urutan Kalimat</th>
	
    <th>Kalimat</th>
    
    
   
  </tr>";
	 
	 foreach($berhasil as $kunci8 => $nilai8)
	 {
		 echo "<tr>". "<td> <div align='center'> C".$kluster_hasil." </div></td>"."<td> <div align='center'> kalimat ke-".$nilai8." </div></td>"."<td> <div align='center'>".$sentence[$nilai8]." </div> </td> "."</tr>";
	 }
	 echo "</table>";
	 
		$summary = "";
		foreach ($berhasil as $key => $value)
		
			$summary = $summary.$sentence[$value].". ";

		// return teks asli dan hasil ringkasan
		$output = array();
		$output['original'] = $load_file;
		$output['summary']  = $summary;

		return $output;
	}