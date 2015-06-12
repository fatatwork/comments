<?
/*  ini_set('display_errors',1);
  error_reporting(E_ALL);*/

  $APP_ID = '4832378'; // ID приложения
  $APP_secret = '7S006k5mPrcsGwGY7FCI'; // Защищённый ключ

  $session = array(); 
  
  function authOpenAPIMember($APP_ID, $APP_secret) { 
    global $session;
    $member = FALSE;
    $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig'); 
    $app_cookie = $_COOKIE['vk_app_' . $APP_ID]; 
    if ($app_cookie) { 
      $session_data = explode ('&', $app_cookie, 10); 
      foreach ($session_data as $pair) { 
        list($key, $value) = explode('=', $pair, 2); 
        if (empty($key) || empty($value) || !in_array($key, $valid_keys)) { 
          continue; 
        } 
        $session[$key] = $value; 
      } 
      foreach ($valid_keys as $key) { 
        if (!isset($session[$key])) return $member; 
      } 
      ksort($session); 

      $sign = ''; 
      foreach ($session as $key => $value) { 
        if ($key != 'sig') { 
          $sign .= ($key.'='.$value); 
        } 
      } 
      $sign .= $APP_secret; 
      $sign = md5($sign);
      if ($session['sig'] == $sign && $session['expire'] > time()) { 
        $member = array( 
          'id' => intval($session['mid']), 
          'secret' => $session['secret'], 
          'sid' => $session['sid'] 
        ); 
      } 
    } 
    return $member; 
  }
  
//$_POST['vk_app_' . $APP_ID] = "expire=1431446560&mid=14798628&secret=oauth&sid=c894cb1db857f3e8a47276bb5b919ce73ec79f2a3c0ee4b08c3d22eb3534086bdd8f329f9851ceb38abc0&sig=7610b30de9acf623b14ab2b1ad0c2845";

  if(isset($_COOKIE['vk_app_' . $APP_ID])){
    $member = authOpenAPIMember($APP_ID, $APP_secret);
    if($member !== FALSE) { 
    /* Пользователь авторизован в Open API */ 
      $params = array(
        'user_ids'         => $session['mid'],
        'fields'       => 'id,first_name,last_name,screen_name,photo_50'
      );
      //Получаем данные пользователя
      $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
      if (isset($userInfo['response'][0]['uid'])) {
        $userInfo = $userInfo['response'][0]; 
      }
      if ( isset( $userInfo )) {//Устанавливаем сессию и куки

          $_SESSION['first_name']   = $userInfo['first_name'];
          $_SESSION['last_name']    = $userInfo['last_name'];
          $_SESSION['image']        = $userInfo['photo_50'];
          $_SESSION['network']      = "vk.com";
          $_SESSION['identity']     = $userInfo['uid'];
      
          $life_time     = time() + ( 60 * 60 * 24 * 7 );
          $access_path   = "/";
          $access_domain = "bsmu.akson.by";
          setcookie('first_name', $_SESSION['first_name'], $life_time, $access_path, $access_domain );
          setcookie('last_name', $_SESSION['last_name'], $life_time, $access_path, $access_domain );
          setcookie('image', $_SESSION['image'], $life_time, $access_path, $access_domain);
          setcookie('network', $_SESSION['network'], $life_time, $access_path, $access_domain );
          setcookie('identity', $_SESSION['identity'], $life_time, $access_path, $access_domain );
          
          $userLink = "//vk.com/id" . $userInfo['uid'];
          $userName = $userInfo['first_name'] . " " . $userInfo['last_name'];
          //Возвращаем данные пользователя скрипту авторизации
          //print_r($member);

          $outString = "<p>Вы вошли как: <a href='$userLink'>".$userName."</a></p>" . "<p><a id='vk_logout' onClick='vk_logout()' href='#'>Выйти</a></p>";
          echo $outString;
      }

    } 
    else { 
      //Пользователь не авторизован
      echo "FAKE_USER";
    }
  }
?>