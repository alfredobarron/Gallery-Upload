$(document).ready(function() {


// Gallery
//=====================================================================

//Se activa el carousel y se desactiva el intervalo de cambio de slide
$('#smokeCarousel').carousel({interval:false});
//Al hacer clic en el boton cerrar
$('#smokeCarousel .btn-close').click(function(e){
	e.preventDefault();
	//Se oculta smokeCarousel
	$('#smokeCarousel').fadeOut();
});

//Al hacer clic en la miniatura
$('#smokeGallery').on('click', 'a', function(e){
	e.preventDefault();
	var idx = $(this).parents('div').index();
	var id = parseInt(idx);
	//Se muestra smokeCarousel
	$('#smokeCarousel').fadeIn();
	// Se selecciona el slide
	$('#smokeCarousel').carousel(id);
});



// Upload
//=====================================================================


//Se crea el metodo que calcula el tamaño de un archivo
function bytesToSize(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if (bytes === 0) return 'n/a';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	if (i === 0) return bytes + ' ' + sizes[i];
	return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
}

//Se crea el metodo que valida el tipo y tamaño de los archivos
function validTypeSize(files){
	var validFiles = new Array();
	var invalidFiles = new Array();
	var limit = 5;
	//Se convierten los megas a bytes
	var maxSize = (limit * 1000) * 1024;
	$.each(files, function(k, v){
		var tipo = true;
		var tamanio = true;
		switch(v.type){
			case 'image/png':
			case 'image/gif':
			case 'image/jpeg':
			case 'image/pjpeg':
			// case 'text/plain':
			// case 'text/html':
			// case 'application/x-zip-compressed':
			// case 'application/pdf':
			// case 'application/msword':
			// case 'application/vnd.ms-excel':
			// case 'video/mp4':
			break;
			default:
			//alert("Unsupported file type!");
			tipo = false;
		}
		//Se compara el tamaño del archivo con el maxSize
		if(v.size > maxSize){
			alert("Uno o mas archivos no podran subirse por que sobrepasan el limite de " + limit + " MB");
			tamanio = false;
		}
		//Se compara el tipo de archivo
		if(tipo === false){
			alert('Uno o mas archivos no podran subirse por que solo se permiten archivos de tipo imagen');
		}
		//Se crea un array con los archivos validos
		if(tipo === true && tamanio ===true){
			validFiles.push(v);
		}else{
			invalidFiles.push(v);
		}
	});
	if(validFiles.length > 0){
		//Se suben los archivos
		uploadFiles(validFiles);
	}
	if(invalidFiles.length > 0){
		var result = 'Archivos no cargados: \n';
		//Se suben los archivos
		$.each(invalidFiles, function(k,v){
			result += v.name + ' ' + bytesToSize(v.size) + '\n';
		});
		alert(result);
	}
}

//Se crea el metodo que sube los archivos
function uploadFiles(files) {
	//Se oculta el texto Arrastra o haz clic
	$('#smokeDragandDrop h3').fadeOut();
	//Se muestra el loader
	//$('#smokeDragandDrop .smokeDragandDropLoader').fadeIn('slow');
	var cont = files.length;
	var c = 1;
	//Se recorren los archivos
	$.each(files, function(k, v){
		//Se instancia el formData
		//var formData = new FormData($('#form-upload')[0]);
		var formData = new FormData();
		//Se agrega el archivo al formData
		//formData.append('file[]', v);
		formData.append('file', v);
		//Se obtiene el nombre del archivo
		var name = v.name;
		//Se obtiene el tamaño del archivo
		var size = bytesToSize(v.size);
		//Se instancia el xhr
		var xhr = new XMLHttpRequest();
		//Se abre el xhr
		xhr.open('post', 'upload.php?action=xhr', true);
		//Caundo inicia la carga del archivo
		xhr.onloadstart = function(){

			//Se instancia la clase que lee los atributos del archivo
			var reader = new FileReader();
			//Se crea el metodo que lee el archivo al cargarse
			reader.onload = function (e) {
				//Se crea el preview de la imagen
				var preview =  '<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 preview-'+k+'"><div class="thumbnail">';
					preview += '<img src="' + e.target.result +'"><div class="caption">';
					//Se crea el progressbar
					preview += '<div class="progress">';
					preview += '<div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width:0%">';
					preview += '</div></div>';
					preview += '<p>' + name + '</p><p>' + size + '</p></div>';
					preview += '</div></div>';
				$('#smokeDragandDrop').prepend($(preview).fadeIn('slow'));
			};
			//Se llama el metodo que lee la url temporal del archivo
			reader.readAsDataURL(v);
		};
		//Se llama el metodo que lee el progreso de subida del archivo
		xhr.upload.onprogress = function(e) {
			if (e.lengthComputable) {
				//Se calcula el porcentaje de subida
				var porcentaje = (e.loaded / e.total) * 100;
				$('.preview-'+ k +' .progress .progress-bar').css('width', porcentaje + '%');
			}
		};
		//Se llama el metodo que lee el archivo al terminar de cargarse
		xhr.onload = function(e){
			//Se borra el preview
			$('.preview-'+k).fadeOut('fast', function() { $(this).remove(); });
			//Si el archivo se subio correctamente
			if(xhr.statusText == 'OK'){
				//Se recibe el responseText del servidor
				var resp = JSON.parse(xhr.responseText);
				//Se crea el thumbnail
				var thumbnail = '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="#" class="thumbnail" ><img class="img-responsive" src="' + resp.$thumbnail + '" /></a></div>';
				$('#smokeGallery').prepend($(thumbnail).fadeIn('slow'));
				//Se crea la imagen
				var img = '<div class="item"><a href="' + v + '" target="_blank"><img class="img-responsive" src="' + resp.$img + '"></a></div>';
				$('#smokeCarousel .carousel-inner').prepend(img);
			}else{
				alert('Ocurrio un error al intentar subir el archivo \n' + name + ' ' +size);
			}
			//Si es el ultimo archivo
			if(cont == c){
				var i = 0;
				//Se agrega la clase active al primer elemento
				$('#smokeCarousel .carousel-inner div').each(function(index){
					if(i==0){
						$(this).addClass('active');
					}else{
						$(this).removeClass('active');
					}
					i++;
				});
				//Se oculta el loading
				//$('#smokeDragandDrop .smokeDragandDropLoader').fadeOut('slow');
				//Se muestra el texto Arrastra o haz clic
				$('#smokeDragandDrop h3').fadeIn('slow');
			}
			c++;
		};
		//xhr.onreadystatechange = function(e){
		//	if(xhr.readyState == 4){
		//		console.log(xhr.responseText)
		//	}
		//};
		//xhr.setRequestHeader("Content-Type", "multipart/form-data");
		xhr.setRequestHeader("Cache-Control", "no-cache");
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		//xhr.setRequestHeader("Content-type", v.type);
		//xhr.setRequestHeader("X-File-Name", v.name);
		//Se envia el formData al servidor
		xhr.send(formData);
	});
}


//Drag and Drop
var obj = $("#smokeDragandDrop");
obj.on('dragenter', function (e){
	e.stopPropagation();
	e.preventDefault();
	$(this).addClass('smokeDragandDropHover');
});
obj.on('dragover', function (e){
	e.stopPropagation();
	e.preventDefault();
});
obj.on('drop', function (e){
	$(this).removeClass('smokeDragandDropHover');
	e.preventDefault();
	var files = e.originalEvent.dataTransfer.files;
	//Se envian los archivos
	validTypeSize(files);
});
$(document).on('dragenter', function (e){
	e.stopPropagation();
	e.preventDefault();
});
$(document).on('dragover', function (e){
	e.stopPropagation();
	e.preventDefault();
	obj.removeClass('smokeDragandDropHover');
});
$(document).on('drop', function (e){
	e.stopPropagation();
	e.preventDefault();
});

//Se crea el texto y el input file
$('#smokeDragandDrop').append('<h3><span class="glyphicon glyphicon-cloud-upload"></span>Arrastra o haz clic.</h3><input type="file" name="file" id="file" multiple style="display:none"/><div class="smokeDragandDropLoader" style="display:none"><div class="loader">Loading...</div></div>');

// Activa el input file
$('#smokeDragandDrop h3').click(function(event){
	event.preventDefault();
	$('#smokeDragandDrop #file').attr('disabled',false).click();
});

//Caundo cambia el input file
$('#smokeDragandDrop #file').on('change', function() {
	var files = this.files;
	if(files.length > 0){
		//Se envian los archivos
		validTypeSize(files);
	}
});

});