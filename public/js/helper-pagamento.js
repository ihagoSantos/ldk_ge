// Contador utilizado para excluir linha com forma de pagamento
let countFormaPagamento = 0;

/**
 * Função que desabilita data de vencimento quando o tipo de pagamento for "à vista" 
 * @param {ThisParameterType} select 
 */
function disableDataVencimento(select) {
    // Caso o pagamento seja 'a vista'
    let rowForm = select.parentElement.parentElement;
    let colDate = rowForm.childNodes[3];
    let inputDate = colDate.childNodes[1];
    console.log(inputDate);
    if (select.value == 1) {
        inputDate.required = false;
        colDate.style.display = "none";
        let date = new Date();
        let now = date.getFullYear().toString() + '-' + (date.getMonth() + 1).toString().padStart(2, 0) + '-' + date.getDate().toString().padStart(2, 0);
        inputDate.value = now;

    } else {
        inputDate.required = true;
        inputDate.value = '';
        colDate.style.display = "block";

    }
}


/**
 * Função que verifica se o valor do pagamento é menor que o valor Total
 * @param {Float} contValorTotalPagamento 
 * @param {Float} valorTotal 
 */
function validPayment(contValorTotalPagamento, valorTotal) {
    //console.log(validaValorPagamento);
    valorTotal = Math.round(valorTotal * 100) / 100
    console.log(valorTotal);
    if (contValorTotalPagamento > valorTotal) {
        return false;
    } else {
        return true;
    }

}

/**
 * Cria a linha com os inputs da nova forma de pagamento
 * @param {String} today 
 * @return {String} form
 */
function addFormaDePagamento(today) {

    // console.log("Nova Forma de Pagamento")
    let form = "<div id='formaPagamento" + countFormaPagamento + "'>" +
        "<div class='row informacoes'>" +
        "<div class='col-sm-10'>" +
        "<h3>Informações do Pagamento</h3>" +
        "</div>" +
        "<div class='col-sm-2'>" +
        "<button class='btn btn-secondary-ludke' onclick='excluirFormaPagamento(" + countFormaPagamento + ")'>Excluir</button>" +
        "</div>" +
        "</div>" +
        "<div class='row justify-content'>" +
        "<div class='col-sm-3 form-group'>" +
        "<label for='formaPagamento'>Tipo de Pagamento <span class='obrigatorio'>*</span></label>" +
        "<select name='formaPagamento[]' class='form-control' id='formaPagamento' onChange='disableDataVencimento(this)' required>" +
        "<option value='' disabled>-- Tipo de Pagamento --</option>" +
        optionsFormaPagamento() +
        "</select>" +
        "<span style='color:red' id='spanformaPagamento'></span>" +
        "</div>" +
        "<div class='col-sm-3 form-group'>" +
        "<label for='valorTotalPagamento'>Valor (R$) <span class='obrigatorio'>*</span></label>" +
        "<input type='number' id='valorTotalPagamento' min='0' step='0.01' onkeyup='validaValorPagamento()' class='form-control' name='valorTotalPagamento[]' required>" +
        "<span style='color:red' id='spanValorPago'></span>" +
        "</div>" +
        "<div class='col-sm-3 form-group'>" +
        "<label for='descontoPagamento'>Desconto %</label>" +
        "<input id='descontoPagamento' type='number' class='form-control' value='0' min='0' max='100' name='descontoPagamento[]' disabled>" +
        "<span style='color:red' id='spanDescontoPagamento'></span>" +
        "</div>" +
        "<div class='col-sm-3 form-group' style='display: none'>" +
        "<label for='dataVencimento'>Data de Vencimento</label>" +
        "<input type='date' class='form-control' id='dataVencimento' name='dataVencimento[]' value='" + today + "'>" +
        "<span style='color:red' id='spanDataVencimento'></span>" +
        "</div>" +
        "</div>" +
        "<div class='row justify-content-center'>" +
        "<div class='col-sm-12 form-group'>" +
        "<label for='obs'>Observações</label>" +
        "<textarea class='form-control' name='obs[]' id='' rows='5'></textarea>" +
        "</div>" +
        "</div>" +
        "</div>";
    countFormaPagamento += 1;
    return form;
}

// Ao clicar no botão excluir, retira os inputs referente à forma de pagamento
function excluirFormaPagamento(id) {
    id = "formaPagamento" + id;
    $(`#${id}`).remove();
}

// Função utilizada no formulário de pagamento de pedidos e vendas
$(function() {
    $('#formPagamento').submit(function(event) {

        // Contador para armazenar o valor adicionado em todas as formas de pagamento. 
        let contValorTotalPagamento = 0;

        // Mapeia todos os inputs do valor em cada forma de pagamento em um array
        let arrayValorTotalPagamento = $('input[name="valorTotalPagamento[]"').map(function() {
            return parseFloat(this.value);
        }).get();

        // Percore o array e soma todas as posições
        arrayValorTotalPagamento.forEach(valor => {
            contValorTotalPagamento += valor
        });

        if (!isValid()) {
            // $("#formPagamento").submit();
            event.preventDefault();

            $("#divNovaFormaPagamento:not(:has(>div))").each(function() {
                alert("Por favor, selecione uma forma de pagamento!");
            });
        }
        // Verifica se contValorTotalPagamento é maior que valorTotal
        if (validPayment(contValorTotalPagamento, valorTotal) == false) {
            // impede o envio do form
            event.preventDefault();
            alert("O valor informado é maior do que o valor total! Verifique os valores informados.");
        }
        if (contValorTotalPagamento < valorTotal) {
            // impede o envio do form
            event.preventDefault();
            //alerta de erro
            alert("O valor informado é menor do que o valor total! Uma nova forma de pagamento será adicionada.")

            // Adiciona nova forma de pagamento ao formulário
            var linhaForm = addFormaDePagamento(today);
            $("#divNovaFormaPagamento").append(linhaForm);

            // Adiciona o valor restante em na ultima forma de pagamento adicionada
            var correcaoValor = valorTotal - contValorTotalPagamento;
            $('input[name="valorTotalPagamento[]"').last().val(parseFloat(correcaoValor.toFixed(2)));
        }
    });

    // Ao clicar no botão "Adicionar Forma de Pagamento" Adiciona na tela os inputs da nova forma de pagamento
    $('#bntNovaFormaPagamento').click(function() {
        // alert('Adicionar Forma de pagamento')
        var linhaForm = addFormaDePagamento(today);
        $("#divNovaFormaPagamento").append(linhaForm);
    });


});


