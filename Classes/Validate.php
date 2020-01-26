<?php
    /**
     * Validation 
     *
     * Semplice classe PHP per la validazione.
     *
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @copyright (c) 2016, Davide Cesarano
     * @license https://github.com/davidecesarano/Validation/blob/master/LICENSE MIT License
     * @link https://github.com/davidecesarano/Validation
     * Added public functions valuePattern($min, $max),datePattern(), timePattern(), urlPattern(), emailPattern() and changed error texts to Swedish
    Example of call class code:
    $val = new Validation();
    $length = 5;//min length of strings
    $min = 3;//minimum value of integers
    $max = 5;//maximum value of integers
    $val->name('starttid')->value($comp_start_time)->timePattern()->required();
    $val->name('startdatum')->value($comp_start_date)->datePattern('Y-m-d')->required();    
    $val->name('e-post')->value($comp_email)->emailPattern()->required();
    $val->name('t&auml;vlingssajten')->value($comp_url)->urlPattern()->required();
    $val->name('gr&auml;ns f&ouml;r round robin')->value($comp_limit_roundrobin)->valuePattern($min,$max)->required();    
    $val->name('Telefon')->value($contact_phone)->pattern('tel')->required();
    $val->name('L&ouml;senord')->value($user_password)->pattern('text')->required()->min($length)->equal($confirm_user_password);
    
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<div class="error"><h3>'.$error.'</h3></br></div>';
        }
        $output_form = 'yes';
    }
     */
    class Validation {
        /**
         * @var array $patterns
         */
        public $patterns = array(
            'uri'           => '[A-Za-z0-9-\/_?&=]+',
        //    'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
            'alpha'         => '[\p{L}]+',
            'words'         => '[\p{L}\s]+',
            'alphanum'      => '[\p{L}0-9]+',
            'int'           => '[0-9]+',
            'float'         => '[0-9\.,]+',
            'tel'           => '[0-9+\s()-]+',
            'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
            'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
            'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
            'address'       => '[\p{L}0-9\s.,()°-]+',
        //    'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
            'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
         //   'email'         => '[[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+'
        );
        /**
         * 
         * @var array $errors
         */
        public $errors = array();
        /**
         * Nome del campo
         * 
         * @param string $name
         * @return this
         */
        public function name($name){
            $this->name = $name;
            return $this;
        }
        /**
         * Valore del campo
         * 
         * @param mixed $value
         * @return this
         */
        public function value($value){
            $this->value = $value;
            return $this;
        }
        /**
         * File
         * 
         * @param mixed $value
         * @return this
         */
        public function file($value){
            $this->file = $value;
            return $this;
        }
        /**
         * Pattern da applicare al riconoscimento
         * dell'espressione regolare
         * 
         * @param string $name nome del pattern
         * @return this
         */
        public function pattern($name){
            if($name === 'array'){
                if(!is_array($this->value)){
                    $this->errors[] = 'Du skrev in felaktigt format f&ouml;r '.$this->name.'!';
                }
            }else{
                $regex = '/^('.$this->patterns[$name].')$/u';
                if($this->value != '' && !preg_match($regex, $this->value)){
                    $this->errors[] = 'Du skrev in felaktigt format f&ouml;r '.$this->name.'!';
                }
            }
            return $this;
        }
        /**
         * Pattern personalizzata
         * 
         * @param string $pattern
         * @return this
         */
        public function customPattern($pattern){
            $regex = '/^('.$pattern.')$/u';
            if($this->value != '' && !preg_match($regex, $this->value)){
                $this->errors[] = 'Du skrev in felaktigt format f&ouml;r '.$this->name.'!';
            }
            return $this;
        }
        /**
         * Date value
         * 
         * @return this
         */        
        public function datePattern($pattern){
            if($this->value != '' && date($pattern, strtotime($this->value)) != date($this->value)){
                $this->errors[] = 'Du skrev in felaktigt datumformat f&ouml;r '.$this->name.'!';
            }
            return $this;
        }
        /**
         * Date value
         * 
         * @return this
         */        
        public function timePattern(){
            if(!strtotime($this->value)){
                $this->errors[] = 'Du skrev in felaktigt tidsformat f&ouml;r '.$this->name.'!';
            }
            return $this;
        }
        /**
         * E-mail value
         * 
         * @return this
         */        
        public function emailPattern(){
            if($this->value != '' && !valid_email($this->value)){
                $this->errors[] = 'Du skrev in ogiltig e-post f&ouml;r '.$this->name.'!';
            }
            return $this;
        }
        /**
         * url value
         * 
         * @return this
         */        
        public function urlPattern(){
            //Remove all illegal characters from a url
            $this->value = filter_var($this->value, FILTER_SANITIZE_URL);  
            if(!filter_var($this->value, FILTER_VALIDATE_URL)){
                $this->errors[] = 'Du skrev in ogiltig adress f&ouml;r '.$this->name.'!';
            }
            return $this;
        }
        /**
         * Campo obbligatorio
         * 
         * @return this
         */
        public function required(){
            if((isset($this->file) && $this->file['error'] === 4) || ($this->value === '' || $this->value === null)){
                $this->errors[] = 'Du gl&ouml;mde att skriva in '.$this->name.'!';
            }            
            return $this;
        }
        /**
         * Lunghezza minima
         * del valore del campo
         * 
         * @param int $min
         * @return this
         */
        public function min($length){
            if(is_string($this->value)){
                if(strlen($this->value) < $length){
                    $this->errors[] = 'Antal tecken i '.$this->name.' &auml;r l&auml;gre &auml;n till&aring;tet!';
                }
            }else{
                if($this->value < $length){
                    $this->errors[] = 'Antal tecken i '.$this->name.' &auml;r l&auml;gre &auml;n till&aring;tet!';
                }
            }
            return $this;
        }
        /**
         * value range for integer
         * 
         * @param int $min
         * @return this
         */
        public function valuePattern($min,$max){
            if (filter_var($this->value, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max))) === false) {
                $this->errors[] = 'V&auml;rdet f&ouml;r '.$this->name.' &auml;r utanf&ouml;r till&aring;tna v&auml;rden!';
            }
            return $this;
        }
        /**
         * Lunghezza massima
         * del valore del campo
         * 
         * @param int $max
         * @return this
         */
        public function max($length){
            if(is_string($this->value)){
                if(strlen($this->value) > $length){
                    $this->errors[] = 'Antal tecken i '.$this->name.' &auml;r h&ouml;gre &auml;n till&aring;tet!';
                }
            }else{
                if($this->value > $length){
                    $this->errors[] = 'Antal tecken i '.$this->name.' &auml;r h&ouml;gre &auml;n till&aring;tet!';
                }
            }
            return $this;
        }
        /**
         * Confronta con il valore di
         * un altro campo
         * 
         * @param mixed $value
         * @return this
         */
        public function equal($value){
            if($this->value != $value){
                $this->errors[] = 'Det du skrev in som '.$this->name.' st&auml;mmer inte!';
            }
            return $this;
        }
        /**
         * Dimensione massima del file 
         *
         * @param int $size
         * @return this 
         */
        public function maxSize($size){
            if($this->file['error'] != 4 && $this->file['size'] > $size){
                $this->errors[] = 'Storleken p&aring; '.$this->name.' &ouml;verskrider det till&aring;tna ('.number_format($size / 1048576, 2).' MB)!';
            }
            return $this;
        }
        /**
         * Estensione (formato) del file
         *
         * @param string $extension
         * @return this 
         */
        public function ext($extension){
            if($this->file['error'] != 4 && pathinfo($this->file['name'], PATHINFO_EXTENSION) != $extension && strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION)) != $extension){
                $this->errors[] = 'Filen '.$this->name.' har fel fil&auml;ndelse: '.$extension.'!';
            }
            return $this;
        }
        /**
         * Purifica per prevenire attacchi XSS
         *
         * @param string $string
         * @return $string
         */
        public function purify($string){
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
        /**
         * Campi validati
         * 
         * @return boolean
         */
        public function isSuccess(){
            if(empty($this->errors)){return true;}
        }
        /**
         * Errori della validazione
         * 
         * @return array $this->errors
         */
        public function getErrors(){
            if(!$this->isSuccess()){return $this->errors;}
        }
        /**
         * Visualizza errori in formato Html
         * 
         * @return string $html
         */
        public function displayErrors(){
            $html = '<ul>';
                foreach($this->getErrors() as $error){
                    $html .= '<li>'.$error.'</li>';
                }
            $html .= '</ul>';
            return $html;
        }
        /**
         * Visualizza risultato della validazione
         *
         * @return booelan|string
         */
        public function result(){
            if(!$this->isSuccess()){
                foreach($this->getErrors() as $error){
                    echo "$error\n";
                }
                exit;
            }else{
                return true;
            }
        }
        /**
         * Verifica se il valore è
         * un numero intero
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_int($value){
            if(filter_var($value, FILTER_VALIDATE_INT)) {return true;}
        }
        /**
         * Verifica se il valore è
         * un numero float
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_float($value){
            if(filter_var($value, FILTER_VALIDATE_FLOAT)) {return true;}
        }
        /**
         * Verifica se il valore è
         * una lettera dell'alfabeto
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_alpha($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) {return true;}
        }
        /**
         * Verifica se il valore è
         * una lettera o un numero
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_alphanum($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => array('regexp' => "/^[a-zA-Z0-9]+$/")])) {return true;}
        }
        /**
         * Verifica se il valore è
         * un url
         *
         * @param mixed $value
         * @return boolean
         
        public static function is_url($value){
            if(filter_var($value, FILTER_VALIDATE_URL)) {return true;}
        }*/
        /**
         * Verifica se il valore è
         * un uri
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_uri($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) {return true;}
        }
        /**
         * Verifica se il valore è
         * true o false
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_bool($value){
            if(is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {return true;}
        }
        /**
         * Verifica se il valore è
         * un'e-mail
         *
         * @param mixed $value
         * @return boolean
         /*
        public static function is_email($value){
            if(filter_var($value, FILTER_VALIDATE_EMAIL)) {return true;}
        }         */
    }