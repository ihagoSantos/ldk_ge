@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-md-10">
                        <div class="titulo-pagina-nome">
                            <h2>Contas a Pagar</h2>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-primary-ludke" role="button" onclick="novaConta()" style="color:white">Nova</a>
                    </div>
                    
                </div>
            </div><!-- end titulo-pagina -->
        </div><!-- end col-->
    </div><!-- end row-->

    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(isset($contas))
            <table id="tabelaContas" class="table table-hover table-responsive-md">
                <thead class="thead-primary">
                <tr>
                    <th>ID</th>
                    <th>Fornecedor</th>
                    <th>Desc.</th>
                    <th>Data Pag.</th>
                    <th>Data Venc.</th>
                    <th>Status</th>
                    <th>Valor Total</th>
                    <th>Valor Pago</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                {{-- Linhas da tabela serão adicionadas com javascript --}}
                
                @foreach ($contas as $conta)
                <tr>
                    <td>{{$conta->id}}</td>
                    <td>{{$conta->fornecedor->nome}}</td>
                    <td>{{$conta->descricao}}</td>
                    <td>{{date('d/m/Y',strtotime($conta->dataPagamento))}}</td>
                    <td>{{date('d/m/Y',strtotime($conta->dataVencimento))}}</td>
                    <td>{{($conta->status == 1)? "Pago" : "Aguardando"}}</td>
                    <td>R$ {{money_format('%i',$conta->valorTotalPgm)}}</td>
                    <td>R$ {{money_format('%i',$conta->valorPago)}}</td>
                    <td>
                        @if ($conta->status == 0)
                        {{-- Pagamento --}}
                        <a id="registrarPagamento" title="Registrar pagamento" onclick="registrarPagamento({{$conta->id}})">
                            <img id="pagar" class="icone" src="{{asset('img/money-bill-wave-solid.svg')}}" >
                        </a>
                            
                        @endif
                        {{-- Visualizar --}}
                        <a id="visualizarPagamento" title="Visualizar pagamento" onclick="exibir({{$conta->id}})">
                            <img class="icone" src="{{asset('img/eye-solid.svg')}}" >
                        </a>
                        @if ($conta->status == 0)
                        <a href="#" onclick="editarContas({{$conta->id}})">
                            <img id="icone" class="icone" src="{{asset('img/edit-solid.svg')}}" style="">
                        </a>
                        @endif
                        <a href="#" onclick="removerContas({{$conta->id}})">
                            <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" style="">
                        </a>
                    </td>
                    
                </tr>
                @endforeach

            </tbody>
        </table> <!-- end table -->

        <div class="row justify-content-center">
            {{ $contas->render() }}
        </div>
        @else
        <div class="row">
            <div class="col-sm-12 limparBusca">
                <a href="{{route('cargos')}}">
                    <button class="btn btn-outline-danger">Listar Todos</button>
                </a>

            </div>
        </div>
        @endif
</div>


