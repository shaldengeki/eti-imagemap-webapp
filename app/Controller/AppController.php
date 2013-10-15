<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
  public $helpers = [
    'Js' => ['Jquery']
  ];
  public $components = [
    'DebugKit.Toolbar',
    'Session',
    'Auth' => [
      'authenticate' => ['IP', 'ETI'],
      'loginRedirect' => ['controller' => 'images', 'action' => 'index'],
      'logoutRedirect' => ['controller' => 'images', 'action' => 'index'],
      'authorize' => ['Controller']
    ]
  ];
  public function beforeFilter() {
    // by default, everyone can view index and view.
    $this->Auth->allow('index', 'view');

    // set authUser to current-user object if user is logged in.
    if ($this->Auth->user('id') > 0) {
      $this->set("authUser", $this->Auth->user());

      // set authScrapeRequest to current-user-scrape request object if user is logged in AND has a pending imagemap scrape request.
      $this->loadModel('ScrapeRequest');
      $scrapeRequest = $this->ScrapeRequest->find('first', [
                                                  'conditions' => [
                                                    'user_id' => $this->Auth->user('id'),
                                                  ]
                                                ]);

      if ($scrapeRequest && $scrapeRequest['ScrapeRequest']['progress'] != 100) {
        $this->set("authScrapeRequest", $scrapeRequest);
        $this->set("scrapeRequestErrors", ScrapeRequest::$ERRORS);
      }
    } else {
      // if user is not logged in, see if we can log them in via their IP.
      $this->loadModel('User');
      $findUser = $this->User->find('all', [
                                    'conditions' => [
                                      'last_ip' => $_SERVER['REMOTE_ADDR']
                                    ]
                                    ]);
      if ($findUser and count($findUser) === 1) {
        // log this user in and refresh the page to load the proper credentials.
        $this->Auth->login($findUser[0]['User']);
        $this->redirect($this->request->here);
      }
    }
  }
  public function isAuthorized($user) {
    // Admin can access every action
    if (isset($user['role']) && $user['role'] === 'admin') {
      return True;
    }

    // Default deny
    return False;
  }
  public function setTagListing($images, $tagQuery="") {
    // counts up the number of instances of each tag present in $images.
    // sets the resultant array to tagListing.

    $tagListing = [];
    foreach ($images as $image) {
      if ($image['Image']['tags']) {
        foreach ($this->Image->tagArray($image['Image']['tags']) as $tag) {
          $tag = $this->Tag->findByName($tag)['Tag'];
          if (!isset($tagListing[$tag['id']])) {
            $tag['count'] = 1;
            $tag['addLink'] = $this->Tag->appendToQuery($tag['name'], $tagQuery);
            $tag['removeLink'] = $this->Tag->appendToQuery('-'.$tag['name'], $tagQuery);
            $tagListing[$tag['id']] = $tag;
          } else {
            $tagListing[$tag['id']]['count']++;
          }
        }
      }
    }
    $this->set('tagListing', $tagListing);    
  }
}
