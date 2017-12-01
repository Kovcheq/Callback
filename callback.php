<?php

class Callback {
    
    public $post = array();
    public $error = array();
    
    function __construct() {
        $this->post = $_POST;
    }
    
    /**
     * Валидируем данные формы
     * @return boolean 
     */
    public function validate() {
        
        if (empty($this->post['name'])
           || strlen($this->post['name']) < 3) {
            $this->error['name'] = 'Имя должно содержать не менее три символа';
        } 
        
        if (empty($this->post['email'])
           || !filter_var($this->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = 'Некоректно заполнено поле E-mail';
        }
        
        if (empty($this->post['text'])
           || strlen($this->post['text']) < 20) {
            $this->error['text'] = 'Сообщение должно содержать не менее 20 символов';
        }
        
        
        return !$this->error;
    } 
    
    /**
     * Отправляем письмо 
     */
    public function send() {
        $json = array();
        if (!empty($this->post) && $this->validate($this->post)) {
            $text = 'Новая заявка с сайта: '; 
            foreach($this->post as $key => $value) {
                $text .= '<br><b>' . ucfirst($key) . "</b>: " . $value;
            }  
            $text = html_entity_decode($text); 
                       
            $to  = "example@gmail.com"; 
 
            $subject = "Новая заявка с сайта"; 
  
            $headers  = "Content-Type: text/html; charset=UTF-8\r\n"; 
            $headers .= "From: example@gmail.com \r\n"; 
            $headers .= "Reply-To: example@gmail.com \r\n";  

            if (mail($to, $subject, $text, $headers)) {
                $json['success'] = 'Ваши данные успешно отправлены ';
            } else {
                $json['error'] = 'Проблемы с отправкой, попробуйте еще раз.';
            } 
        
         } else { 
            $json['error'] = implode('<br>', $this->error); 
        }
        header('Content-Type: application/json');
        echo json_encode($json);
    }  
} 

$callback = new Callback;
$callback->send();