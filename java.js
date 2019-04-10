function cadastroMedico(){
    document.getElementById('formulario_medico').hidden = false;
    document.getElementById('formulario_paciente').hidden = true;
	
    document.getElementById('radio_medico').checked = true;
}

function cadastroPaciente(){
    document.getElementById('formulario_paciente').hidden = false;
    document.getElementById('formulario_medico').hidden = true;
	
    document.getElementById('radio_paciente').checked = true;
}

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
});

function mostraProtocolos(){
	$('#principal').load('mostra_protocolo.php');
}

function mostraProtocolo(protocoloId){
	$('#principal').load('mostra_protocolo.php',{id:protocoloId});
}

function salvaProtocolo(protocoloId, medicoId){
    var nome = $('[name="nome-protocolo"]').val();
    var modulos = $('[name="modulos-protocolo"]').val();
    $.post('salva_protocolo.php', {id: protocoloId, nome: nome, medico: medicoId, modulos: modulos}, function(){
		mostraProtocolos();		
	});
}

function removeProtocolo(protocoloId){
    var remove = confirm('Tem certeza que deseja remover esse Protocolo?');
    if(remove == true){
        $.post('remove_protocolo.php', {id: protocoloId}, function(data){
			if(data != ""){
				alert(data);
			}
            mostraProtocolos();
        });
    }
}

function adicionaModulos(){
    var modulos = []
    var i = 0;
    $("[name='modulos[]']:checked").each(function (){
        modulos[i] = $(this).val();
        $(this).prop('checked', false);
        i += 1;
    });

    modulos.forEach(function(moduloId){
        $.post('mostra_modulo.php', {id: moduloId}, function(data){
            $('#modulos').append(data);                        
        })
        var value = $('[name="modulos-protocolo"').val();
        value += (',' + moduloId);
        $('[name="modulos-protocolo"]').val(value);
    })
    ;
}

function removeModulo(moduloId){
    var value = $('[name="modulos-protocolo"').val();
    var newValue = value.replace(',' + moduloId, '');
    $('[name="modulos-protocolo"]').val(newValue);

    $('#modulo' + moduloId).remove();
}


function mostraProntuarios(){
	$('#principal').load("mostra_prontuario.php");
}

function mostraProntuario(prontuarioId){
	$('#principal').load("mostra_prontuario.php", {id: prontuarioId});
}

function adicionaProntuario(protocoloId, medicoId, protocoloNome){
	$.post("adiciona_prontuario.php", {protocolo: protocoloId, medico: medicoId}, function(data){
		mostraProntuario(data);
	});
};

function salvaProntuario(prontuarioId){
	var listaValores = [];
	var radio = "";
	$('input').each(function(){
		if($(this).attr('type') != "radio"){
			var name = $(this).attr('name').split('-');
			listaValores[name[0]] += ":" + name[1] + "=" + $(this).val();
		}
	});
	
	$('[type=radio]:checked').each(function(){
		var name = $(this).attr('name').split('-');
		listaValores[name[0]] += ":" + name[1] + "=" + $(this).val();
	})

	var valores = "";

	listaValores.forEach(function(item, index){
		valores += index + item + ";";
	})

	$.post('salva_prontuario.php', {id: prontuarioId, valores: valores}, function(data){
		if(data == ""){
			mostraProntuario(prontuarioId);
		}else{
			$('#erro-prontuario').html(data);
		}		
	});
}

function mostraAgenda(opcao, data){
	$('#principal').load('mostra_agenda.php', {opcao: opcao, data: data});
}

function mostraCalendario(){
	$('#calendario').load('mostra_calendario.php');
}

function adicionaEvento(dataEvento, usuario){
	var tipo = $('[name=tipo]').val();
	var descricao = $('[name=descricao]').val();
	var horario = $('[name=horario').val();
	var paciente = $('[name=paciente]').val();

	$.post('adiciona_evento.php', {usuario: usuario, data: dataEvento, tipo: tipo, descricao: descricao, horario: horario, paciente: paciente}, function(data){
		if(data == ""){
			$('#modal-evento').modal('toggle');
			mostraAgenda('dia', dataEvento);
			mostraCalendario();
		}else{
			$('#erro-modal-evento').html(data);
		}
		
	});
}

