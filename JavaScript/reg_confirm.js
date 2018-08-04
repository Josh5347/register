window.onload = function(){
  document.getElementById("phone_div1").style.visibility = "hidden";
  document.getElementById("phone_div2").style.visibility = "hidden";  
}
days = new Array(0,31,29,31,30,31,30,31,31,30,31,30,31);
function changeday(selectID, amonth)
{
    while (document.all(selectID).options.length > 0) document.all(selectID).remove(0);
    for (var i = 1; i <= days[amonth]; i++)
    {
      var nOption = document.createElement("OPTION");
      nOption.text=i;
      //將日轉為2位數字串
      if ( i < 10 )
        nOption.value = "0" + i;
      else 
        nOption.value = i.toString();

      document.all(selectID).add(nOption);
    }
}
function disp_phone(){
  if (document.getElementById("newCheck").checked == true){
    document.getElementById("phone_div1").style = "visible";
    document.getElementById("phone_div2").style = "visible";
  }
  else{
    document.getElementById("phone_div1").style.visibility = "hidden";
    document.getElementById("phone_div2").style.visibility = "hidden";    
  }
}
