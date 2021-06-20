@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-12">
            <div class="titulo-pagina">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="titulo-pagina-nome">
                            <h2>Produtos</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary-ludke" role="button" onclick="novoProduto()">Novo</button>
                    </div>
                    <div class="col-md-4 input-group">
                        {{-- <input id="inputBusca" class="form-control input-ludke" type="text" placeholder="Pesquisar" name="pesquisar"> --}}
                        <form action="{{route('buscarProduto')}}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input name="q" type="text" class="form-control" placeholder="Buscar Produto" aria-label="Recipient's username" aria-describedby="basic-addon2">
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
            <a href="{{route('produtos')}}">
                <button class="btn btn-outline-danger">Listar Todos</button>
            </a>

        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-sm-12">
            @if(isset($produtos))
            <table id="tabelaProdutos" class="table table-hover table-responsive-sm">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Validade (Meses)</th>
                        {{-- <th>Quantidade</th> --}}
                        <th>Preço (R$)</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($produtos as $produto)
                    <tr>
                        <td>{{$produto->id}}</td>
                        <td>{{$produto->nome}}</td>
                        <td>{{$produto->categoria->nome}}</td>
                        <td>{{$produto->validade}}</td>
                        <td>{{$produto->preco}}</td>
                        <td>
                            <a href="#" onclick="editarProduto({{$produto->id}})">
                                <img id="iconeEdit" class="icone" src="{{asset('img/edit-solid.svg')}}" >
                            </a>
                            <a href="#" onclick="removerProduto({{$produto->id}})">
                                <img id="iconeDelete" class="icone" src="{{asset('img/trash-alt-solid.svg')}}" >
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> <!-- end table -->
            <div class="row justify-content-center">
                {{$produtos->render()}}
            </div>
            @else
            <div class="row">
                <div class="col-sm-12 limparBusca">
                    <a href="{{route('produtos')}}">
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

