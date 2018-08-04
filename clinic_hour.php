<?php require_once('Connections/connection.php'); ?>
<?php require_once('Connections/function.php'); ?>
<?php
function save_data($week_day)
{
  global $row, $am, $pm;
  
  //結構陣列分am及pm
  if ( $row['am_pm'] == "am" ){
    //結構陣列可容納5個門診/每天，使用堆疊從0開始存放
    for ($i = 0; $i < 5; $i++ )
    {
      if (!isset($am[$week_day]->doctor[$i]))
      {
        $am[$week_day]->doctor[$i] = $row['doctor'];
        $am[$week_day]->room[$i] = $row['room'];
        break;       
      }
      
    }
  }else{
    for ($i = 0; $i < 5; $i++ )
    {
      if (!isset($pm[$week_day]->doctor[$i]))
      {
        $pm[$week_day]->doctor[$i] = $row['doctor'];
        $pm[$week_day]->room[$i] = $row['room'];
        break;
      }
    }
  }
}
// 建立 session

mysqli_select_db($connection,'register') or die('資料庫register不存在'); 
$query = sprintf("SELECT * FROM clinic_hour WHERE department = %s ;",GetSQLValue($_GET["dept"],"text"));
$result = mysqli_query($connection, $query) or die(mysqli_error());

class DoctorStruct{
  public $doctor;
  public $room;
  public $patient_num;
}

for ( $k = 0; $k < 6; $k++ )
{
  $am[$k] = new DoctorStruct();
  $pm[$k] = new DoctorStruct();
}

$i = 0;
if ($result){
  while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
  {

    //print_r($row);
    //echo "<br />";
    switch ($row['week_day']){
      case '1':
        save_data(0);
        break;
      case '2':
        save_data(1);
        break;
      case '3':
        save_data(2);
        break;      
      case '4':
        save_data(3);
        break;      
      case '5':
        save_data(4);
        break;      
      case '6':
        save_data(5);
        break;      

      }


    $i++;
  }


  date_default_timezone_set('Asia/Taipei');
  $today = date("md");
  
  $today_week_day = date("N");

  $today_sunday = FALSE;
  //星期天須顯示星期一
  if($today_week_day == 7 ){
    $today_week_day = 1;
    $today_sunday = TRUE;  
  }

  $HowMany = $today_week_day - 1;

  $a_day = 86400;

  for( $j = 0; $j < 14; $j++ ){
    //第 j 週的秒數
    $WeekShift = $j * 604800;
    //星期一到星期六的timestamp 及 月日
    for ( $i = 0; $i < 6; $i++ ){

      $DayShift = $a_day * $i;

      //星期一  timestamp = 今天 - (一天秒數 * 距星期一幾天 ) 
      //星期 i  timestamp = 星期一 + $DayShift
      $day[$i] = time() - ($a_day * $HowMany) + $DayShift + $WeekShift; 

      //星期天須顯示星期一
      if ($today_sunday)
        $day[$i] = (time() + $a_day ) - ($a_day * $HowMany) + $DayShift + $WeekShift; 
      
      // mm / dd
      $title_date[$j][$i] = date('m', $day[$i]) . '/' . date('d', $day[$i]);
      //echo $title[$j][$i];
    }
    //echo "<br />";
  }
  $title_week[0] = "星期一";
  $title_week[1] = "星期二";
  $title_week[2] = "星期三";
  $title_week[3] = "星期四";
  $title_week[4] = "星期五";
  $title_week[5] = "星期六";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::網路掛號::</title>
<link href="CSS/index.css" rel="stylesheet" type="text/css" />
<link href="CSS/clinic.css" rel="stylesheet" type="text/css" />
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
            <td class="menu_top"><a class="link1" href="inquire.php">查　詢 / 取　消　掛　號</a></td>
            <td class="menu_top_now"><a class="link1" href="clinic_hour.html">門　診　時　間　表</a></td>
            <td class="menu_top"><a class="link1" href="">醫　師　介　紹</a></td>
          </tr>            
        </table>      
     </td>
    </tr>
    <tr>
      <td>
        <span class="index_style6">科別：<?php echo $_GET["dept"]; ?></span>
        <br /><br />
        <table class="table_clinic">
          <?php
            for( $j = 0; $j < 14; $j++ )
            {
         
              echo "<tr class='hr_clinic'>";
              echo "<td class='hr_td'></td>";
              
                for( $i = 0; $i < 6 ; $i++ )
                {
                  echo "<td class='hr_td' >". $title_date[$j][$i] ."<br />". $title_week[$i]."</td>";
                }
                
              echo "</tr>";
              echo "<tr class='tr_clinic'>";
              echo "<td class='tr_td'>上午</td>";  
                for( $i = 0; $i < 6 ; $i++ )
                { 
                  echo "<td class='tr_td'><table>";
                  for ($k = 0; $k < 5; $k++ ){
                    //若isset不成立，則不顯示空的陣列
                    if (isset($am[$i]->doctor[$k]))
                      echo "<tr><td class='tr_td_td'>". $am[$i]->doctor[$k] .'<span class="room">['. $am[$i]->room[$k] . "診]</span></td></tr>";
                  }
                  echo "</table></td>";   
                }
              echo "</tr>";
              echo "<tr class='tr_clinic'>";
              echo "<td class='tr_td'>下午</td>";  
                for( $i = 0; $i < 6 ; $i++ )
                { 
                  echo "<td class='tr_td'><table>";
                  for ($k = 0; $k < 5; $k++ ){
                    //若isset不成立，則不顯示空的陣列
                    if (isset($pm[$i]->doctor[$k]))
                      echo "<tr><td class='tr_td_td'>". $pm[$i]->doctor[$k] .'<span class="room">['. $pm[$i]->room[$k] . "診]</span></td></tr>";
                  }
                  echo "</table></td>";   
                }
              echo "</tr>";
              echo "<tr class='tr_clinic'>";
              echo "<td class='tr_td'>晚上</td>";  
                for( $i = 0; $i < 6 ; $i++ )
                { 
                  echo "<td class='tr_td'>". "<br />"."</td>";
                }
              echo "</tr>";
            }
          ?>
        </table>  
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
