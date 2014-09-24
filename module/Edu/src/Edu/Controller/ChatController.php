<?php

namespace Edu\Controller;

use Zend\View\Model\ViewModel;
use Likerow\Controller;
use Edu\Model;
use Zend\Session\Container;

class ChatController extends Controller\BaseController {

    public function msgeAction() {
        /*$pusher = $this->service()->get('Likerow\Pusher\Pusher');
        $data = $pusher->trigger('test_channel', 'my_event', array( 'test' => 1 ));
        var_dump($data);exit; */       
    }

}