<div class="modal fade" tabindex="-1" role="dialog" id="dlgProdutos">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formProduto" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Novo Produto</h5>
                </div>
                <div class="modal-body">

                    {{-- ID do produto --}}
                    <input type="hidden" id="id" class="form-control">

                    {{-- Nome do produto --}}
                    <div class="form-group">
                        <label for="nomeProduto" class="control-label">Nome do Produto <span class="obrigatorio">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nomeProduto" placeholder="Nome do Produto" autofocus>
                        </div>
                        <div id="validationNome"></div>
                    </div>

                    {{-- Categoria do produto --}}
                    <div class="form-group">
                        <label for="categoriaProduto" class="control-label">Categoria do Produto <span class="obrigatorio">*</span></label>
                        <div class="input-group">
                            <select class="form-control" id="categoriaProduto">
                                <option value="" disabled selected hidden>-- Selecionar Categoria --</option>
                            </select>
                        </div>
                        <div id="validationCategoria"></div>
                    </div>

                    {{-- Validade do Produto --}}
                    <div class="form-group">
                        <label for="validadeProduto" class="control-label">Validade do Produto (Meses) <span class="obrigatorio">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="validadeProduto" placeholder="Validade do Produto">
                        </div>
                        <div id="validationValidade"></div>
                    </div>

                    {{-- Preço do produto --}}
                    <div class="form-group">
                        <label for="precoProduto" class="control-label">Preço do Produto (por Kg) <span class="obrigatorio">*</span></label>
                        <div class="input-group">
                            <input type="text" step="0.01" class="form-control" id="precoProduto" placeholder="Preço do Produto">
                        </div>
                        <div id="validationPreco"></div>
                    </div>

                    {{-- Input Fotos Produto --}}
                    <div class="form-group">
                        <label for="imagensProduto" class="control-label">Selecionar Imagens</label>
                        <div class="input-group">
                            <input type="file" name="imagensProduto[]" class="form-control-file" id="imagensProduto" placeholder="Preço do Produto" multiple>
                        </div>
                        <div id="validationImagensProduto"></div>
                    </div>

                    {{-- foto --}}
                    <div class="row justify-content-center">
                        <div class="fotos col-sm-12">
                            <ul class="listaImagem">
                                {{-- @for($i=0;$i<=5;$i++)
                                <li>
                                    <div id="{{$i}}" class="fotoProduto">
                                        <div class="excluirFoto" onclick="excluirFoto({{$i}})"></div>
                                    </div>
                                </li>
                                @endfor --}}
                            </ul>
                        </div>
                    </div>
                    {{-- Descrição do produto --}}

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

    //essa função é chamada sempre que atualiza a pagina
    $(function(){
        // Configura o ajax para todas as requisições ir com token csrf
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        carregarCategorias();
        // carregarProdutos();

        // Função para aparecer icone de excluir foto
        exibirBotaoExcluirFoto();
        carregarImagens();

        // ao exibir o modal, procura o input com autofocus e seleciona ele
        $('.modal').on('shown.bs.modal',function() {
            $(this).find('[autofocus]').focus();
        });

    });

    //Array contendo os ids das fotos para ser deletado do banco
    arrayIdsDeletarFotos = [];


    //Tira a exibição das fotos e retorna um array com os ids para serem excluidos do banco
    function excluirFoto(id){
        arrayIdsDeletarFotos.push(id);
        foto = document.getElementById(id).style.display="none";
    }

    // Sempre que clica no botão novo, limpa os campos do modal
    function novoProduto(){
        // Limpa campos do modal
        $('#id').val('');
        $('#nomeProduto').val('');
        $('#categoriaProduto').val('');
        $('#validadeProduto').val('');
        $('#imagensProduto').val('');
        $('#precoProduto').val('');
        $(".listaImagem").html("");
        //$('#descricaoProduto').val('');

        $(".span").remove(); //limpa os span de erro

        // exibe modal cadastrar Produtos
        $('#dlgProdutos').modal('show');

    }

    // carrega categorias da api e coloca no select
    function carregarCategorias(){
        $.getJSON('/categorias',function(data){

            for(i=0 ; i<data.length; i++){
                // adiciona cada categoria no select do formulário
                opcao = '<option value="'+data[i].id+'">'+data[i].nome+'</option>';
                $('#categoriaProduto').append(opcao);
            }
        });
    }

    // cria um html da linha da tabela
    function montarLinha(p){
        var linha = "<tr>"+
                        "<td>"+p.id+"</td>"+
                        "<td>"+p.nome+"</td>"+
                        "<td>"+p.categoria.nome+"</td>"+
                        "<td>"+p.validade+"</td>"+
                        // "<td>"+p.quantidade+"</td>"+
                        "<td>"+p.preco+"</td>"+
                        
                        "<td>"+
                            "<a href="+"#"+" onclick="+"editarProduto("+p.id+")"+">"+
                                "<img id="+"iconeEdit"+" class="+"icone"+" src="+"{{asset('img/edit-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                            "<a href="+"#"+" onclick="+"removerProduto("+p.id+")"+">"+
                                "<img id="+"iconeDelete"+" class="+"icone"+" src="+"{{asset('img/trash-alt-solid.svg')}}"+" style="+""+">"+
                            "</a>"+
                        "</td>"+
                    "</tr>";
        return linha;
    }
    function montarLinhaImagem(fotosProduto){
        $(".listaImagem").empty();
        console.log($(".listaImagem").empty());
        for(i=0;i<fotosProduto.length;i++){
            let nomeFoto = fotosProduto[i].path;
            linha = "<li id="+i+" class="+"fotoProduto"+">"+
                                        "<div class="+"excluirFoto"+" onclick="+"excluirFoto("+fotosProduto[i].id+")"+"></div>"+
                                        "<img id="+""+fotosProduto[i].id+""+" class="+"itemFoto"+" src="+"storage/public/"+nomeFoto+">"+
                                    "</li>"
            $(".listaImagem").append(linha);

            }
    }
    function editarProduto(id){
        //console.log("Editar");

        // Limpa o input de imagens, caso alguma imagem tenha sido carregada anteriormente
        $("#imagensProduto").val('');


        console.log(imagensProduto);
        // getJSON já faz o parser do dado recebido para json
        $.getJSON('/produtos/'+id, function(data){
            $('#id').val(data.id);
            $('#nomeProduto').val(data.nome);
            $('#categoriaProduto').val(data.categoria_id);
            $('#validadeProduto').val(data.validade);
            // $('#quantidadeProduto').val(data.quantidade);
            $('#precoProduto').val(data.preco);
           // $('#descricaoProduto').val(data.descricao);

            //$('#validadeProduto').val(novadata);

            let fotosProduto = data.fotosProduto;
            montarLinhaImagem(fotosProduto);
            exibirBotaoExcluirFoto();

            $(".span").remove(); //limpa os span de erro

            $('#dlgProdutos').modal('show');
            // limpa o array contendo o id das imagens para deletar
            arrayIdsDeletarFotos.length = 0;

        });
        // exibe modal cadastrar Produtos

    }
    function confirmaDeletarProduto(id){

        linhasTabela = $("#tabelaProdutos>tbody>tr");//pega linha da tabela

        linha = linhasTabela.filter(function(i,elemento){
            //faz um filtro na linha e retorna a que tiver o id igual ao informado
            if(elemento.cells[0].textContent == id){
                return elemento.cells[1].textContent
            }
        });
        // return nome;

        return confirm("Você tem certeza que deseja remover o produto "+linha[0].cells[1].textContent+"?");
    }
    function removerProduto(id){

        confirma = confirmaDeletarProduto(id);
        // se o usuário confirmar
        if(confirma){
            // faz requisição DELETE para /api/produtos passando o id do produto que deseja apagar
            $.ajax({
                type: "DELETE",
                url: "/produtos/"+id,
                context: this,
                success: function(){
                    // console.log("Deletou");
                    // linhas = $("#tabelaProdutos>tbody>tr");//pega linha da tabela
                    // e = linhas.filter(function(i,elemento){
                    //     return elemento.cells[0].textContent == id;//faz um filtro na linha e retorna a que tiver o id igual ao informado
                    // });
                    // if(e){
                    //     e.remove();// remove a linha
                    // }
                    alert("Produto deletado com sucesso!")
                    window.location.href='/indexProdutos';
                },
                error: function(error){
                    console.log(error);
                }
            });
        }


    }


    // carrega produtos do banco através da api e chama a função montarLinha para colocar na tabela
    function carregarProdutos(){
        $.getJSON('/produtos',function(produtos){
            for(i=0;i<produtos.length;i++){
                // split = produtos[i].validade.split('-');
                // novadata =  split[2] + "/" +split[1]+"/"+split[0];
                // //console.log(novadata);
                // produtos[i].validade = (novadata);
                //console.log(produtos[i].validade);
                linha = montarLinha(produtos[i]);
                $('#tabelaProdutos>tbody').append(linha);
            }
        });
    }
    // exibe botão de excluir na foto
    function exibirBotaoExcluirFoto(){
        $('.fotoProduto').mouseover(function(){
            var idElemento = $(this).attr("id");
            $(this).children().css("display","block");
            // $(this).children().fadeIn(80);
        });
        $('.fotoProduto').mouseout(function(){
            var idElemento = $(this).attr("id");
            $(this).children().first().css("display","none");
            // $(this).children().fadeOut(80);
        });
    }

    // Exibe imagens que foram carregadas na tela
    function carregarImagens(){
        $("#imagensProduto").change(function(){
            //console.log('carregou imagem');
            $(".listaImagem").html("");
            var totalImagens = document.getElementById("imagensProduto").files.length; // número de fotos carregadas no input
            for(i=0;i<totalImagens;i++){
                linha = "<div id="+i+" class="+"fotoProduto"+">"+
                                        "<div class="+"excluirFoto"+" onclick="+"excluirFoto("+i+")"+"></div>"+
                                        "<img class="+"itemFoto"+" src='"+URL.createObjectURL(event.target.files[i])+"'>"+
                                    "</div>"
                $(".listaImagem").append(linha);

                // exibe botão de excluir na foto
                // exibirBotaoExcluirFoto();
            }
        });
    }
    // função para fazer requisição post para o controller
    function criarProduto(){
        // cria um objeto com os dados do form
        // var imagensProduto = document.getElementById("imagensProduto").files;
        // console.log(imagensProduto);

        prod = {
            nome: $('#nomeProduto').val(),
            validade: $('#validadeProduto').val(),
            preco: $('#precoProduto').val(),
            //descricao: $('#descricaoProduto').val(),
            categoriaProduto: $('#categoriaProduto').val(),
            // fotosProduto: imagensProduto


    };
        
        
        
        // cria um FormData para ser enviado ao controller com os dados da requisição presentes no formulário
        let form = document.getElementById('formProduto');
        let formData = new FormData(form);
        formData.append('nome',prod.nome);
        formData.append('validade',prod.validade);
        formData.append('preco',prod.preco);
       // formData.append('descricao',prod.descricao);
        formData.append('categoriaProduto',prod.categoriaProduto);
        // formData.append('fotosProduto',prod.imagensProduto);

        // console.log(formData);
        $.ajax({
            url:'/produtos',
            method:"POST",
            data:formData,
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(produto){

                $("#dlgProdutos").modal('hide'); //esconde o modal após fazer a requisição
                alert("Produto cadastrado com Sucesso!");

                window.location.href = '/indexProdutos';

                // // produto = JSON.parse(data);//converter o dado retornado para JSON ocorrerá um erro, pois o dado retornado é um object
                // linha = montarLinha(produto); //monta a linha html para exibir o novo produto adicionado
                // $('#tabelaProdutos>tbody').append(linha);//injeta a linha na tabela

            },
            error: function(error){
                retorno = JSON.parse(error.responseText);
                exibirErros(retorno.errors);
                console.log(error);
            }
        });
    }

    function exibirErros(error){
        $(".span").remove(); //limpa os span de erro
        if(error){
            if(error.nome){
                for(i=0;i<error.nome.length;i++){
                    console.log(error.nome[i]);
                    $("#validationNome").append("<span class="+"span"+" style="+"color:red"+">"+error.nome[i]+"</span>")
                    // ao exibir o modal, procura o input com autofocus e seleciona ele
                    
                    
                }
            }
            if(error.validade){
                for(i=0;i<error.validade.length;i++){
                    console.log(error.validade[i]);
                    $("#validationValidade").append("<span class="+"span"+" style="+"color:red"+">"+error.validade[i]+"</span>")
                }
            }
            if(error.preco){
                for(i=0;i<error.preco.length;i++){
                    console.log(error.preco[i]);
                    $("#validationPreco").append("<span class="+"span"+" style="+"color:red"+">"+error.preco[i]+"</span>")
                }
            }
            if(error.imagensProduto){
                for(i=0;i<error.imagensProduto.length;i++){
                    console.log(error.imagensProduto[i]);
                    $("#validationImagensProduto").append("<span class="+"span"+" style="+"color:red"+">"+error.imagensProduto[i]+"</span>")
                }
            }

            if(prod.categoriaProduto == null){
                $("#validationCategoria").append("<span class="+"span"+" style="+"color:red"+">O campo categoria é obrigatório.</span>")
            }

        }
    }

    function salvarProduto(){



        var imagensProduto = document.getElementById("imagensProduto").files;

        
        // cria um objeto com os dados do form
        prod = {
            id: $('#id').val(),
            nome: $('#nomeProduto').val(),
            validade: $('#validadeProduto').val(),
            preco: $('#precoProduto').val(),
           // descricao: $('#descricaoProduto').val(),
            categoriaProduto: $('#categoriaProduto').val(),
            arrayIdsDeletarFotos: arrayIdsDeletarFotos,
            // imagensProduto: imagensProduto
        };

        

        // console.log(prod.imagensProduto);
        let form = document.getElementById('formProduto');
        let formData = new FormData(form);

        

        formData.append('id',prod.id);
        formData.append('nome',prod.nome);
        formData.append('validade',prod.validade);
        formData.append('preco',prod.preco);
       // formData.append('descricao',prod.descricao);
        formData.append('categoriaProduto',prod.categoriaProduto);
        formData.append('arrayIdsDeletarFotos',prod.arrayIdsDeletarFotos);


        // console.log("valores do FormData");
        // for(value of formData.values())
        //     console.log(value + typeof(value));
        // for(var value of formData.entries())
        //     console.log(value);

        // formData = formData.serializeArray();


        $.ajax({
            url:'/produtos/'+prod.id,
            method:"POST",
            data:formData,
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(prod){
                    alert("Produto "+prod.nome+" atualizado com sucesso!");
                    window.location.href = '/indexProdutos';
                    // prod = JSON.parse(data); //converte a string data para um objeto json
                    // linhas = $('#tabelaProdutos>tbody>tr'); //pega todas as linhas da tabela
                    // e = linhas.filter(function(i,elemento){//faz uma filtragem e retorna a linha que contem o id do produto atualizado
                    //     return (elemento.cells[0].textContent == prod.id);
                    // });
                    // // console.log(e);
                    // // se encontrou a linha, atualiza cada coluna
                    // if(e){
                    //     e[0].cells[0].textContent = prod.id;
                    //     e[0].cells[1].textContent = prod.nome;
                    //     e[0].cells[2].textContent = prod.categoria.nome;
                    //     e[0].cells[3].textContent = prod.validade;
                    //     // e[0].cells[4].textContent = prod.quantidade;
                    //     e[0].cells[4].textContent = prod.preco;
                    //     e[0].cells[5].textContent = prod.descricao;
                    // }
                    // // limpa o array contendo o id das imagens para deletar
                    // arrayIdsDeletarFotos.length = 0;

                    // $("#dlgProdutos").modal('hide'); //esconde o modal após fazer a requisição
                },
                error: function(error){
                    // limpa o array contendo o id das imagens para deletar
                    arrayIdsDeletarFotos.length = 0;
                    retorno = JSON.parse(error.responseText);
                    exibirErros(retorno.errors);
                    console.log(error);
                }
            });
    }

    // função chamada sempre que a tela é atualizada
    $(function(){
        // função chamada sempre que clica no botão submit do formulário
        $('#formProduto').submit(function(event){
            event.preventDefault(); // não deixa fechar o modal quando clica no submit

            if($('#id').val()!= ''){
                // limparArrayIdsFotos();
                salvarProduto();// função chamada para editar produto
            }
            else{
                // limparArrayIdsFotos();
                criarProduto();// função que faz a requisição para o controller
            }
            // $("#dlgProdutos").modal('hide'); //esconde o modal após fazer a requisição
        });
    });
/*    
  $(document).ready(function($){
    $("#precoProduto").keypress(function(){
        $("#precoProduto").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', 
        decimal:'.', affixesStay: false});
    });
  });*/
</script>

@endsection
