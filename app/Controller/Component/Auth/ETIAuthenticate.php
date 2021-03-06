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
      Checks to ensure that the given username is logged onto ETI with the current IP.
      Returns a User array if successful, False if not.
    */
    $username = $request['data']['User']['name'];

    // ensure that the given username is logged onto ETI with the current IP.
    if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
      $requestParams = [
        'username' => $username,
        'ip' => $_SERVER['REMOTE_ADDR']
      ];
      $etiRequest = new Curl('https://boards.endoftheinter.net/scripts/login.php?'.http_build_query($requestParams));
      $checkETI = $etiRequest->ssl(False)->get();
      if ($checkETI !== "1:".$username) {
        return False;
      }
    }

    $findUser = $this->_findUser($username);
    if (!$findUser) {
      // kludge to signal that ETI auth succeeded but the user doesn't exist yet.
      return ['id' => Null, 'name' => $username];
    }
    return $findUser;
  }
}
?>