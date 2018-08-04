window.onload = function(){
    //var selectCont = document.getElementsByClassName("pradio");
    //var sl = selectCont.length;
    //var i = 0;
    
    var openNewPage = function(e){  
        for ( i =0; i < e.length ; i++ )
        {
            e[i].onclick = function(){
                if (this.checked){
                    window.location.href = this.getAttribute('data-href');
                }
            }
        }
    }
    openNewPage(document.getElementsByClassName("pradio"));    
}
