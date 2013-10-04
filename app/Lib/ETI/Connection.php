<?php
namespace ETI;

class Connection {
  private $cookie;
  private static $SITES = [
    'main' => [
      'url' => 'https://endoftheinter.net',
      'username' => 'b',
      'password' => 'p'
    ],
    'mobile' => [
      'url' => 'https://iphone.endoftheinter.net',
      'username' => 'username',
      'password' => 'password'
    ]
  ];

  public function __construct($username, $password, $site="main") {
    if (!$this->login($username, $password, $site)) {
      throw new \Exception("Could not sign in to ETI with given credentials.");
    }
  }

  public function get($url) {
    $curl = new \Curl($url);
    return $curl->ssl(False)
                ->cookie($this->cookie)
                ->get();
  }
  public function parallelGet($urls, $callback, &$opts, $curlOpts=[]) {
    // override some default cURL opts with the $curlOpts provided.
    $defaultOpts = [
      CURLOPT_COOKIE => $this->cookie,
      CURLOPT_USERAGENT => "LLAnim.us",
      CURLOPT_ENCODING => "gzip,deflate",
      CURLOPT_REFERER => "",
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_FOLLOWLOCATION => 1,
      CURLOPT_MAXREDIRS => 2
    ];
    foreach ($curlOpts as $opt=>$value) {
      $defaultOpts[$opt] = $value;
    }

    // now get all of the given URLs in parallel.
    $parallelCurl = new \ParallelCurl(20, $defaultOpts);
    foreach ($urls as $url) {
      $parallelCurl->startRequest($url, $callback, $opts);
    }
    $parallelCurl->finishAllRequests();
  }

  public function post($url, $fields) {
    $curl = new \Curl($url);
    return $curl->ssl(False)
                ->cookie($this->cookie)
                ->fields($fields)
                ->post();
  }

  public function login($username, $password, $site="main") {
    if (!isset(static::$SITES[$site])) {
      throw new \Exception("No such login site method was found: ".$site);
    }
    $fields = [
              static::$SITES[$site]['username'] => $username,
              static::$SITES[$site]['password'] => $password,
              ];

    $curl = new \Curl(static::$SITES[$site]['url']);
    $header = $curl->ssl(False)
                    ->header(True)
                    ->fields($fields)
                    ->post();
    if (!$header) {
      return False;
    }
    $headers = http_parse_headers($header);

    if (!isset($headers["Set-Cookie"])) {
      return False;
    }
    $this->cookie = implode(";", $headers["Set-Cookie"]);
    return $this;
  }
}
?>