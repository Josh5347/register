<?php require_once('Connections/connection.php'); ?>
<?php require_once('Connections/function.php'); ?>
<?php 

date_default_timezone_set('Asia/Taipei');

// 建立 session
if (!isset($_SESSION)) {
  session_start();
}

require_once('inquire_fun.php');

// insert = patient_reg 表示 verify.js 已通過所有輸入檢核，可以進行掛號或查詢程序
if ((isset($_POST["insert"])) && ($_POST["insert"] == "patient_reg")) 
{
  $_SESSION['patient_id'] = $_POST['pid'];  
  $inquire =& inquire();

  if ( $_POST['inquire_flg'] == false )
    $reg_done = "查無掛號資料";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::網路掛號::</title>
<link href="CSS/index.css" rel="stylesheet" type="text/css" />
<link href="CSS/reg_confirm.css" rel="stylesheet" type="text/css" />
<link href="CSS/inquire.css" rel="stylesheet" type="text/css" />
<script src="JavaScript/reg_confirm.js" type="text/javascript"></script>
<script src="JavaScript/verify.js" type="text/javascript"></script>
<script src="JavaScript/SpryData.js" type="text/javascript"></script>
</head>
<body>
<table class="table1">
    <tr>
      <td class="index_style2">
	     <span class="hospital">
        康健綜合醫院 - 網路掛號            
        </span>          
       </td>
    </tr>
    <tr>
     <td class="index_style2">
	      <table class="table2">
          <tr class="menu">
            <td class="menu_top"><a class="link1" href="index.html">網　路　掛　號</a></td>
            <td class="menu_top_now"><a class="link1" href="inquire.php">查　詢 / 取　消　掛　號</a></td>
            <td class="menu_top"><a class="link1" href="clinic_hour.html">門　診　時　間　表</a></td>
            <td class="menu_top"><a class="link1" >醫　師　介　紹</a></td>
          </tr>            
        </table>      
     </td>
    </tr>
    <tr>
      <td>
        <span class="index_style6">請輸入基本資料</span>
      </td>
    </tr>
    <br />
    <?php require_once("inquire_bottom.php"); ?>
        <br />
        <br />
        <br />
        <span class="bottom_text1">康健綜合醫院</span><br />
        <span class="bottom_text1">71742 台南市永康區中山南路539號 電話：(06) 2394029 傳真：(06) 2396394</span><br />
        <span class="bottom_text1">本網站建議使用IE7.0以上瀏覽器，最佳解析度為1024×768</span>    
      </td>
    </tr>
</table>

</body>
</html>
