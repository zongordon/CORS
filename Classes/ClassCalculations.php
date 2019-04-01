<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClassCalculations
 *
 * @author zongordon
 */
class ClassCalculations {
    public $registrations;
    public $repechage;
    public $roundrobin;
    public $limit_roundrobin;
    public $class_max_matches;
    public $class_total_time;
    public $class_match_time;
    //put your code here
    public function class_max_matches() {
        if($this->limit_roundrobin > 2 && $this->limit_roundrobin <6 && $this->registrations-$this->limit_roundrobin <=0){
            $this->roundrobin = 'yes';
            return $this->registrations*($this->registrations-1)/2;
        }
        else{
            $this->roundrobin = 'no'; 
        }
        if($this->roundrobin === 'no' && $this->repechage === 'no' && $this->registrations > 0){
            return $this->registrations-1;
        }

        if($this->repechage === 'yes' && $this->roundrobin === 'no'){
            if($this->registrations > 17){
                return $this->registrations-1+6;
            }
            else if($this->registrations > 16){
                return $this->registrations-1+5;
            }
            else if($this->registrations > 9){
                return $this->registrations-1+4;
            }
            else if($this->registrations > 8){
                return $this->registrations-1+3;
            }
            else if($this->registrations > 5){
                return $this->registrations-1+2;
            }
            else if($this->registrations > 4){
                return $this->registrations-1+1;
            }
            else if($this->registrations > 1){
                return $this->registrations-1;
            }
            else {
                return $this->registrations = 0;
            }
        }
    }
    public function class_total_time() {
        return $this->class_match_time;
    }
}
$max_matches = new ClassCalculations;
$max_matches->limit_roundrobin = 5;
$max_matches->registrations = 5;
$max_matches->repechage = 'yes';
echo 'This is the number of matches in the class: '.$max_matches->class_max_matches().'<br/>';
echo 'This is the repechage status of the class: '.$max_matches->repechage.'<br/>';
echo 'This is the round robin status of the class: '.$max_matches->roundrobin.'<br/>';

$total_match_time = new ClassCalculations;
$total_match_time->class_match_time = 3.8;
echo 'This is the total match time for the class: '.$max_matches->class_max_matches()*$total_match_time->class_total_time().'<br/>';
