<?php
function &inquire(){

  global $connection;
  $today = date( "Ymd", time());
  mysqli_select_db($connection,'register') or die('資料庫register不存在');

  $query = sprintf("SELECT room_info.*, clinic_hour.department FROM room_info INNER JOIN clinic_hour 
  ON room_info.am_pm = clinic_hour.am_pm AND room_info.room = clinic_hour.room AND room_info.week_day = clinic_hour.week_day 
  WHERE room_info.patient_id = %s AND room_info.date > %s;",GetSQLValue($_SESSION['patient_id'],"text"), GetSQLValue($today,"date") );

  $result_inquire = mysqli_query($connection, $query) or die(mysqli_error());
  
  class RoomStruct{
    public $date;
    public $am_pm;
    public $room;
    public $doctor;
    public $register_no;
    public $deptment;
  }
  
  while ( $row = mysql_fetch_assoc($result_inquire)){
  
    $x[$_SESSION['num']] = new RoomStruct();
    $x[$_SESSION['num']]->date      = $row['date'];
    $x[$_SESSION['num']]->am_pm     = $row['am_pm'];
    $x[$_SESSION['num']]->room      = $row['room'];      
    $x[$_SESSION['num']]->doctor    = $row['doctor'];
    $x[$_SESSION['num']]->register_no = $row['register_no'];
    $x[$_SESSION['num']]->department  = $row['department'];
    
    //總共掛號的數量
    $_SESSION['num']++;
  }
  
  if ($_SESSION['num'] == 0 )
    $_POST['inquire_flg'] = false;  
  else
    $_POST['inquire_flg'] = true;

  return $x;
}
function inquire_all(){
  
  global $connection;
  mysqli_select_db($connection,'register') or die('資料庫register不存在');

  $query = sprintf("SELECT room_info.*, clinic_hour.department FROM room_info INNER JOIN clinic_hour 
  ON room_info.am_pm = clinic_hour.am_pm AND room_info.room = clinic_hour.room AND room_info.week_day = clinic_hour.week_day 
  WHERE room_info.patient_id = %s;",GetSQLValue($_SESSION['patient_id'],"text") );

  $result_inquire_all = mysqli_query($connection, $query) or die(mysqli_error());

  $numInqAll = 0;
  while ( $row = mysql_fetch_assoc($result_inquire_all)){
    $numInqAll++;
  }

  if($numInqAll == 0)
    return false;
  else
    return true;
  }
function cancel(){

  global $connection, $reg_done;
  mysqli_select_db($connection,'register') or die('資料庫register不存在');
  
  //echo $_POST['can_date'] . "<br />" . $_POST['can_am_pm'] . "<br />" . $_POST['can_room'] . "<br />" .$_SESSION['patient_id'];

  $query = sprintf("DELETE FROM room_info WHERE date = %s AND room = %s AND am_pm = %s AND patient_id = %s;",
  GetSQLValue($_POST['can_date'],"date"),
  GetSQLValue($_POST['can_room'],"text"),
  GetSQLValue($_POST['can_am_pm'],"text"),
  GetSQLValue($_SESSION['patient_id'],"text"));
  
  $result_cancel = mysqli_query($connection, $query) or die(mysqli_error());
  
  if ($result_cancel){
    $reg_done = "取消成功";    
  }
}
function delPatient(){

  global $connection, $reg_done;
  mysqli_select_db($connection,'register') or die('資料庫register不存在');

  $query = sprintf("DELETE FROM patient WHERE patient_id = %s;", GetSQLValue($_SESSION['patient_id'],"text"));

  $result_delPatient = mysqli_query($connection, $query) or die(mysqli_error());
  
}

//總共掛號的數量
$_SESSION['num'] = 0;

//有點選取消掛號 can_date為欲取消掛號之日期
if (isset($_POST['can_date'])){
  cancel();

  //初診掛號取消
  if ( inquire_all() == false ){
    delPatient();
  }

}

//echo $_POST['inquire_flg'];
//查詢掛號 inquire_flg = true 表示已經有點選過取消掛號按鈕，非檢核完之後帶出的查詢
if ($_POST['inquire_flg'] == true){
  //傳回$inquire結構陣列
  $inquire =& inquire();
    
  if ($reg_done != "取消成功"){
    if ( $_POST['inquire_flg'] == false )
      $reg_done = "查無掛號資料";
  }
}

?>