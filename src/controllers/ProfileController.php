<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;
use src\models\Post;

class ProfileController extends Controller {

    private $loggedUser;

    public function __construct(){
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/signin');
        }
    }

    public function index($atts = []) {
        $page = intval(filter_input(INPUT_GET,'page'));

        // Detectando o usuario acessado
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id = $atts['id'];
        }
        
        //pegando informacoes do usuario
        $user = UserHandler::getUser($id, true);

        //pegando o feed do usuario
        $feed = PostHandler::getUserFeed($id, $page, $this->loggedUser->id);

        if(!$user) {
            $this->redirect('/');
        }
        $dateFrom = new \DateTime($user->birthdate);
        $dateTo =   new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;
        $user->ageMonths = $dateFrom->diff($dateTo)->m;


        // verificando se eu sigo 
        $isFollowing  = false;
        if($user->id != $this->loggedUser->id){
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }
        $this->render('profile',[
            'loggedUser'  => $this->loggedUser,
            'user'        => $user,
            'feed'        => $feed,
            'isFollowing' => $isFollowing,
        ]);

    }
   
    public function follow($atts){
        $to = intval($atts['id']);

        if(UserHandler::idExists($to)){
            if(UserHandler::isFollowing($this->loggedUser->id, $to)){
                UserHandler::unFollow($this->loggedUser->id, $to);
            } else {
                UserHandler::Follow($this->loggedUser->id, $to);
            }
        }

        $this->redirect('/perfil/'.$to);
    }


}