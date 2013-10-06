<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class IPAuthenticate extends BaseAuthenticate {
  // Last-IP authentication module.
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
    if (!isset($request['data']['User']) || !isset($request['data']['User']['name'])) {
      return False;
    }

    $username = $request['data']['User']['name'];

    // ensure that the given username exists.
    $findUser = $this->_findUser($username);
    if ($findUser) {
      // if the current IP is the user's last ip address, log them in.
      if ($findUser['last_ip'] === $_SERVER['REMOTE_ADDR']) {
        return $findUser;
      }
    }
    return False;
  }
}
?>