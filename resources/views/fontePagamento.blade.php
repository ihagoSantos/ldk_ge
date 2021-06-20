@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="titulo-pagina-nome">
                            <h2>Fonte de Pagamento</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-primary-ludke" role="button" onclick="novaFontePagamento()">Novo</a>
                    </div>
                    <div class="col-md-4 input-group">
                        {{-- <input id="inputBusca" class="form-control input-ludke" type="text" placeholder="Pesquisar" name="pesquisar"> --}}
                        <form action="{{route('buscarFontePagamento')}}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input name="q" type="text" class="form-control" placeholder="Buscar Fonte de Pagamento">
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
            <a href="{{route('fontePagamento')}}">
                <button class="btn btn-outline-danger">Listar Todos</button>
            </a>

        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-sm-12">
            @if(isset($fontePagamento))
            <table id="tabelafontePagamento" class="table table-hover table-responsive-sm">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Linhas da tabela serão adicionadas com javascript --}}
                    @foreach ($fontePagamento as $fp)
                    <tr>
                        <td>{{$fp->id}}</td>
                        <td>{{$fp->nome}}</td>
                        <td>
                            <a href="#" onclick="editarFontePagamento({{$fp->id}})">
                                <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}" style="">
                            </a>
                            <a href="#" onclick="removerFontePagamento({{$fp->id}})">
                                <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" style="">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> <!-- end table -->

            <div class="row justify-content-center">
                {{ $fontePagamento->render() }}
            </div>
            @else
            <div class="row">
                <div class="col-sm-12 limparBusca">
                    <a href="{{route('fontePagamento')}}">
                        <button class="btn btn-outline-danger">Listar Todos</button>
                    </a>
    
                </div>
            </div>
            {{-- Mensagem Alerta --}}
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="alert alert-danger" role="alert">
                        {{$menssage}}
                    </div>
                </div>
            </div>
            @endif
        </div><!-- end col-->
    </div><!-- end row-->
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="dlgFontePagamento">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formFontePagamento" name="formFontePagamento">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Fonte de Pagamento</h5>
                </div>
                <div class="modal-body">
                    {{-- ID da fontePagamento --}}
                    <input type="hidden" id="id" class="form-control">

                    {{-- Nome da fontePagamento --}}
                    <div class="form-group">
                        {{-- Div para validação --}}
                        {{-- Fonte de Pagamento --}}

                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Nome <span class="obrigatorio">*</span></label>
                                <input class="form-control" type="text" name="nomeFontePagamento" id="nomeFontePagamento">
                                <div id="validationNome"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Agência <span class="obrigatorio">*</span></label>
                                <input class="form-control" type="text" name="agenciaFontePagamento" id="agenciaFontePagamento">
                                <div id="validationAgencia"></div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Conta <span class="obrigatorio">*</span></label>
                                <input class="form-control" type="text" name="contaFontePagamento" id="contaFontePagamento">
                                <div id="validationConta"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Observação</label>
                                <textarea class="form-control" name="obsFontePagamento" id="obsFontePagamento" cols="30" rows="2"></textarea>
                                <div id="validationObs"></div>
                            </div>
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

    // Usa a biblioteca quicksearch para buscar dados na tabela
    // $('input#inputBusca').quicksearch('table#tabelafontePagamento tbody tr');

    $(function(){

        // Configuração do ajax com token csrf
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // carregarfontePagamento();

        // ao exibir o modal, procura o input com autofocus e seleciona ele
        $('.modal').on('shown.bs.modal',function() {
            $(this).find('[autofocus]').focus();
        });

    });

    //function novaFontePagamento(){
    function novaFontePagamento(){
        $('#id').val('');

        $("#nomeFontePagamento").val('');
        $("#agenciaFontePagamento").val('');
        $("#contaFontePagamento").val('');
        $("#obsFontePagamento").val('');

        $(".span").remove(); //remove a linha do span
        // exibe o modal cadastrar fontePagamento
        $('#dlgFontePagamento').modal('show');

        

    }

    function editarFontePagamento(id){
        console.log('Editar Categoria');
        $.getJSON('/fontePagamento/'+id, function(data){
            // console.log(data);
            $('#id').val(data.id);
            $('#nomeFontePagamento').val(data.agencia);
            $("#agenciaFontePagamento").val(data.conta);
            $("#contaFontePagamento").val(data.conta);
            $("#obsFontePagamento").val(data.obs);

            $("#span").remove(); //remove a linha do span
            //exibe Modal Cadastrar Categoria
            $('#dlgFontePagamento').modal('show');
        });
    }

    function removerFontePagamento(id){
        confirma = confirm("Você tem certeza que deseja remover a fonte de pagamento?");
        if(confirma){
            $.ajax({
                type: "DELETE",
                url: "/fontePagamento/"+id,
                context: this,
                success: function(){
                    alert("Fonte de Pagamento deletada com sucesso!");
                    window.location.href = '/indexFontePagamento';

                },
                error: function(error){
                    console.log(error);
                }

            });
        }

    }

    function criarFontePagamento(){
        fontePagamento = {
            nome: $("#nomeFontePagamento").val(),
            agencia: $("#agenciaFontePagamento").val(),
            conta: $("#contaFontePagamento").val(),
            obs: $("#obsFontePagamento").val(),
        };

        $.ajax({
            type: "POST",
            url: "/fontePagamento",
            context:this,
            data:fontePagamento,
            success: function(data){
                fontePagamento = JSON.parse(data);
                $('#dlgFontePagamento').modal('hide');
                alert("Fonte de Pagamento "+fontePagamento.nome+" cadastrada com sucesso!")
                window.location.href = '/indexFontePagamento';
                
            },
            error:function(error){
                retorno = JSON.parse(error.responseText);
                exibirErros(retorno.errors);

            }
        });
    }
    function exibirErros(error){
        $(".span").remove(); //remove a linha do span

        if(error){
            if(error.nome){
                for(i=0;i<error.nome.length;i++){
                    console.log(error.nome[i]);
                    $("#validationNome").append("<span class="+"span"+" style="+"color:red"+">"+error.nome[i]+"</span>")
                }
            }
            if(error.agencia){
                for(i=0;i<error.agencia.length;i++){
                    console.log(error.agencia[i]);
                    $("#validationAgencia").append("<span class="+"span"+" style="+"color:red"+">"+error.agencia[i]+"</span>")
                }
            }
            if(error.conta){
                for(i=0;i<error.conta.length;i++){
                    console.log(error.conta[i]);
                    $("#validationConta").append("<span class="+"span"+" style="+"color:red"+">"+error.conta[i]+"</span>")
                }
            }
            if(error.obs){
                for(i=0;i<error.obs.length;i++){
                    console.log(error.obs[i]);
                    $("#validationObs").append("<span class="+"span"+" style="+"color:red"+">"+error.obs[i]+"</span>")
                }
            }


        }

    }
    function salvarFontePagamento(){
        $("#span").remove(); //remove a linha do span

        fontePagamento = {
            id: $('#id').val(),
            nome: $("#nomeFontePagamento").val(),
            agencia: $("#agenciaFontePagamento").val(),
            conta: $("#contaFontePagamento").val(),
            obs: $("#obsFontePagamento").val(),
        };
        // faz requisição PUT para /api/fontePagamento passando o id da categoria que deseja editar
        $.ajax({
            type: "PUT",
            url: "/fontePagamento/"+fontePagamento.id,
            context: this,
            data: fontePagamento,
            success: function(data){
                fontePagamento = JSON.parse(data);
                alert("Categoria "+fontePagamento.nome+" salva com sucesso!")
                window.location.href = '/indexFontePagamento';

            },
            error: function(error){
                console.log(error);
                retorno = JSON.parse(error.responseText);
                exibirErros(retorno.errors);
            }
        });
    }
   $(function () {
       $('#formFontePagamento').submit(function (event) {
           event.preventDefault();
           if($('#id').val()!= '')
                salvarFontePagamento();
            else
                criarFontePagamento();
            // $("#dlgFontePagamento").modal('hide');

       })

    });



  </script>

@endsection