// Função que percorre os valores de entrada nos pagamentos e verifica se é maior que o valor do 
//pedido.
function validaValorPagamento() {
    // Valor Total do pedido
    // let valorTotal = <?php echo $valorTotalDoPagamento; ?>
    // Contador para armazenar o valor adicionado em todas as formas de pagamento. 
    let contValorTotalPagamento = 0;

    // Mapeia todos os inputs do valor em cada forma de pagamento em um array
    let arrayValorTotalPagamento = $('input[name="valorTotalPagamento[]"').map(function() {
        return parseFloat(this.value);
    }).get();

    // Percore o array e soma todas as posições
    arrayValorTotalPagamento.forEach(valor => {
        contValorTotalPagamento += valor
    });

    if (contValorTotalPagamento > valorTotal) {
        alert("O valor total informado no pagamento é maior do que o valor total do pedido! Por favor, informe novamente os valores.");
        $('input[name="valorTotalPagamento[]"').val('');
    } else {
        console.log(`Pagamento: ${contValorTotalPagamento} | Valor Total: ${valorTotal}`);

    }
}

// monta as linhas dos <option> com as formas de pagamento para serem exibidas no select
function optionsFormaPagamento() {
    let options = "";
    formasPagamento.forEach(element => {
        options += `<option value="${element.id}">${element.nome}</option>`;
    });
    return options;
}

function isValid() {
    let isValid = true;
    // if($('#dataVencimento').val() == ""){
    //     isValid = false;
    //     $("#spanDataVencimento").html("Selecione a Data de Vencimento")
    // }
    // if($('#dataVencimento').val() != ""){
    //     $("#spanDataVencimento").html("")
    // }
    // if($('#dataPagamento').val() == ""){
    //     isValid = false;
    //     $("#spanDataPagamento").html("Selecione a Data de Pagamento")
    // }
    // if($('#dataPagamento').val() != ""){
    //     $("#spanDataPagamento").html("")
    // }
    if ($('#formaPagamento').val() == null) {
        isValid = false;
        $("#spanformaPagamento").html("Selecione o Tipo de Pagamento")
    }
    if ($('#formaPagamento').val() != null) {
        $("#spanformaPagamento").html("")
    }
    if ($('#valorPago').val() == "") {
        isValid = false;
        $("#spanValorPago").html("Preencha o Valor do Pagamento")
    }
    if ($('#valorPago').val() != "") {
        $("#spanValorPago").html("")
    }
    if ($('#descontoPagamento').val() == "") {
        isValid = false;
        $("#spanDescontoPagamento").html("Preencha o Desconto do Pagamento")
    }
    if ($('#descontoPagamento').val() != "") {
        $("#spanDescontoPagamento").html("")
    }

    return isValid;
}

// calcula o desconto
function calcularDesconto(valorTotal, valorDoDescontoNoPedido) {
    let desconto = $('#descontoPagamento').val();
    if (desconto > 100) {
        alert('Não é possível aplicar um desconto maior do que 100%');
        return null;
    } else {
        return (valorTotal * (desconto / 100)) + valorDoDescontoNoPedido;

    }
}

// atualiza o valor do desconto ao inserir o desconto
function atualizarValorDesconto(valorTotal, valorDoDescontoNoPedido) {

    let valorDesconto = calcularDesconto(valorTotal, valorDoDescontoNoPedido);
    let inputDesconto = $('#descontoPagamento').val();

    if (valorDesconto != null) {
        // valorDesconto = valorDesconto + valorDoDescontoNoPedido;
        // Atualiza na tela o valor do desconto
        $('#valorDesconto').html(valorDesconto);
    } else {
        $('#descontoPagamento').val(0);
        $('#valorDesconto').html(valorDoDescontoNoPedido);
    }
}

// atualizaValorParcialmentePago
// atualiza o valor pago no pedido PARCIALMENTE PAGO
function atualizaValorParcialmentePago(valorTotal, valorPagoPedido) {
    let valorPago = $('#valorPago').val();

    if (valorPago > valorTotal) {
        alert(`Você não pode inserir um valor maior do que o valor do pedido: R$ ${valorTotal}`);
        $('#valorPago').val('');
        $('#valorTotalPago').html(valorPagoPedido);
    } else {
        $('#valorTotalPago').html(valorPago);
    }
}

// atualiza o valor pago
function atualizaValorPago(valorTotal) {
    let valorPago = $('#valorPago').val();
    let valorComDesconto = valorTotal - calcularDesconto(valorTotal)
    if (valorPago > valorComDesconto) {
        alert(`Você não pode inserir um valor maior do que o valor do pedido: R$ ${valorComDesconto}`);
        $('#valorPago').val('');
        $('#valorTotalPago').html(0);
    } else {
        $('#valorTotalPago').html(valorPago);
    }
}