function removeEvento(eventoId, data){
	$.post('remove_evento.php', {id: eventoId}, function(){
		mostraAgenda('dia', data);
		mostraCalendario();
	})
}

function mostraIndexMedico(){
	$('#principal').load('mostra_index_medico.php');
}

function adicionaItemRefeicao(numeroItem){
	var onclick = "adicionaItemRefeicao( " + (numeroItem + 1) + ")";
	$('#adiciona-item-refeicao').attr('onclick', onclick);
	$.post('item_refeicao.php', {item: numeroItem}, function(data){
		$('#itens-refeicao').append(data);
	});
}

function removeItemRefeicao(id){
	$('#' + id).remove();
}

function adicionaRegistro(tipo, usuario){
	var nome = $('[name=nome-' + tipo + ']').val();
	var horario = $('[name=horario-' + tipo + ']').val();
	if(tipo == "refeicao"){
		var descricao = "";
		
		$('.item-refeicao').each(function(){
			var idItem = $(this).attr('id');
			var nomeItem = $('[name=nome-' + idItem + ']').val();
			var qtdItem = $('[name=qtd-' + idItem + ']').val();
			var medidaItem = $('[name=medida-' + idItem + ']').val();
			
			descricao += nomeItem + ":" + qtdItem + ":" + medidaItem + ";";
		});
	} else {
		var descricao = $('[name=descricao-' + tipo + ']').val();
	}
	
	$.post('adiciona_registro.php', {usuario: usuario, nome: nome, horario: horario, descricao: descricao, tipo: tipo}, function(data){
		if(data == ""){
			$('#modal-' + tipo).modal('toggle');
			mostraIndexPaciente(tipo);
		}else{
			$('#erro-modal-' + tipo).html(data);
		}
	});
}

function removeRegistro(idRegistro, tipo){
	$.post('remove_registro.php', {id: idRegistro}, function(data){
	    if(data != ""){
			alert(data);
		}
		mostraIndexPaciente(tipo);
	});
}

function modalAlteraRegistro(idRegistro, tipo){
	$.post('modal_altera_registro.php', {id: idRegistro, tipo: tipo}, function(data){
		$('body').append(data);
		$('#modal-altera-registro-' + idRegistro).modal('toggle');
	})
}

function alteraRegistro(idRegistro, tipo){
	var nome = $('[name=nome-altera-' + idRegistro + ']').val();
	var horario = $('[name=horario-altera-' + idRegistro + ']').val();

	if(tipo == "refeicao"){
		var descricao = "";
		
		$('.altera-item-refeicao').each(function(){
			var idItem = $(this).attr('id');
			var nomeItem = $('[name=nome-' + idItem + ']').val();
			var qtdItem = $('[name=qtd-' + idItem + ']').val();
			var medidaItem = $('[name=medida-' + idItem + ']').val();
			
			descricao += nomeItem + ":" + qtdItem + ":" + medidaItem + ";";
		});
	} else{
		var descricao = $('[name=descricao-altera-' + idRegistro + ']').val();
	}

	$.post('altera_registro.php', {id: idRegistro, nome: nome, horario: horario, descricao: descricao, tipo: tipo}, function(){
		mostraIndexPaciente(tipo);
		$('#modal-altera-registro-' + idRegistro).remove();
	});


}

function adicionaAlteraItemRefeicao(numeroItem){
	var onclick = "adicionaAlteraItemRefeicao( " + (numeroItem + 1) + ")";
	$('#adiciona-altera-item-refeicao').attr('onclick', onclick);
	$.post('altera_item_refeicao.php', {item: numeroItem}, function(data){
		$('#altera-itens-refeicao').append(data);
	});
}

function removeAlteraItemRefeicao(id){
	$('#' + id).remove();
}

function selecionaEstado(estado){
	$('#estado-atual').attr('src', ("Imagens/" + estado + ".png"));	
}

function mostraIndexPaciente(tipoAtividade){
	$('#principal').load('mostra_index_paciente.php', {tipo: tipoAtividade});
}

function mostraRegistros(){
	$('#principal').load('mostra_registro.php');
}