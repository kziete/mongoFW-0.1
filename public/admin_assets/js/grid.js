function log(txt){
	if(typeof console != 'undefined')
		console.log(txt);
}

var Grid = function(name){
	var self = this;
	self.nombre = name;
	self.contenedor = $("#container-" + self.nombre);
	self.hidden = $("#hidden-" + self.nombre);

	self.data = [];

	self.__init__ = function(){
		self.data = self.hidden.val() !="" ? $.parseJSON(self.hidden.val()): [["",""]];
		self.armar();
	}

	self.armar = function(){
		self.contenedor.empty();
		$.each(self.data,function(k,v){
			self.contenedor.append(self.getFila(k,v));
		});
		self.contenedor.append(self.getBotonMas());
	}
	self.recolectarData = function(){
		var cont = 0;
		for(i =0;i<self.data.length;i++){
			for(a =0;a<self.data[i].length;a++){
				self.data[i][a] = $(".data-" + self.nombre).eq(cont).val();
				cont++;
			}
		};
		self.hidden.val(JSON.stringify(self.data));
	}
	self.getFila = function(index,set){
		var tr = $("<tr/>");
		$.each(set,function(k,v){
			tr.append(
				$("<td/>")
					.append($("<input/>").addClass('data-' + self.nombre)
						.attr('type','text')
						.val(v)
						.keyup(function(){
							self.recolectarData();
						})
					)
						
			);
		});
		tr.append(
			$("<td/>").append(
				$("<a/>").addClass('btn btn-warning')
					.append(
						$("<i/>").addClass('icon-remove icon-white')
					)
				.click(function(e){
					e.preventDefault();
					tr.remove();
					self.removerFila(index);
				})
			)
		)
		return tr;
	}
	self.getBotonMas = function(){

		return $("<tr/>").append(
			$("<td/>").append(
					$("<a/>").addClass('btn btn-success').append(
							$("<i/>").addClass('icon-plus icon-white')
						)
						.click(self.agregarFila)
				)
		)
	}
	self.removerFila = function(index){
		self.data.splice(index,1);		
		self.armar();
	}
	self.agregarFila = function(e){
		e.preventDefault();
		self.data.push(['','']);
		self.armar();		
	}


	self.__init__();
}