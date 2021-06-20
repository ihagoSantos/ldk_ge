
<nav class="navbar navbar-expand-md navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home')}}">
            <img id="logo" src="{{asset('img/logo.svg')}}" style="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->

            {{-- @if(Auth::check()) --}}
            {{-- @can('view_admin', Auth::user()) --}}
                <ul class="navbar-nav mr-auto">
                    {{-- Inicio --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('home')}}">Início</a>
                    </li>
                    {{-- Cruds --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"  id="navbarDropdownGerenciar" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Gerenciar
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('produtos')}}">Produtos</a>
                            <a class="dropdown-item" href="{{route('categorias')}}">Categorias</a>
                            <a class="dropdown-item" href="{{route('clientes')}}">Clientes</a>
                            <a class="dropdown-item" href="{{route('funcionarios')}}">Funcionários</a>
                            <a class="dropdown-item" href="{{route('cargos')}}">Cargos</a>
                            <a class="dropdown-item" href="{{route('fornecedores')}}">Fornecedores</a>
                            <a class="dropdown-item" href="{{route('centroCusto')}}">Centro de Custo</a>
                            <a class="dropdown-item" href="{{route('fontePagamento')}}">Fonte de Pagamento</a>
                        </div>
                    </li>
                    {{-- Pedidos --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"  id="navbarDropdownGerenciar" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pedidos
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('pedidos')}}">Novo Pedido</a>
                            <a class="dropdown-item" href="{{route('listarPedidos')}}">Listar Pedidos</a>
                        </div>
                    </li>
                    {{-- <li class="nav-item">

                        <a class="nav-link" href="{{route('listarPedidos')}}">Pedidos</a>
                    </li> --}}
                    {{-- Vendas --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"  id="navbarDropdownGerenciar" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Vendas
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('vendas')}}">Nova Venda</a>
                            <a class="dropdown-item" href="{{route('listarVendas')}}">Listar Vendas</a>
                        </div>
                    </li>
                    {{-- Relatórios --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"  id="navbarDropdownGerenciar" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Relatórios
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('relatorioCliente')}}" target="_blank">Clientes</a>
                            <a class="dropdown-item" href="{{route('relatorioProdutos')}}" target="_blank" >Produtos</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#filtroRelatorioPedidos">Pedidos</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#filtroRelatorioVendas">Vendas</a>
                        </div>
                    </li>
                    {{-- Contas --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"  id="navbarDropdownGerenciar" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Contas
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('contas.receber')}}">Contas a receber</a>
                            <a class="dropdown-item" href="{{route('contas.pagar')}}">Contas a pagar</a>
                        </div>
                    </li>
                    {{-- Ajuda --}}
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ajuda</a>
                    </li>
                </ul>
            {{-- @endcan --}}


            
            {{-- @endif --}}
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
{{--
@if(Auth::user()->can('view_gerenteGeral', Auth::user())
   || Auth::user()->can('view_gerenteAdmin', Auth::user())
   || Auth::user()->can('view_vendedor', Auth::user())
   || Auth::user()->can('view_secretaria', Auth::user())
   || Auth::user()->can('view_salsicheiro', Auth::user())
   )
--}}
{{-- Modal Filtro Relatório Produtos --}}
<div class="modal fade" id="filtroRelatorioPedidos" tabindex="-1" role="dialog" aria-labelledby="filtroRelatorioProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Gerar Relatório de pedidos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="relatorioGeralPedidos" action="{{route('relatorioGeralPedidos')}}" method="POST" target="_blanck">
        <div class="modal-body">
            <div class="alert alert-secondary" role="alert">
                Para gerar o relatório com todas as informações, deixe o <strong>filtro</strong> em branco!
              </div>
                @csrf
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioNomeCliente">Nome do Cliente</label>
                        <input type="text" class="form-control" id="filtroRelatorioNomeCliente" name="filtroRelatorioNomeCliente" placeholder="Nome do Cliente">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioNomeReduzido">Nome Reduzido</label>
                        <input type="text" class="form-control" id="filtroRelatorioNomeReduzido" name="filtroRelatorioNomeReduzido" placeholder="Nome Reduzido">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioDataEntregaInicial">Data de Entrega Inicial</label>
                        <input type="date" class="form-control" id="filtroRelatorioDataEntregaInicial" name="filtroRelatorioDataEntregaInicial">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioDataEntregaFinal">Data de Entrega Final</label>
                        <input type="date" class="form-control" id="filtroRelatorioDataEntregaFinal" name="filtroRelatorioDataEntregaFinal">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioStatus_id">Status</label>
                        <select class="form-control" name="filtroRelatorioStatus_id" id="filtroRelatorioStatus_id">
                            <option value="" disabled selected>-- STATUS --</option>
                            <option value="1">SOLICITADO</option>
                            <option value="2">PESADO</option>
                            <option value="3">ENTREGUE</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioEntregador">Entregador</label>
                        <select class="form-control" name="filtroRelatorioEntregador" id="filtroRelatorioEntregador">
                            <option value="" disabled selected>-- ENTREGADOR --</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Gerar Relatório</button>
            </div>
        </form>
      </div>
    </div>
  </div>


  {{-- Modal Filtro Relatório Vendas --}}
