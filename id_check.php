<?php require_once('Connections/connection.php'); ?>
<?php require_once('Connections/function.php'); ?>
<?php
//echo "進入程式id_check";

// 選擇 MySQL 資料庫register
mysqli_select_db($connection,'register') or die('資料庫register不存在'); 

// 新會員輸入的帳號
$check_name = "-1";
if (isset($_GET['patient_id'])) {
  $check_name = $_GET['patient_id'];
}

// 查詢病人身分證號是否已經存在？
$query = sprintf("SELECT * FROM patient WHERE patient_id = %s", GetSQLValue($check_name, "text"));
$result = mysql_query($query) or die(mysqli_error());

// 結果集的記錄筆數
$totalRows = mysql_num_rows($result);

if ($totalRows > 0)
{
  $row  = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $yyyymmdd = substr($row['birthday'],0,4) . substr($row['birthday'],5,2) . substr($row['birthday'],8,2);
  if (isset($_GET['birthday'])) 
  {
    if ( $yyyymmdd != $_GET['birthday'] )
      echo 1;//生日有誤
    else
      echo 0;//正常
  }else{
    echo "c";//日期未傳送成功
  }   
    
}else{
  echo 2;//查無此病患
}
// 釋放結果集
mysql_free_result($result);
?>