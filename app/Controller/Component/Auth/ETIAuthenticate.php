<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class ETIAuthenticate extends BaseAuthenticate {
  // ETI authentication module.
  public $settings = [
    'fields' => [
      'username' => 'name',
      'password' => 'password'
    ],
    'userModel' => 'User',
    'scope' => [],
    'recursive' => 0,
    'contain' => null,
    'passwordHasher' => 'Simple'
  ];

  public function authenticate(CakeRequest $request, CakeResponse $response) {
    /* 
      Checks to ensure that:
      1) The given username exists,
      2) The given username is logged onto ETI with the current IP.

      Returns a User array if successful, False if not.
    */
    $username = $request['data']['User']['username'];

    // ensure that the given username exists.
    $findUser = $this->_findUser($username);
    if (!$findUser) {
      return False;
    }

    // if the current IP is the user's last ip address, log them in.
    if ($findUser['last_ip'] === $_SERVER['REMOTE_ADDR']) {
      return $findUser;
    }

    // ensure that the given username is logged onto ETI with the current IP.
    // if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
      $etiRequest = new Curl('https://boards.endoftheinter.net/scripts/login.php?username='.urlencode($username).'&ip='.$_SERVER['REMOTE_ADDR']);
    // } else {
    //   return $findUser;
    // }
    $checkETI = $etiRequest->ssl(False)->get();
    if ($checkETI !== "1:".$username) {
      return False;
    }
    return $findUser;
  }
}
?>