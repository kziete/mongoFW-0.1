function log(txt){
	if(typeof console != "undefined")
		console.log(txt);
}

//Implementa indexOf para obtener posiciones de items en listas
Array.prototype.indexOf = function(obj, start) {
     for (var i = (start || 0), j = this.length; i < j; i++) {
         if (this[i] === obj) { return i; }
     }
     return -1;
}


var MultiFile = function(name){

	var self = this;	
	self.nombre = name;
	self.contendor = jQuery("#contenedor-"+self.nombre);
	self.hidden = jQuery("#hidden-" + self.nombre);
	self.archivos = self.hidden.val() ? jQuery.parseJSON(self.hidden.val()) : [];

	self.barraParent = $("#bar-" + self.nombre);
	self.barra = self.barraParent.find('.bar');
	//self.tplFila = jQuery(".fila-tpl").html();

	self.iniciar = function(){
	    jQuery('#fileupload-' + self.nombre).fileupload({
	        done: function (e, data) {
	        	var datos = jQuery.parseJSON(data.result);

				self.addFile(datos.files[0].name);
	        	self.agregarFila(datos.files[0].name);
				/*var resp = jQuery.parseJSON(data.result);
				jQuery("#input-video").val(resp[0].name);
				jQuery("#span-video").html(resp[0].name);*/
	        },
	        progressall: function (e, data) {
	        	self.barraParent.show();
				var progress = parseInt(data.loaded / data.total * 100, 10);
				self.barra.css(
					'width',
					progress + '%'
				);
				if(progress >= 100)
					self.barraParent.hide();
	        },
			send: function (e, data) {
				
				//self.agregarFila(data);
			}
	    });
	    self.poblarLista();
	}

	self.poblarLista = function(){
		jQuery.each(self.archivos,function(k,v){
			self.agregarFila(v.name);
		});
	}

	self.agregarFila = function(name){
		var view = {
			nombre: name
		};
		var output = self.getHtml(view);
		self.contendor.prepend(output);
	}

	self.getHtml = function(data){

		var html = '<div id="id-' + MD5(data.nombre) + '">';
		html +=	'	<a href="/archivos/' + data.nombre + '" target="_blank" class="pull-left">' + data.nombre + '</a>';
		html +=	'	<a href="#" class="btn-remove btn btn-warning btn-small pull-right">';
		html +=	'		<i class="icon-remove icon-white"></i>';
		html +=	'	</a>';
		html +=	'	<div class="clearfix"></div>';
		html +=	'</div>';
		return self.setEventos(html,data.nombre);
	}
	self.setEventos = function(html,nombre){
		var obj = $(html);
		obj.find('.btn-remove').click(function(e){
			e.preventDefault();
			jQuery("#id-" + MD5(nombre)).remove();
			self.removeFile(nombre);
		});
		return obj;
	}

	self.addFile = function(name){
		self.archivos.push({'name':name});
		self.updateNames();
	}
	self.removeFile = function(name){
		for(i=0;i<self.archivos.length;i++){
			if(self.archivos[i].name == name){
				self.archivos.splice(i, 1);
				break;
			}
		}
		//self.archivos.splice(self.archivos.indexOf(name), 1);
		self.updateNames();
	}
	self.updateNames = function(){
		self.hidden.val(JSON.stringify(self.archivos));
	}
	self.getIdFromName = function(string){
		return string.replace(' ','');
	}



    self.iniciar();

}