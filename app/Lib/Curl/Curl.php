<?php
class CurlException extends Exception {
  public function __construct($message=null, $code = 0, Exception $previous=null) {
    parent::__construct($message, $code, $previous);
  }
  public function display() {
    // displays end user-friendly output explaining the exception that occurred.
    echo "A cURL occurred: ".$this->message.". The staff has been notified; sorry for the inconvenience!";
  }
}

class Curl {
  protected $curl, $opts;
  public function __construct($url) {
    $this->reset();
    $this->url($url);
  }
  protected function setOpt($opt, $value) {
    curl_setopt($this->curl, constant("CURLOPT_".$opt), $value);
    $this->opts[$opt] = $value;
    return $this;
  }
  protected function setOpts($opts) {
    foreach ($opts as $opt=>$value) {
      $this->setOpt($opt, $value);
    }
    return $this;
  }
  public function reset() {
    if ($this->curl) {
      curl_close($this->curl);
    }
    $this->curl = curl_init();
    $this->opts = [];
    $this->setOpts([
                   "RETURNTRANSFER" => True,
                   "MAXREDIRS" => 100
                   ]);
    $this->cookie("")
      ->agent("LLAnim.us")
      ->encoding("gzip,deflate")
      ->referer("")
      ->ssl(False)
      ->timeout(5000)
      ->header(False)
      // ->connectTimeout(500)
      ->follow();
    return $this;
  }
  public function url($url) {
    return $this->setOpt("URL", $url);
  }
  public function cookie($cookie) {
    return $this->setOpt("COOKIE", $cookie);
  }
  public function agent($agent) {
    return $this->setOpt("USERAGENT", $agent);
  }
  public function encoding($encoding) {
    return $this->setOpt("ENCODING", $encoding);
  }
  public function referer($referer) {
    return $this->setOpt("REFERER", $referer);
  }
  public function ssl($ssl=True) {
    return $this->setOpt("SSL_VERIFYPEER", (bool) $ssl);
  }
  public function follow($follow=True) {
    return $this->setOpt("FOLLOWLOCATION", (bool) $follow);
  }
  public function timeout($timeout) {
    return $this->setOpt("TIMEOUT_MS", $timeout);
  }
  public function connectTimeout($connectTimeout) {
    return $this->setOpt("CONNECTTIMEOUT_MS", $connectTimeout);
  }
  public function fields(array $fields) {
    return $this->setOpt("POSTFIELDS", http_build_query($fields));
  }
  public function header($header=True) {
    $this->setOpt("HEADER", (bool) $header);
    return $this->setOpt("NOBODY", (bool) $header);
  }
  public function get() {
    $result = curl_exec($this->curl);
    $curlError = curl_error($this->curl);
    $this->reset();
    if ($curlError || $result === False) {
      throw new CurlException("Error: ".$curlError."\nResult: ".$result);
    } else {
      return $result;      
    }
  }
  public function post() {
    curl_setopt($this->curl, CURLOPT_POST, True);
    return $this->get();
  }
}

function get_enclosed_string($haystack, $needle1, $needle2="", $offset=0) {
  if ($needle1 == "") {
    $needle1_pos = 0;
  } else {
    $needle1_pos = strpos($haystack, $needle1, $offset) + strlen($needle1);
    if ($needle1_pos === FALSE || ($needle1_pos != 0 && !$needle1_pos) || $needle1_pos > strlen($haystack)) {
      return false;
    }
  }
  if ($needle2 == "") {
    $needle2_pos = strlen($haystack);
  } else {
    $needle2_pos = strpos($haystack, $needle2, $needle1_pos);
    if ($needle2_pos === FALSE || !$needle2_pos) {
      return false;
    }
  }
  if ($needle1_pos > $needle2_pos || $needle1_pos < 0 || $needle2_pos < 0 || $needle1_pos > strlen($haystack) || $needle2_pos > strlen($haystack)) {
    return false;
  }
  
    $enclosed_string = substr($haystack, $needle1_pos, $needle2_pos - $needle1_pos);
    return $enclosed_string;
}

function get_last_enclosed_string($haystack, $needle1, $needle2="") {
  //this is the last, smallest possible enclosed string.
  //position of first needle is as close to the end of the haystack as possible
  //position of second needle is as close to the first needle as possible
  if ($needle2 == "") {
    $needle2_pos = strlen($haystack);
  } else {
    $needle2_pos = strrpos($haystack, $needle2);
    if ($needle2_pos === FALSE) {
      return false;
    }
  }
  if ($needle1 == "") {
    $needle1_pos = 0;
  } else {
    $needle1_pos = strrpos(substr($haystack, 0, $needle2_pos), $needle1) + strlen($needle1);
    if ($needle1_pos === FALSE) {
      return false;
    }
  }
  if ($needle2 != "") {
    $needle2_pos = strpos($haystack, $needle2, $needle1_pos);
    if ($needle2_pos === FALSE) {
      return false;
    }
  }
    $enclosed_string = substr($haystack, $needle1_pos, $needle2_pos - $needle1_pos);
    return $enclosed_string;
}

function get_biggest_enclosed_string($haystack, $needle1, $needle2="") {
  //this is the largest possible enclosed string.
  //position of last needle is as close to the end of the haystack as possible.
  
  if ($needle1 == "") {
    $needle1_pos = 0;
  } else {
    $needle1_pos = strpos($haystack, $needle1) + strlen($needle1);
    if ($needle1_pos === FALSE) {
      return false;
    }
  }
  if ($needle2 == "") {
    $needle2_pos = strlen($haystack);
  } else {
    $needle2_pos = strrpos($haystack, $needle2, $needle1_pos);
    if ($needle2_pos === FALSE) {
      return false;
    }
  }
    $enclosed_string = substr($haystack, $needle1_pos, $needle2_pos - $needle1_pos);
    return $enclosed_string;
}
?>