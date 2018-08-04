function verify(){
  var pid_str = document.getElementById("pid");
  var year= document.getElementById("birth_year");
  var mon = document.getElementById("selectmonth");
  var day = document.getElementById("selectday");
  var today = new Date();

  if (!pid_str.value.replace( /^(\s|\u00A0)+|(\s|\u00A0)+$/g, ""))
  {  
    alert("身分證號不可為空!");
    return false;
  }
  if(pid_str.value.replace( /^[A-Z]\d{9}$/, ""))
  {
    alert("身分證號有誤!");
    return false;   
  }
  if( !year.value.replace(/\D/g,"" ) ){
    alert("西元年須為數字");
    return false;
  }
  if ( year.value < 1910 ||
       year.value > today.getFullYear()) 
  {
    alert("請輸入正確西元年");
    return false;
  }
  if ( mon.value == "請選擇" )
  {  
    alert("請選擇出生月日");
    return false;    
  }

  //月份轉為字串
  if ( mon.value < 10 )
    var mon_str = "0" + mon.value;
  else 
    var mon_str = mon.value;

  var yyyymmdd = year.value + mon_str + day.value;

  // 檢查身分證號是否已經存在?生日是否正確
    checkIDExist(document.getElementById("pid").value, yyyymmdd);
    return true;
    
}
function checkIDExist(patient_id,birthday_yyyymmdd)
{
  var objUserData = new Object;
  objUserData.patient_id = patient_id;	
  objUserData.birthday = birthday_yyyymmdd;  
  
	// 呼叫伺服端的patient_id_check.php, 在網址後加上使用者輸入的帳號
	var req = Spry.Utils.loadURL("GET","id_check.php?patient_id="+patient_id+"&birthday="+birthday_yyyymmdd, false, myCallBack,{userData: objUserData});
}
// 在收到伺服器的反應後，這個callback函數就會被觸發
function myCallBack(req) 
{
  //  
  var result = req.xhRequest.responseText; 
  
  //alert ("*"+result+"*");
  //alert (document.getElementById("newCheck").checked);
  //初診病患
  if (document.getElementById("newCheck").checked == true)
  {
    
    if ( result < 2 )
    {      
      alert(req.userData.patient_id + "\r\n此身分證號已非初診病患, 請您重新輸入"); 
      document.getElementById("insert").value = "";
    } //復診病患
  }else{
    if (result == 2 ) 
    {
      alert(req.userData.patient_id + "\r\n查無病歷資料, 或請您改選初診掛號"); 
      document.getElementById("insert").value = "";
    }
    if (result == 1 )
    {
      alert("病患生日" + req.userData.birthday + "有誤");
      document.getElementById("insert").value = "";
    }
  }
    
  
}