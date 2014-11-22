<?php

namespace Hydra;

class Token {

  private $token;
  private $formName;

  public function __construct($formName) {
    $this->formName = $formName;
  }

  public function getToken() {
    return $this->token;
  }

  /**
   * Generate token
   * @return string
   */
  public function generateToken() {
    // token already exists - probably just refresh of page / validation error
    if(isset($_SESSION['token'][$this->formName])) {
      $_SESSION['token'][$this->formName]['time'] = time();
      $this->token = $_SESSION['token'][$this->formName]['value'];
    } else {
      $this->token = md5(uniqid(rand(), TRUE));
      $_SESSION['token'][$this->formName] = array(
        'value' => $this->token,
        'time' => time()
      );
    }

    return $this->token;
  }

  /**
   * Check if form token exists and is valid
   * @param $formName
   * @param $postValue
   * @return bool
   */
  public function checkToken($formName, $postValue) {
    $this->flushTokens();

    if(!isset($_SESSION['token'])) {
      return false;
    }

    if(!isset($_SESSION['token']) || !count($_SESSION['token'])) {
      return false;
    }

    if(isset($_SESSION['token'][$formName])) {
      $token = $_SESSION['token'][$formName];
    } else {
      return false;
    }

    if($token['value'] == $postValue && (time() - $token['time'] < 1800) ) {
      return true;
    }

    return false;
  }

  public function removeToken($formName) {
    unset($_SESSION['token'][$formName]);
  }

  /**
   * Flush old tokens
   */
  public function flushTokens() {
    if(!isset($_SESSION['token']) || !count($_SESSION['token'])) {
      return;
    }

    $unsetIndex = array();
    foreach($_SESSION['token'] as $index => $token) {
      if(time() - $token['time'] > 1800) {
        $unsetIndex[] = $index;
      }
    }

    if(!count($unsetIndex)) {
      return;
    }

    foreach($unsetIndex as $index) {
      unset($_SESSION['token'][$index]);
    }
  }
}