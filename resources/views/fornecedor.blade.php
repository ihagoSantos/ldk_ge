@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="titulo-pagina-nome">
                            <h2>Fornecedores</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary-ludke" role="button" onclick="novoFornecedor()">Novo</button>
                    </div>
                    <div class="col-md-4 input-group">
                        {{-- <input id="inputBusca" class="form-control input-ludke" type="text" placeholder="Pesquisar" name="pesquisar"> --}}
                        <form action="{{route('buscarFornecedor')}}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input name="q" type="text" class="form-control" placeholder="Buscar Fornecedor" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                  <button class="btn btn-primary-ludke" type="submit">Buscar</button>
                                </div>
                              </div>
                        </form>

                    </div>
                </div>
            </div><!-- end titulo-pagina -->
        </div><!-- end col-->
    </div><!-- end row-->

    @if(isset($achou) && $achou == true)
    <div class="row">
        <div class="col-sm-12 limparBusca">
            <a href="{{route('fornecedores')}}">
                <button class="btn btn-outline-danger">Listar Todos</button>
            </a>

        </div>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-sm-12">

            <table id="tabelaFuncionarios" class="table table-hover table-responsive-xl">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($fornecedores as $forn)
                        <tr>
                            <td>{{$forn->id}}</td>
                            <td>{{$forn->nome}}</td>
                            <td>{{$forn->email}}</td>
                            <td>{{$forn->telefone->residencial}}</td>
                            <td>{{$forn->tipo}}</td>
                            <td>
                                <a href="#" onclick="editarFuncionario({{$forn->id}})">
                                    <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}">
                                </a>
                                <a href="#" onclick="removerFuncionario({{$forn->id}})">
                                    <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> <!-- end table -->            
        </div><!-- end col-->
    </div><!-- end row-->
    <div class="row justify-content-center">
        {{ $fornecedores->render() }}
    </div>

</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="dlgFornecedores">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formFornecedores">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Novo Fornecedor</h5>
                </div>
                <div class="modal-body">

                    {{-- ID do fornecedor --}}
                    <input type="hidden" id="id" class="form-control">

                    <div class="row">
                        {{-- nome --}}
                        <div class="col-sm-6 form-group">
                            <label for="nome">Nome <span class="obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome" autofocus>
                            <span id="validationNome"></span>
                        </div>

                        {{-- nome responsável --}}
                        <div class="col-sm-6 form-group">
                            <label for="nome">Nome do Responsável</label>
                            <input type="text" class="form-control" id="nomeResponsavel" name="nomeResponsavel">
                            <span id="validationNomeResponsavel"></span>
                        </div>
                    </div>

                    <div class="row">
                        {{-- cpf/cnpj --}}
                        <div class="col-sm-6 form-group">
                            <label for="nome">CPF/CNPJ <span class="obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="cpfCnpj" name="cpfCnpj">
                            <span id="validationCpfCnpj"></span>
                        </div>

                        {{-- telefone --}}
                        <div class="col-sm-6 form-group">
                            <label for="nome">Telefone <span class="obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="telefone" name="telefone">
                            <span id="validationTelefone"></span>
                        </div>
                    </div>

                    <div class="row">
                        {{-- cpf/cnpj --}}
                        <div class="col-sm-6 form-group">
                            <label for="nome">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <span id="validationEmail"></span>
                        </div>

                        {{-- telefone --}}
                        <div class="col-sm-6 form-group">
                            <label for="nome">Tipo <span class="obrigatorio">*</span></label>
                            {{-- <input type="text" class="form-control" id="tipo" name="tipo"> --}}
                            <select class="form-control" name="tipo" id="tipo">
                                <option selected disabled>Selecione o tipo</option>
                                <option value="CARNE">CARNE</option>
                                <option value="PORCOS">PORCOS</option>
                                <option value="TRIPAS">TRIPAS</option>
                                <option value="CONDIMENTOS">CONDIMENTOS</option>
                                <option value="TEMPEROS">TEMPEROS</option>
                                <option value="EMBALAGENS">EMBALAGENS</option>
                                <option value="ETIQUETAS">ETIQUETAS</option>
                            </select>
                            <span id="validationTipo"></span>
                        </div>
                    </div>
                    
                </div><!-- end modal body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Cadastrar</button>
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('javascript')

<script type="text/javascript">
$(document).ready(function($){ 
    try{
        $("#cpfCnpj").unmask();
        $("#cpfCnpj").keypress(function(){      
            var tamanho = $('#cpfCnpj').val().length;
            if(tamanho < 14){
                $('#cpfCnpj').mask('999.999.999-99');

            }
        else if(tamanho >= 14){    
                $('#cpfCnpj').mask('00.000.000/0000-00');

            }
            
        });

        $("#telefone").mask("(99) 9999-99999");
        $("#telefone").blur(function(event) {
            if($(this).val().length == 15){
                $('#telefone').mask('(99) 99999-9999');
            } else {
                $('#telefone').mask('(99) 9999-99999');
            }
        });
    }catch(e){
        console.log(e)
    }    
});
$(document).ready(function(){

    // Configura o ajax para todas as requisições ir com token csrf
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ao exibir o modal, procura o input com autofocus e seleciona ele
    $('.modal').on('shown.bs.modal',function() {
        $(this).find('[autofocus]').focus();
    });
});

