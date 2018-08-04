    <tr>
      <td>
        <table class="input_table">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <tr id="check">
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
              <td class="input_col2"><input type="submit" value="確　認" onclick="return verify();" /></td>
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
            echo '</td></tr><tr><td><table class="inquire_table">';
            echo '<tr><td class="inquire_hr"></td><td class="inquire_hr">掛號日期</td><td class="inquire_hr">科別</td><td class="inquire_hr">午別</td>
                      <td class="inquire_hr">診間</td><td class="inquire_hr">醫師名稱</td><td class="inquire_hr">就診號</td></tr>';
            for ($cnt = 0; $cnt < $_SESSION['num']; $cnt++){
              echo "<tr>";
              echo '<td class="inquire_td">'.'<form class="form_inquire" name="f'.$cnt.'" action='.$_SERVER['PHP_SELF'].' method="post">'.
                      '<input type="hidden" name="can_date" value="'.$inquire[$cnt]->date.'"/>'.
                      '<input type="hidden" name="can_am_pm" value="'.$inquire[$cnt]->am_pm.'"/>'.
                      '<input type="hidden" name="can_room" value="'.$inquire[$cnt]->room.'"/>'.
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