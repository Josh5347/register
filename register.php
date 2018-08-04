<?php require_once('Connections/connection.php'); ?>
<?php require_once('Connections/function.php'); ?>
<?php
//將clinic_hour門診表的資料放入結構陣列中，供html輸出
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
        //將掛號人數設為0
        $am[$week_day]->patient_num[$i] = 0;
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
        $pm[$week_day]->patient_num[$i] = 0;
        break;
      }
    }
  }
}
// 建立 session
if (!isset($_SESSION)) {
  session_start();
}
//echo session_id()."<br />";
$_SESSION['dept'] = $_GET["dept"];

//第一次進入頁面，week按鈕初設為第一個按鈕
if (!isset($_GET['period']))
  $_GET['period'] = 0;

//echo $_GET['period'];

mysqli_select_db($connection,'register') or die('資料庫register不存在'); 
$query = sprintf("SELECT * FROM clinic_hour WHERE department = %s ;",GetSQLValue($_SESSION['dept'],"text"));
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
  
    //將要顯示的醫師看診時間按星期分別由資料庫放入輸出陣列中
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
    
  $today_week_day = date("N");

  $today_saturday = FALSE;
  //星期六須顯示星期一
  if($today_week_day == 6 ){
    $today_saturday = TRUE;  
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
      //$day[$i] = time() + (86400*1) - ($a_day * $HowMany) + $DayShift + $WeekShift; 
      
      //星期六須顯示星期一
      /*if ($today_saturday)
        $day[$i] = (time() + $a_day ) - ($a_day * $HowMany) + $DayShift + $weekShift;*/ 
      
      // mm / dd
      $title_date[$j][$i] = date('m', $day[$i]) . '/' . date('d', $day[$i]);
      //查詢room_info的key
      $sql_date[$j][$i] = date('Ymd',$day[$i]);
      //echo $sql_date[$j][$i] . ' ';
    }
    //echo "<br />";
  }
  $title_week[0] = "星期一";
  $title_week[1] = "星期二";
  $title_week[2] = "星期三";
  $title_week[3] = "星期四";
  $title_week[4] = "星期五";
  $title_week[5] = "星期六";
  
  //getRoomInfo();
  //按鈕選擇的日期區間成為$j的值
  $j = $_GET['period'];
  //$i為星期一到星期六
  
  
  //取得各門診掛號人數，$i為星期幾
  for ( $i = 0; $i < 6; $i++ ){
 
    //echo GetSQLValue($sql_date[$j][$i],"text" ) . " \$i:" . $i. "<br />";
    $query_room = sprintf("SELECT room_info.*, clinic_hour.department FROM room_info INNER JOIN
    clinic_hour ON room_info.doctor = clinic_hour.doctor AND room_info.am_pm = clinic_hour.am_pm WHERE 
    room_info.date = %s AND clinic_hour.week_day = %d ;",GetSQLValue($sql_date[$j][$i],"text" ) , $i+1);  
    $result_room = mysqli_query($connection,$query_room) or die(mysqli_error());
    if ($result_room)
    {

      while($row_room = mysqli_fetch_array($result_room, MYSQLI_ASSOC))
      {
        //print_r($row_room_am);
        //echo "<br />";
        if ( $row_room['am_pm'] == "am" )
        { 
          if ($row_room['department'] == $_SESSION['dept'])
          {
            for ( $k = 0; $k < 5; $k++ )
            {
              //echo $am[$i]->room[$k] . "  vs  " . $row_room['room'];
              //$i為星期幾，$k為上午有幾個門診，若搜尋到該門診，則累計掛號病患數目
              if ($am[$i]->room[$k] == $row_room['room'])
              {
                $am[$i]->patient_num[$k]++;
                //echo $am[$i]->doctor[$k] . $am[$i]->patient_num[$k];
                break;
              }
            }
          }
        }else{
          if ($row_room['department'] == $_SESSION['dept'])
          {
            for ( $k = 0; $k < 5; $k++ )
            {
              //echo $pm[$i]->room[$k] . "  vs  " . $row_room['room'];
              //$i為星期幾，$k為上午有幾個門診，若搜尋到該門診，則累計掛號病患數目
              if ($pm[$i]->room[$k] == $row_room['room'])
              {
                $pm[$i]->patient_num[$k]++;
                //echo $pm[$i]->doctor[$k] . $pm[$i]->patient_num[$k];
                break;
              }
            }
          }          
        }
      }
    }
  }
  

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::網路掛號::</title>
<link href="CSS/index.css" rel="stylesheet" type="text/css" />
<link href="CSS/clinic.css" rel="stylesheet" type="text/css" />
<link href="CSS/register.css" rel="stylesheet" type="text/css" />
<script src="JavaScript/register.js" type="text/javascript"></script>

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
            <td class="menu_top"><a class="link1" href="inquire.php">查　詢 / 取　消　掛　號</a></td>
            <td class="menu_top"><a class="link1" href="clinic_hour.html">門　診　時　間　表</a></td>
            <td class="menu_top"><a class="link1" href="">醫　師　介　紹</a></td>
          </tr>            
        </table>      
     </td>
    </tr>
    <tr>
      <td>
        <span class="index_style6">科別：<?php echo $_GET["dept"]; ?></span>
        <br /><br />
        <table class="table_radio">
          <tr calss="tr_radio">
            <td calss="td_radio">
              <form>
              <?php
                for( $radio_no = 0; $radio_no < 14; $radio_no++ )
                {
                  /*if ( $today_saturday == true && 
                       $radio_no == 0)
                    continue;*/
                    
                  if ( $radio_no == $_GET['period'])
                    $checked = 'checked="checked"';
                  else
                    $checked = "";
                  echo '<div class="div_radio">';
                  echo '<input type="radio" class="pradio" '.$checked.' data-href="register.php?dept='. $_SESSION["dept"].'&period='.$radio_no.'">'. $title_date[$radio_no][0].'～'.$title_date[$radio_no][5].'</input>';
                  echo "</div>";
                  
                }
              ?>
              </form>
            </td>
          </tr>
        </table>
        <br />
        <table class="table_clinic">
          <?php
              $cnt_am = 0;
              $cnt_pm = 100;
              $weeks = $_GET["period"]; 
              echo "<tr class='hr_clinic'>";
              echo "<td class='hr_td'></td>";
              
                for( $i = 0; $i < 6 ; $i++ )
                {
                  echo "<td class='hr_td' >". $title_date[$weeks][$i] ."<br />". $title_week[$i]."</td>";
                }
                
              echo "</tr>";
              echo "<tr class='tr_clinic'>";
              echo "<td class='tr_td'>上午</td>";
              //星期一到星期六  
                for( $i = 0; $i < 6 ; $i++ )
                { 
                  echo "<td class='tr_td'><table>";
                  //每天上午有五個門診
                  for ($k = 0; $k < 5; $k++ )
                  {
                    //若isset不成立，則不顯示空的陣列，陣列中堆疊存放門診醫師及掛號人數資料
                    if (isset($am[$i]->doctor[$k]))
                    {
                      echo "<tr><td class='tr_td_td'>";
                      //今天及以前的看診醫師不提供掛號
                      if ($i >= $today_week_day || 
                          $weeks > 0            )
                      { 
                        $cnt_am++;
                        echo '<form class="reg_form" name="f'.$cnt_am.'" action="reg_confirm.php" method="post">';
                        echo '<input type="hidden" name="reg_key[]" value="'.$sql_date[$weeks][$i].'"/>';
                        echo '<input type="hidden" name="reg_key[]" value="am"/>';
                        echo '<input type="hidden" name="reg_key[]" value="'.$am[$i]->room[$k].'"/>';                        
                        echo '<input type="hidden" name="reg_key[]" value="'.$am[$i]->doctor[$k].'"/>'; 
                        echo '<input type="hidden" name="reg_key[]" value="'.$am[$i]->patient_num[$k].'"/>';                                                                        
                        echo '<a href="javascript:document.f'.$cnt_am.'.submit();">'.$am[$i]->doctor[$k].'</a>';                        
                        echo "</form>";
                      }
                      else
                        {echo $am[$i]->doctor[$k];}
                      echo '</td><td class="tr_td_td">';
                      //今天以前的掛號人數不顯示
                      if ($i >= $today_week_day ||
                          $weeks > 0            )
                      
                        echo '('. $am[$i]->patient_num[$k] . ')';
                      echo "</td></tr>";
                    }
                  }
                  echo "</table></td>";   
                }
              echo "</tr>";
              echo "<tr class='tr_clinic'>";
              echo "<td class='tr_td'>下午</td>";  
                for( $i = 0; $i < 6 ; $i++ )
                { 
                  echo "<td class='tr_td'><table>";
                  for ($k = 0; $k < 5; $k++ )
                  {
                    //若isset不成立，則不顯示空的陣列，陣列中堆疊存放門診醫師及掛號人數資料
                    if (isset($pm[$i]->doctor[$k]))
                    {
                      echo "<tr><td class='tr_td_td'>";          
                      //今天及以前的看診醫師不提供掛號
                      if ($i >= $today_week_day ||
                      $weeks > 0            )
                      { 
                        $cnt_pm++;
                        echo '<form class="reg_form" name="f'.$cnt_pm.'" action="reg_confirm.php" method="post">';
                        echo '<input type="hidden" name="reg_key[]" value="'.$sql_date[$weeks][$i].'"/>';
                        echo '<input type="hidden" name="reg_key[]" value="pm"/>';
                        echo '<input type="hidden" name="reg_key[]" value="'.$pm[$i]->room[$k].'"/>';                        
                        echo '<input type="hidden" name="reg_key[]" value="'.$pm[$i]->doctor[$k].'"/>';                                                
                        echo '<input type="hidden" name="reg_key[]" value="'.$pm[$i]->patient_num[$k].'"/>';                                                                                                
                        echo '<a href="javascript:document.f'.$cnt_pm.'.submit();">'.$pm[$i]->doctor[$k].'</a>';                        
                        echo "</form>";
                      }
                      else
                        {echo $pm[$i]->doctor[$k];}                                                       
                      //今天以前的掛號人數不顯示
                      if ($i >= $today_week_day ||
                          $weeks > 0            )
                        echo '('. $pm[$i]->patient_num[$k] . ')';
                      echo "</td></tr>";                    
                    }
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
            
          ?>
        </table>
        <span class="explan_text">掛號內數字為目前已掛人數</span>  
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