function novoFornecedor(){
    $("#id").val("");
    $("#nome").val("");
    $("#nomeResponsavel").val("");
    $("#cpfCnpj").val("");
    $("#telefone").val("");
    $("#email").val("");
    $("#tipo").val("");

    $(".span").remove(); //limpa os span de erro
    $("#dlgFornecedores").modal('show');
}


function limparDadosModal(){
    $("#id").val("");
    $("#nome").val("");
    $("#nomeResponsavel").val("");
    $("#cpfCnpj").val("");
    $("#telefone").val("");
    $("#email").val("");
    $("#tipo").val("");
    $(".span").remove(); //limpa os span de erro
}
$(function(){
        $('#formFornecedores').submit(function(event){
            event.preventDefault();// não deixa fechar o modal quando clica no submit
            if($('#id').val()!= ''){
                console.log('Salvar Fornecedor');
                salvarFornecedor();
            }
            else{
                console.log('Criar Fornecedor');
                criarFornecedor();
            }
            // $('#dlgFuncionarios').modal('hide');
        });
    });
function salvarFornecedor(){
    let fornecedor = {
        id: $("#id").val(),
        nome: $("#nome").val(),
        nomeResponsavel:$("#nomeResponsavel").val(),
        cpfCnpj:$("#cpfCnpj").val(),
        telefone:$("#telefone").val(),
        email:$("#email").val(),
        tipo:$("#tipo").val()
    }

    $.ajax({
        type:"PUT",
        url:"/fornecedor/"+fornecedor.id,
        context:this,
        data:fornecedor,
        success: function(data){
            let forn = JSON.parse(data);
            alert(`Fornecedor ${forn.nome} salvo com sucesso!`);
            window.location.href = "indexFornecedores";
        },
        error: function(error){
            
            retorno = JSON.parse(error.responseText);
            exibirErros(retorno.errors);
        }
    });
}
function criarFornecedor(){
    let fornecedor = {
        nome: $("#nome").val(),
        nomeResponsavel:$("#nomeResponsavel").val(),
        cpfCnpj:$("#cpfCnpj").val(),
        telefone:$("#telefone").val(),
        email:$("#email").val(),
        tipo:$("#tipo").val()
    }
    
    $.ajax({
            type: "POST",
            url: "/fornecedor",
            context:this,
            data:fornecedor,
            success: function(data){
                fornecedor = JSON.parse(data);
                
                alert("Fornecedor cadastrado com sucesso!");
                window.location.href="\indexFornecedores";
            
            },
            error:function(error){
                retorno = JSON.parse(error.responseText);
                exibirErros(retorno.errors);
                console.log(error);

            }
        });

}

function editarFuncionario(id){
    limparDadosModal();
    $.getJSON("/fornecedor/"+id, function(data){
        console.log(data);
        $("#id").val(data.id);
        $("#nome").val(data.nome);
        $("#nomeResponsavel").val(data.nomeResponsavel);
        $("#cpfCnpj").val(data.cpfCnpj);
        $("#telefone").val(data.telefone.residencial);
        $("#email").val(data.email);
        $("#tipo").val(data.tipo);

        $("#dlgFornecedores").modal('show');
    });
}
function removerFuncionario(id){
    let confirma = confirm("você tem certeza que deseja remover esse fornecedor?");
    if(confirma){
        $.ajax({
            type: "DELETE",
            url: "/fornecedor/"+id,
            context: this,
            success: function(){
                alert("Fornecedor deletado com sucesso!");
                window.location.href="\indexFornecedores";

            },
            error: function(error){
                console.log(error);
            }
        });
    }
}

function exibirErros(error){
    
    $(".span").remove(); //limpa os span de erro
    if(error){
        if(error.nome){
            for(i=0;i<error.nome.length;i++){
                console.log(error.nome[i]);
                $("#validationNome").append("<span class="+"span"+" style="+"color:red"+">"+error.nome[i]+"</span>")
            }
        }
        if(error.cpfCnpj){
            for(i=0;i<error.cpfCnpj.length;i++){
                console.log(error.cpfCnpj[i]);
                $("#validationCpfCnpj").append("<span class="+"span"+" style="+"color:red"+">"+error.cpfCnpj[i]+"</span>")
            }
        }
        if(error.tipo){
            for(i=0;i<error.tipo.length;i++){
                console.log(error.tipo[i]);
                $("#validationTipo").append("<span class="+"span"+" style="+"color:red"+">"+error.tipo[i]+"</span>")
            }
        }
        if(error.telefone){
            for(i=0;i<error.telefone.length;i++){
                console.log(error.telefone[i]);
                $("#validationTelefone").append("<span class="+"span"+" style="+"color:red"+">"+error.telefone[i]+"</span>")
            }
        }
    }
}


</script>
@endsection