<div class="modal fade" id="filtroRelatorioVendas" tabindex="-1" role="dialog" aria-labelledby="filtroRelatorioVendasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Gerar Relatório de vendas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="relatorioGeralVendas" action="{{route('relatorioGeralVendas')}}" method="POST" target="_blanck">
        <div class="modal-body">
            <div class="alert alert-secondary" role="alert">
                Para gerar o relatório com todas as informações, deixe o <strong>filtro</strong> em branco!
              </div>
                @csrf
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioVendasNomeCliente">Nome do Cliente</label>
                        <input type="text" class="form-control" id="filtroRelatorioVendasNomeCliente" name="filtroRelatorioVendasNomeCliente" placeholder="Nome do Cliente">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioVendasNomeReduzido">Nome Reduzido</label>
                        <input type="text" class="form-control" id="filtroRelatorioVendasNomeReduzido" name="filtroRelatorioVendasNomeReduzido" placeholder="Nome Reduzido">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioVendasDataEntregaInicial">Data de Entrega Inicial</label>
                        <input type="date" class="form-control" id="filtroRelatorioVendasDataEntregaInicial" name="filtroRelatorioVendasDataEntregaInicial">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioVendasDataEntregaFinal">Data de Entrega Final</label>
                        <input type="date" class="form-control" id="filtroRelatorioVendasDataEntregaFinal" name="filtroRelatorioVendasDataEntregaFinal">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioVendasStatus_id">Status</label>
                        <select class="form-control" name="filtroRelatorioVendasStatus_id" id="filtroRelatorioVendasStatus_id">
                            <option value="" disabled selected>-- STATUS --</option>
                            <option value="1">SOLICITADO</option>
                            <option value="2">PESADO</option>
                            <option value="3">ENTREGUE</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="filtroRelatorioVendasEntregador">Entregador</label>
                        <select class="form-control" name="filtroRelatorioVendasEntregador" id="filtroRelatorioVendasEntregador">
                            <option value="" disabled selected>-- ENTREGADOR --</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Gerar Relatório</button>
            </div>
        </form>
      </div>
    </div>
  </div>

<script type="application/javascript">

    $(document).ready(function(){
        // Relatório de Pedidos
        $("#filtroRelatorioPedidos").on('show.bs.modal',function(){
            // Limpa Inputs modal
            $("#filtroRelatorioNomeCliente").val("");
            $("#filtroRelatorioNomeReduzido").val("");
            $("#filtroRelatorioDataEntregaInicial").val("YYYY-MM-DD");
            $("#filtroRelatorioDataEntregaFinal").val("YYYY-MM-DD");
            $("#filtroRelatorioStatus_id").val("");
            $("#filtroRelatorioEntregador").html('<option value="" disabled selected>-- ENTREGADOR --</option>');

            // Busca todos os funcionários que podem entregar pedido
            $.getJSON('/getEntregadores', function(entregadores){

                entregadores.forEach(entregador => {
                    $("#filtroRelatorioEntregador").append(`<option value="${entregador.id}">${entregador.user.name}</option>`)
                });
            });
        });

        // Relatório de vendas
        $("#filtroRelatorioVendas").on('show.bs.modal',function(){
            // Limpa Inputs modal
            $("#filtroRelatorioVendasNomeCliente").val("");
            $("#filtroRelatorioVendasNomeReduzido").val("");
            $("#filtroRelatorioVendasDataEntregaInicial").val("YYYY-MM-DD");
            $("#filtroRelatorioVendasDataEntregaFinal").val("YYYY-MM-DD");
            $("#filtroRelatorioVendasStatus_id").val("");
            $("#filtroRelatorioVendasEntregador").html('<option value="" disabled selected>-- ENTREGADOR --</option>');

            // Busca todos os funcionários que podem entregar pedido
            $.getJSON('/getEntregadores', function(entregadores){

                entregadores.forEach(entregador => {
                    $("#filtroRelatorioVendasEntregador").append(`<option value="${entregador.id}">${entregador.user.name}</option>`)
                });
            });
        });
    });

</script>
