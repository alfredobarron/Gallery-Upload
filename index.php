<?php

$path = "images/";
$directorio = dir($path);

//echo "Directorio ".$path.":<br><br>";
$i=0;
while ($archivo = $directorio->read()){
	$trozos = explode(".", $archivo);
	$ext = end($trozos);

	$trozos2 = explode("-", $archivo);
	$first = $trozos2[0];

	if($ext == 'jpg'){
		if($first == 'thumb'){
			//Se crean los thums de la galeria
			$thumb .= '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="#" class="thumbnail" ><img class="img-responsive" src="'.$path.$archivo.'" /></a></div>';
		}else{
			if ($i == 0){
				$active = 'active';
			}else{
				$active = '';
			}
			//Se crean las imagenes grandes
			$img .= '<div class="item '.$active.'"><a href="'.$path.$archivo.'" target="_blank"><img class="img-responsive" src="'.$path.$archivo.'"></a><div class="fb-share-button" data-href="http://alfredobarron.com/proyectos/Gallery+Upload/" data-width="200px" data-type="link"></div></div>';
			$i++;
		}
	}
}
$directorio->close();

?>
<!DOCTYPE html>
<html lang="es">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Smoke Gallery + Upload</title>
	<meta name="description" content="WEB DESIGNER & DEVELOPER" />
	<meta name="keywords" content="JQuery, plugin, Bootstrap, smoke, gallery, galeria, imagenes, upload, drag and drop, alfredobarron, alfredo barron, alfredo, barron" />
	<meta name="robots" content="index,follow,archive">
	<meta name="author" content="Lic. Alfredo Barrón" />
	<link rel="shortcut icon" href="../../images/favicon.png">

	<!-- Bootstrap -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="styles.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	</head>
	<body>
		<div class="container-fluid">
			<h1 class="text-center">Smoke Gallery + Upload</h1>

			<div id="smokeDragandDrop" class="row smokeDragandDrop"></div>
			<!-- <a href="#" class="btn btn-default" name="btn-file" id="btn-file"><span class="glyphicon glyphicon-plus"></span> Agregar Imagenes</a> -->

			<br><br>

			<div class="row smokeGallery" id="smokeGallery">
				<?php echo $thumb ?>
			</div>
		</div>

		<div class="carousel slide smokeCarousel" id="smokeCarousel">
			<div class="carousel-inner">
				<?php echo $img ?>
			</div>
			<a href="#" class="btn-close"><span class="glyphicon glyphicon-remove"></span></a>
			<a class="left carousel-control" href="#smokeCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
			<a class="right carousel-control" href="#smokeCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script src="scripts.js"></script>
		<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&appId=521651221243058&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	</body>
</html>