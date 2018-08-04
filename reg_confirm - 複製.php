<?php require_once('Connections/connection.php'); ?>
<?php require_once('Connections/function.php'); ?>
<?php
date_default_timezone_set('Asia/Taipei');

// 建立 session
if (!isset($_SESSION)) {
  session_start();
}

if ((isset($_POST["insert"])) && ($_POST["insert"] == "patient_reg")) 
{
  //新增初診資料成功與否
  $reg_new_chk = true;
  mysqli_select_db($connection,'register') or die('資料庫register不存在');
  
  //初診病患，新增patient資料
  if($_POST['new_check'] == "on")
  {
    //產生insert用生日字串
    if ( $_POST['birth_mon'] < 10 )
      $mon_str = "0" . $_POST['birth_mon'];
    else 
      $mon_str = $_POST['birth_mon'];
    
    $yyyymmdd = $_POST['birth_year'] . $mon_str . $_POST['birth_day'];
    
    $query = sprintf("INSERT INTO patient ( patient_id, birthday, phone) VALUES (%s, %s, %s)", 
    GetSQLValue($_POST['pid'], "text"),
    GetSQLValue($yyyymmdd, "date"),
    GetSQLValue($_POST['phone'], "text"));
    
    $result_new = mysqli_query($connection, $query);
    
    if (!$result_new) {
      $error_str = mysqli_error();
      $reg_done = "初診掛號失敗". $error_str;
      $reg_new_chk = false;//新增初診資料失敗
    }

  }

  //複診及初診新增資料成功
  if ($reg_new_chk == true)
  {
    $_SESSION['patient_id'] = $_POST['pid'];
    $query = sprintf("INSERT INTO room_info (date, am_pm, room, doctor, patient_id, register_no, week_day) VALUES (%s, %s, %s, %s, %s, %s, %s)", 
    GetSQLValue($_SESSION['sql_date'], "date"),
    GetSQLValue($_SESSION['am_pm'], "text"),  
    GetSQLValue($_SESSION['room'], "text"),  
    GetSQLValue($_SESSION['doctor'], "text"),  
    GetSQLValue($_POST['pid'], "text"),  
    GetSQLValue($_SESSION['register_no'], "text"),
    GetSQLValue($_SESSION['week_day'], "text"));

      // 傳回結果集
    $result = mysqli_query($connection, $query);
    
    if ($result) {
      $reg_done = "掛號成功";
    }else{
      if (mysql_errno()==1062)
        $error_str = ":當日醫師重複掛號";
      $reg_done = "掛號失敗". $error_str;
    }
    
    echo $_POST['inquire_flg'];
    //查詢今天以後的掛號資料 
    if ($result == true || 
        $_POST['inquire_flg'] == true){

      $today = date( "Ymd", time());

      $query = sprintf("SELECT room_info.*, clinic_hour.department FROM room_info INNER JOIN clinic_hour 
      ON room_info.am_pm = clinic_hour.am_pm AND room_info.room = clinic_hour.room AND room_info.week_day = clinic_hour.week_day 
      WHERE room_info.patient_id = %s;",GetSQLValue($_SESSION['patient_id'],"text"));

      $result_inquire = mysqli_query($connection, $query) or die(mysqli_error());
      
      $_POST['inquire_flg'] = true;

      class RoomStruct{
        public $date;
        public $am_pm;
        public $room;
        public $doctor;
        public $register_no;
        public $deptment;
      }
     
      $num = 0;
      while ( $row = mysql_fetch_assoc($result_inquire)){
      
        $inquire[$num] = new RoomStruct();
        $inquire[$num]->date      = $row['date'];
        $inquire[$num]->am_pm     = $row['am_pm'];
        $inquire[$num]->room      = $row['room'];      
        $inquire[$num]->doctor    = $row['doctor'];
        $inquire[$num]->register_no = $row['register_no'];
        $inquire[$num]->department  = $row['department'];
        
        $num++;
      }
    }
    
  }
}


$value = "";
$i = 0;
if (isset($_POST["reg_key"]))
{
  foreach ($_POST["reg_key"] as $value) 
  {
    switch ($i)	
    {
    case 0 : 
      $_SESSION['sql_date'] = $value;    
      break;
    case 1 : 
      $_SESSION['am_pm']    = $value;    
      break;
    case 2 : 
      $_SESSION['room']     = $value;    
      break;
    case 3 : 
      $_SESSION['doctor']   = $value;    
      break;
    case 4 : 
      $_SESSION['register_no']= $value + 1;    
      break;
    }  
    //echo $value . "<br />" ;
    $i++;
  }	
  //產生星期
  $_SESSION['week_day'] = date("N",mktime(0, 0, 0,substr($_SESSION['sql_date'],4,2),substr($_SESSION['sql_date'],6,2),substr($_SESSION['sql_date'],0,4)));
  //echo substr($_SESSION['sql_date'],4,2).substr($_SESSION['sql_date'],6,2).substr($_SESSION['sql_date'],0,4)."<br />";  
  //echo $_SESSION['$week_day']."<br />";
}
//echo $_SESSION['sql_date'] . "<br />" . $_SESSION['am_pm'] . "<br />" . $_SESSION['room'] . "<br />" . $_SESSION['doctor'] . "<br />". $_SESSION['register_no']. "<br />";

//顯示掛號資料中的日期
$show_date = substr($_SESSION['sql_date'], 0, 4) . "/" . substr($_SESSION['sql_date'], 4, 2) . "/" . substr($_SESSION['sql_date'], 6, 2);

switch ($_SESSION['week_day'])	
{
case 1 : 
  $week_day_c = "星期一";    
  break;
case 2 : 
  $week_day_c = "星期二";    
  break;
case 3 : 
  $week_day_c = "星期三";    
  break;
case 4 : 
  $week_day_c = "星期四";    
  break;
case 5 : 
  $week_day_c = "星期五";    
  break;
case 6 : 
  $week_day_c = "星期六";    
  break;
}

if ($_SESSION['am_pm'] == "am") 
  $am_pm_c = "上午";
else
  $am_pm_c = "下午";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::網路掛號::</title>
<link href="CSS/index.css" rel="stylesheet" type="text/css" />
<link href="CSS/reg_confirm.css" rel="stylesheet" type="text/css" />
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
            <td class="menu_top_now"><a class="link1" href="index.html">網　路　掛　號</a></td>
            <td class="menu_top"><a class="link1" href="cancel.html">查　詢 / 取　消　掛　號</a></td>
            <td class="menu_top"><a class="link1" href="clinic_hour.html">門　診　時　間　表</a></td>
            <td class="menu_top"><a class="link1" >醫　師　介　紹</a></td>
          </tr>            
        </table>      
     </td>
    </tr>
    <tr>
      <td>
        <span class="index_style6">預約掛號資料</span>
      </td>
    </tr>
    <br />
    <tr>
      <td>
        
        <table class="reg_table">
          <tr>
            <td class="reg_head_date">預約日期</td><td class="reg_head">午別</td><td class="reg_head">科別</td><td class="reg_head">診間</td><td class="reg_head">醫師</td><td class="reg_head">就診號數</td>
          </tr>
          <tr>
            <?php
            echo "<td class=reg_td>" . $show_date . "(" . $week_day_c . ")" . "</td>";
            echo "<td class=reg_td>" . $am_pm_c . "</td>";
            echo "<td class=reg_td>" . $_SESSION['dept'] . "</td>";
            echo "<td class=reg_td>" . "第" . $_SESSION['room'] . "診</td>";
            echo "<td class=reg_td>" . $_SESSION['doctor'] . "</td>";
            echo "<td class=reg_td>" . $_SESSION['register_no'] . "號</td>";
            
            ?>
          </tr>        
        </table>

      </td>
    </tr>
    <tr>
      <td>
        <table class="input_table">
          <form action="reg_confirm.php" method="post">
            <tr>
              <td class="input_col1">&nbsp初診掛號</td>
              <td class="input_col2"><input type="checkbox" name="new_check" id="newCheck" onchange="disp_phone()" /></td>
            </tr>
            <tr>
              <td class="input_col1"><span style="color:red">*</span>身分證號</td>
              <td class="input_col2"><input id="pid" name="pid" type="text" size="15" maxlength="10" /></td>
            </tr>
            <tr>
              <td class="input_col1"><span style="color:red">*</span>生日</td>
              <td class="input_col2">西元年<input type="text" id="birth_year" name="birth_year" size="7" maxlength="4" />
                <select id="selectmonth" name="birth_mon" onchange="changeday('selectday', this.value)">
                  <option>請選擇</option>
                  <option value=1>01</option><option value=2>02</option><option value=3>03</option><option value=4>04</option><option value=5>05</option>
                  <option value=6>06</option><option value=7>07</option><option value=8>08</option><option value=9>09</option><option value=10>10</option>
                  <option value=11>11</option><option value=12>12</option>
                </select>月
                <select id="selectday" name="birth_day">
                </select>日
              </td>
            </tr>
            <tr>
              <td class="input_col1"><div id="phone_div1">&nbsp電話</div></td>
              <td class="input_col2">
                <div id="phone_div2">
                  <input name="phone" type="text" size="15" maxlength="12" />
                </div>
              </td>
            </tr>
            <tr>
              <td class="input_col1"></td>
              <td class="input_col2"><input type="submit" value="確認掛號" onclick="return verify();" /></td>
            </tr>
            <input name="insert" id="insert" type="hidden" value="patient_reg" />
            <tr>
              <td></td>
              <td id="result_text"><?php echo $reg_done; ?></td>
            </tr>
          </form>
        </table>
        <?php 
          if ($_POST['inquire_flg'] == true){
            echo "</td></tr><tr><td><table>";
            echo '<tr><td class="inquire_hr"></td><td class="inquire_hr">掛號日期</td><td class="inquire_hr">科別</td><td class="inquire_hr">午別</td>
                      <td class="inquire_hr">診間</td><td class="inquire_hr">醫師名稱</td><td class="inquire_hr">就診號數</td></tr>';
            for ($cnt = 0; $cnt < $num; $cnt++){
              echo "<tr>";
              echo '<td class="inquire_td">'.'<form name="f'.$cnt.'" action="reg_confirm.php" method="post">'.
                      '<input type="hidden" name="cancel_key[]" value="'.$inquire[$cnt]->date.'"/>'.
                      '<input type="hidden" name="cancel_key[]" value="'.$inquire[$cnt]->am_pm.'"/>'.
                      '<input type="hidden" name="cancel_key[]" value="'.$inquire[$cnt]->room.'"/>'.
                      '<input type="hidden" name="cancel_key[]" value="'.$_SESSION['patient_id'].'"/>'.
                      '<input type="hidden" name="inquire_flg" value="'.$_POST['inquire_flg'].'"/>'.
                      '<input type="submit" value="取消掛號"/>'.
                    '</form>'.'</td>'.
                    '<td class="inquire_td">'.$inquire[$cnt]->date.'</td>'.
                    '<td class="inquire_td">'.$inquire[$cnt]->department.'</td>'.
                    '<td class="inquire_td">'.$inquire[$cnt]->am_pm.'</td>'.
                    '<td class="inquire_td">'.$inquire[$cnt]->room.'</td>'.
                    '<td class="inquire_td">'.$inquire[$cnt]->doctor.'</td>'.
                    '<td class="inquire_td">'.$inquire[$cnt]->register_no.'</td>';

              echo "</tr>";
            }
            echo "</table>";
          }  
        ?>    
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
