<?php
include 'charset.php';
require_once 'login.php';
 $db_server = mysql_connect($hn, $un, $pw);
  if (!$db_server) die("Невозможно подлючиться к MySQL:".  mysql_error());
  
mysql_select_db($db,$db_server)
        or die("Невозможно выбрать базу данных:".  mysql_error());
     

  if (isset($_POST['login'])    &&
      isset($_POST['email']) &&
      isset($_POST['password']))
  {
       
   if(isset($_POST['submit']))
{
     $err = array();
   
     if(strlen($_POST['login'])==null)
    {
        $error_msg = "Логин должен быть заполнен";
        $err[]=$error_msg;
       
    }
    
    if((!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))&&(strlen($_POST['login'])) != null)
    {
        $error_msg = "Логин может состоять только из букв английского алфавита и цифр";
        $err[]=$error_msg;
       
    }

    if(((strlen($_POST['login']))!= null)&&(strlen($_POST['login']) < 3))
    {
        $error_msg = "Логин должен быть не меньше 3-х символов";
         $err[]=$error_msg;
       
    }

    $curr_login = get_post('login');
    $query_login = "SELECT * FROM users WHERE login='$curr_login'";
    $result_login = mysql_query($query_login);
    $count_login = mysql_num_rows($result_login);
    
   
    if($count_login > 0)
    {
       $error_msg = "Пользователь с таким логином уже существует, введите другой логин";
     $err[]=$error_msg;
    } 
    
       
    if(strlen($_POST['email'])==null)
    {
        $error_msg = "E-mail должен быть заполнен";
        $err[]=$error_msg;
       
    }
    
    if((strlen($_POST['email'])!=null)&&!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i",$_POST['email']))
    {
        $error_msg = "Некорректный e-mail";
        $err[]=$error_msg;
       
    }
    
    $curr_email = get_post('email');
    $query_email = "SELECT * FROM users WHERE email='$curr_email'";
    $result_email = mysql_query($query_email);
    $count_email = mysql_num_rows($result_email);
    
   
    if($count_email > 0)
    {
       $error_msg = "Пользователь с таким e-mail уже существует";
     $err[]=$error_msg;
       
    }

    if(strlen($_POST['password'])==null)
    {
        $error_msg = "Пароль должен быть заполнен";
        $err[]=$error_msg;
       
    }
    
    if(((strlen($_POST['password']))!=null)&&(strlen($_POST['password']) < 6))
    {
        $error_msg = "Пароль должен быть не меньше 6-х символов";
         $err[]=$error_msg;
        
    }
    
    if(((strlen($_POST['password']))!=null)&&(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['password'])))
    {
        $error_msg = "Пароль может состоять только из букв английского алфавита и цифр";
        $err[]=$error_msg;
       
    }
}   
      

       $loginf = $_POST['login']; 
       $emailf = $_POST['email'];
       
      if(count($err) == 0)
    { 
     
     $loginf = $emailf = null; 
      
    $login    = clean(get_post('login'));
    $email    = clean(get_post('email'));
    $password = clean(get_post('password'));
    $password = md5(md5(trim(($password))));
    $regtime  = date("Y-m-d H:i:s");
    $query    = "INSERT INTO users VALUES" .
      "(NULL, '$login', '$email', '$password', '$regtime')";
    
    $succses = null ;
  	if (!mysql_query($query,$db_server)) 
        { echo "Сбой при вставке данных: $query<br>".
        mysql_error."<br /><br>";}
        else {$succses = "Вы успешно зарегистрированы!";}
  }
  }
    
  
  $query1 = "SELECT count(*) FROM users";
  $result1 = mysql_query($query1);
  $query2 = "SELECT min(regtime) FROM users";
  $result2 = mysql_query($query2);
  $query3 = "SELECT max(regtime) FROM users";
  $result3 = mysql_query($query3);
    
  if (!$result1) die ("Сбой при доступе к базе данных: " . mysql_error);

 
  $row1 = mysql_fetch_row( $result1);
  $row2 = mysql_fetch_row( $result2);
  $row3 = mysql_fetch_row( $result3);
  
  $count = "Зарегистрировано " . get_correct_str($row1[0],"пользователь","пользователя","пользователей");
  $datefirst = $row2[0];
  $datelast = $row3[0];
  if ($row1[0]==0)
  {$resultfirst = null;
  $resultlast = null;
  }
  else
  {
  $resultfirst = "Первый пользователь зарегистрировался ".get_result_date(date("Y-m-d H:i:s"),$datefirst)." назад";
  $resultlast = "Последний пользователь зарегистрировался ".get_result_date(date("Y-m-d H:i:s"),$datelast)." назад";
  }
  ?>

 <link href="index.css" rel="stylesheet">

