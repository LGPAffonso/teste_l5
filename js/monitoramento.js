$.ajax({
    url: "http://l5d1137.callbox.com.br/monitoramento/lib/ramais.php",
    type: "GET",
    success: function(data){                
        for(let i in data){
            $('#cartoes').append(`<div class="cartao">
                                <div>${data[i].nome}</div>
                                <span class="${data[i].status} icone-posicao"></span>
                              </div>`)
        }
        
    },
    error: function(){
        console.log("Errouu!")
    }
});