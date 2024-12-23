timeout = 0
var reqajx = function() {
    $.ajax({
        url: "/Controller/RamalController.php",
        type: "GET",
        data: {funcao:'dashboard'},
        success: function( result){             
            data =  JSON.parse(result)
            $('.cartao').remove();
            $('.cartao-indisponivel').remove();
            for(let i in data.data){
                $('#cartoes').append(`<div class="cartao`+(data.data[i].status == "indisponivel"? '-indisponivel': '')+`">
                                    <div>${data.data[i].nome}</div>
                                    <span class="${data.data[i].status} icone-posicao"></span>
                                  </div>`)
            }

            if (data.erro) {

                for(let i in data.erros){
                    $('#erros').append(`<div class="row erro">
                                        <p class="row">${data.erros[i]}</p>
                                        <span class="ocupado icone-posicao"></span>
                                      </div>`)
                }
                setTimeout(() => {
                $('.erro').remove();
                    
                }, 10000);
            }
            timeOut = setTimeout(reqajx, 10000)
        },
        error: function(){
            console.log("Errouu!")
            timeOut = setTimeout(reqajx, 10000)
        }
    });
    
}
reqajx()