{{-- Modal Cadastro/Edição --}}
<div class="modal fade" tabindex="-1" role="dialog" id="dlgContas">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formContasPagar" name="formContasPagar">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nova Conta</h5>
                </div>
                <div class="modal-body">
                    
                    {{-- ID da conta --}}
                    <input type="hidden" id="id" class="form-control">
                    
                    {{-- Centro de custo --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Fornecedor</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Fornecedor</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 form-group">
                            <select class="form-control" name="fornecedor" id="fornecedor" required>
                                <option selected disabled></option>
                                @foreach ($fornecedores as $fornecedor)
                                    <option value="{{$fornecedor->id}}">{{$fornecedor->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <a href="{{route('fornecedores')}}" class="btn btn-primary-ludke">Novo Fornecedor</a>
                        </div>
                    </div>
                    {{-- Centro de custo --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Centro de Custo</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 form-group">
                            <select class="form-control" name="centroCusto" id="centroCusto" required>
                                <option selected disabled></option>
                                @foreach ($centroCusto as $cc)
                                    <option value="{{$cc->id}}">{{$cc->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <a href="{{route('centroCusto')}}" class="btn btn-primary-ludke">Novo Centro de Custo</a>
                        </div>
                    </div>
                    {{-- Fonte de Pagamento --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Fonte de Pagamento</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 form-group">
                            <select class="form-control" name="fontePagamento" id="fontePagamento" required>
                                <option selected disabled></option>
                                @foreach ($fontePagamento as $fp)
                                    <option value="{{$fp->id}}">{{$fp->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <a href="{{route('fontePagamento')}}" class="btn btn-primary-ludke">Nova Fonte de Pagamento</a>
                        </div>
                    </div>
                    
                    {{-- Conta --}}
                    <div class="row">
                        <div class="col-sm-10">
                            <h4>Conta a Pagar</h4>
                        </div>
                    </div>

                    <div id="divContas"></div>
                    <div class="row justify-content-center">
                        <div class="col-sm-3">
                            <a class="btn btn-primary-ludke" id="btnAdicionarConta">Adicionar Conta</a>
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

{{-- Modal Visualizar--}}
<div class="modal fade" tabindex="-1" role="dialog" id="dlgVisualizar">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visualizar Conta</h5>
            </div>
            <div class="modal-body">
                {{-- Fornecedor --}}
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Fornecedor</h4>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">Nome</label>
                        <h5 id="showNomeFornecedor"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Nome do Responsável</label>
                        <h5 id="showNomeRespFornecedor"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">CPF/CNPJ</label>
                        <h5 id="showCpfCnpjFornecedor"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Tipo</label>
                        <h5 id="showTipoFornecedor"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">E-mail</label>
                        <h5 id="showEmailFornecedor"></h5>
                    </div>
                    
                    <div class="col-sm-3">
                        <label for="">Telefone</label>
                        <h5 id="showTelefoneFornecedor"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Celular</label>
                        <h5 id="showCelularFornecedor"></h5>
                    </div>
                </div>


                {{-- Centro de custo --}}
                <div class="row" style="margin-top:20px">
                    <div class="col-sm-12">
                        <h5>Centro de Custo</h5>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">Nome</label>
                        <h5 id="showNomeCentroCusto"></h5>
                    </div>
                    <div class="col-sm-6">
                        <label for="">Observação</label>
                        <h5 id="showObsCentroCusto"></h5>
                    </div>
                    
                </div>
                {{-- Fonte de Pagamento --}}
                <div class="row" style="margin-top:20px">
                    <div class="col-sm-12">
                        <h4>Fonte de Pagamento</h4>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">Nome</label>
                        <h5 id="showNomeFontePagamento"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Nome do Responsável</label>
                        <h5 id="showAgenciaFontePagamento"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">CPF/CNPJ</label>
                        <h5 id="showContaFontePagamento"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Tipo</label>
                        <h5 id="showObsFontePagamento"></h5>
                    </div>
                </div>
                {{-- Conta --}}
                <div class="row" style="margin-top:20px">
                    <div class="col-sm-12">
                        <h4>Conta</h4>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">Descrição</label>
                        <h5 id="showDescricaoConta"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Data de Pagamento</label>
                        <h5 id="showDataPagamentoConta"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Data de Vencimento</label>
                        <h5 id="showDataVencimentoConta"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Status</label>
                        <h5 id="showStatusConta"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">Valor Total</label>
                        <h5 id="showValorTotalConta"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Valor Pago</label>
                        <h5 id="showValorPagoConta"></h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Observação</label>
                        <h5 id="showObsConta"></h5>
                    </div>
                </div>
            </div><!-- end modal body-->
            <div class="modal-footer">
                
                <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">

    // Exibe o modal de contas a pagar
    function novaConta(){

        // Limpar Modal
        limparModalConta();
        // Exibe btn add conta
        $("#btnAdicionarConta").css('display','block');
        // Exibe modal
        $("#dlgContas").modal('show');
    }

    $(document).ready(() => {
            // Configuração do ajax com token csrf
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        
        $("#btnAdicionarConta").click(()=>{
            const html = returnHtmlContasPagar();
            $("#divContas").append(html);
        });

        
    });
    function returnHtmlContasPagar(){
        
        const html = `<div class="divRowConta">
                        <hr>
                        <div class="row">
                            <div class="col-sm-10">
                                <h5>Nova Conta</h5>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-secondary-ludke" onclick="excluirRowConta(this)">Excluir</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="">Descrição</label>
                                <input type="text" class="form-control" name="desc[]" id="desc" required>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="">Valor Total</label>
                                <input type="number" class="form-control" name="valorTotal[]" id="valorTotal" step="0.01" required>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="">Data de Pagamento</label>
                                <input type="date" class="form-control" name="dataPagamento[]" id="dataPagamento" required>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="">Data de Vencimento</label>
                                <input type="date" class="form-control" name="dataVencimento[]" id="dataVencimento" required>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="">Observação</label>
                                <textarea class="form-control" name="obsConta[]" id="obsConta" cols="30" rows="2"></textarea>
                            </div>
                        </div>
                    </div>`;

        return html;
    }

    function limparModalConta(){
        $("#id").val("");
        $("#desc").val("");
        $("#valorTotal").val("");
        $("#dataPagamento").val("");
        $("#dataVencimento").val("");
        $("#obsConta").val("");
        $("#centroCusto").val("");
        $("#fornecedor").val("");

        $("#divContas").html("");
        
        $("#divFontePagamento").html("");
    }

    // Remove div nova fonte de pagamento
    function excluirRowConta(btn){
        const divNovaFontePagamento = btn.parentElement.parentElement.parentElement;
        divNovaFontePagamento.remove();
    }
    function salvarConta(){
        const data = {
            id: $("#id").val(),
            fornecedor: $("#fornecedor").val(),
            centroCusto: $("#centroCusto").val(),
            fontePagamento: $("#fontePagamento").val(),
            desc: $("#desc").val(),
            valorTotal: $("#valorTotal").val(),
            dataPagamento: $("#dataPagamento").val(),
            dataVencimento: $("#dataVencimento").val(),
            obsConta: $("#obsConta").val(),
        }
        console.log(data)

        $.ajax({
            url: '/contas/pagar/atualizar/'+data.id,
            type: "POST",
            data: data,
            context: this,
            success: function(callback){
                response = JSON.parse(callback);
                if(response.status === true){
                    alert(response.msg);
                    window.location.href = '/contas/pagar/';
                }else{
                    alert(response.msg);
                }
            },
            error: function(error){
                console.log(error);
            }
        });

    }
    function exibir(id){
        
        $.ajax({
            url: '/contas/pagar/show/'+id,
            type: "GET",
            success: function(callback){
                const dados = JSON.parse(callback);
                // Conta
                $("#showNomeFornecedor").html(dados.fornecedor.nome);
                $("#showNomeRespFornecedor").html(dados.fornecedor.nomeResponsavel);
                $("#showCpfCnpjFornecedor").html(dados.fornecedor.cpfCnpj);
                $("#showEmailFornecedor").html(dados.fornecedor.email);
                $("#showTipoFornecedor").html(dados.fornecedor.tipo);
                $("#showTelefoneFornecedor").html(dados.fornecedor.telefone.residencial);
                $("#showCelularFornecedor").html(dados.fornecedor.telefone.celular);
                // Centro Custo
                $("#showNomeCentroCusto").html(dados.centro_custo.nome);
                $("#showObsCentroCusto").html(dados.centro_custo.obs);
                // Fonte Pagamento
                $("#showNomeFontePagamento").html(dados.fonte_pagamento.nome);
                $("#showAgenciaFontePagamento").html(dados.fonte_pagamento.agencia);
                $("#showContaFontePagamento").html(dados.fonte_pagamento.conta);
                $("#showObsFontePagamento").html(dados.fonte_pagamento.obs);
                // Conta
                $("#showDescricaoConta").html(dados.descricao);
                $("#showDataPagamentoConta").html(formatarData(dados.dataPagamento));
                $("#showDataVencimentoConta").html(formatarData(dados.dataVencimento));
                $("#showStatusConta").html(dados.status == 0 ? "Aguardando" : "Pago");
                $("#showValorTotalConta").html(retornaValorFormatado(dados.valorTotalPgm));
                $("#showValorPagoConta").html(retornaValorFormatado(dados.valorPago));
                $("#showObsConta").html(dados.obs);

                $("#dlgVisualizar").modal("show");
            },
            error: function(error){
                console.log(error);
            }
        });
    }
    function retornaValorFormatado(valor){
        return Number(valor).toLocaleString('pt-BR',{
                    style: 'currency',
                    currency: 'BRL'
                });
    }
    function formatarData(data){
        let arrayData = data.split('-');
        return `${arrayData[2]}/${arrayData[1]}/${arrayData[0]}`;
    }
    function cadastrarConta(){

        const desc = $("[name='desc[]']").map(function(){
            return $(this).val();
        }).get();
        const valorTotal = $("[name='valorTotal[]']").map(function(){
            return $(this).val();
        }).get();
        const dataPagamento = $("[name='dataPagamento[]']").map(function(){
            return $(this).val();
        }).get();
        const dataVencimento = $("[name='dataVencimento[]']").map(function(){
            return $(this).val();
        }).get();
        const obsConta = $("[name='obsConta[]']").map(function(){
            return $(this).val();
        }).get();
        const data = {
            fornecedor: $("#fornecedor").val(),
            centroCusto: $("#centroCusto").val(),
            desc: desc,
            valorTotal: valorTotal,
            dataPagamento: dataPagamento,
            dataVencimento: dataVencimento,
            obsConta: obsConta,
            fontePagamento: $("#fontePagamento").val(),
        }


        $.ajax({
            url: '/contas/pagar/cadastrar',
            type: "POST",
            data: data,
            context: this,
            success: function(callback){
                response = JSON.parse(callback);
                if(response.status === 1){
                    alert(response.msg);
                    window.location.href = '/contas/pagar/';

                }
            },
            error: function(error){
                console.log(error);
            }
        });
    }
    function editarContas(id){
        limparModalConta()
        $.ajax({
            url: '/contas/pagar/show/'+id,
            type: "GET",
            success: function(callback){
                const dados = JSON.parse(callback);
                exibirInfoModalContas(dados)
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function registrarPagamento(id){
        let confirma = confirm("Você tem certeza que deseja finalizar o pagamento?");
        if(confirma){
            
            $.ajax({
            url: '/contas/pagar/registrarPagamento',
            type: "POST",
            data: {id: id},
            success: function(callback){
                let resposta = JSON.parse(callback);
                alert(resposta.msg);
                window.location.href = '/contas/pagar/';
            },
            error: function(error){
                console.log(error);
            }
        });
        }
    }

    /*
    Exibe dados da requisição no modal para editar
    */
    function exibirInfoModalContas(dados){
        $("#id").val(dados.id);
        $("#desc").val(dados.descricao);
        $("#valorTotal").val(dados.valorTotalPgm);
        $("#dataPagamento").val(dados.dataPagamento);
        $("#dataVencimento").val(dados.dataVencimento);
        $("#obsConta").val(dados.obs);
        $("#fornecedor").val(dados.fornecedor_id);
        $("#centroCusto").val(dados.centroCusto_id);
        $("#fontePagamento").val(dados.fontePagamento_id);
        

        let html = `<div class="divRowConta">

                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="">Descrição</label>
                                <input type="text" class="form-control" value="${dados.descricao}" name="desc" id="desc" required>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="">Valor Total</label>
                                <input type="number" class="form-control" value="${dados.valorTotalPgm}" name="valorTotal" id="valorTotal" step="0.01" required>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="">Data de Pagamento</label>
                                <input type="date" class="form-control" value="${dados.dataPagamento}" name="dataPagamento" id="dataPagamento" required>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="">Data de Vencimento</label>
                                <input type="date" class="form-control" value="${dados.dataVencimento}" name="dataVencimento" id="dataVencimento" required>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="">Observação</label>
                                <textarea class="form-control" value="${dados.obs}" name="obsConta" id="obsConta" cols="30" rows="2"></textarea>
                            </div>
                        </div>
                    </div>`;
                    
        // Add informações conta
        $("#divContas").append(html);
        // Remove btn add contas
        $("#btnAdicionarConta").css('display','none');

        // Exibe modal
        $("#dlgContas").modal('show');
    }
    function removerContas(id){
        $.ajax({
            url: '/contas/pagar/deletar/'+id,
            type: "DELETE",
            context: this,
            success: function(callback){
                response = JSON.parse(callback);
                
                alert("Conta removida com sucesso");
                window.location.href = '/contas/pagar/';

                
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    $(document).ready(()=>{
        $("#formContasPagar").submit(function(event){
            event.preventDefault();
            
            if($("#id").val() != ''){
                salvarConta();
            }else{
                cadastrarConta();
            }
        });
    })
</script>
@endsection