<div id="header">
    <div>
    </div>
 <div class="tabsLink">
        <a href="#reg" style="padding-right: 30px">Регистрация</a> 
        <a href="#info">Информация</a>
        </div>
</div>
<div id="content">
  <a class="tabs" id="reg"></a>
        <div class="tab">
            <form class="forms"action="index.php#reg" method="post">
 <br>Логин <br> <input type="text" name="login" value="<?=@$loginf?>"><br> 
 E-mail <br> <input type="text" name="email" value="<?=$emailf?>"> <br>
 Пароль<br>  <input type="password" name="password"><br>
 <br> <input type="submit" name="submit" value="Зарегистрироваться"><br><br>
    <?php  
       foreach($err as $error)
            {
            print $error."<br>";
            }
       print $succses."<br>";
     ?>
             </form>
        </div>
        
        <a class="tabs" id="info"></a>
        <div class="tab">
            <form class="forms">
  <?php  echo <<<_END
  </br>
   $count</br>
   $resultfirst</br>
   $resultlast
_END;
?>
    </form>
        </div>
</div>
<div id="footer">
 
</div>
  
  <?php
  
 mysql_close($db_server);
    
function get_post($var)
  {
    return mysql_real_escape_string($_POST[$var]);
      }
    
     
function get_correct_str($num, $str1, $str2, $str3) 
{
    $val = $num % 100;
    
    if ($val > 10 && $val < 20) return $num .' '. $str3;
    else {
        $val = $num % 10;
        if ($val == 1) return $num .' '. $str1;
        elseif ($val > 1 && $val < 5) return $num .' '. $str2;
        else return $num .' '. $str3;
         }
}


function get_result_date($str1,$str2)
{
    $result_date = (strtotime($str1) - strtotime($str2));
    $minute=60;
    $hour = 60*$minute;
    $day  = 24*$hour;
    $week = 7*$day;
    $month = 4*$week;
    $year =  12*$month;
    
    if ($result_date<$minute)
    {
        return get_correct_str($result_date, "секунду", "секунды","секунд");
    }
    elseif ($result_date>=$minute&&$result_date<$hour)     
       {
        $result_date=  round($result_date/$minute);
        return get_correct_str($result_date, "минуту", "минуты","минут");
    }
      elseif ($result_date>=$hour&&$result_date<$day)     
       {
        $result_date=  round($result_date/$hour);
        return get_correct_str($result_date, "час", "часа","часов");
    }  
    elseif ($result_date>=$day&&$result_date<$week)     
       {
        $result_date=  round($result_date/$day);
        return get_correct_str($result_date, "день", "дня","дней");
    } 
    elseif ($result_date>=$week&&$result_date<$month)     
       {
        $result_date=  round($result_date/$week);
        return get_correct_str($result_date, "неделю", "недели","недель");
    }  
    elseif ($result_date>=$month&&$result_date<$year)     
       {
        $result_date=  round($result_date/$month);
        return get_correct_str($result_date, "месяц", "месяца","месяцев");
    } 
   else 
   {
         return "больше года";
   }
}

function clean($var) 
        {
    $var = trim($var);
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlspecialchars($var);
    
    return $var;
}

?>

