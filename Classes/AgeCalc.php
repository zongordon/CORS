<?php //Added code if age < 10 for team members to be able to match with classes

/**
 * Description of AgeCalc
    Calculating the contestant's age at the date for the competition
 * @author zongordon
 * Example to call class:
$calculate_age = new AgeCalc;
    $calculate_age->comp_start_date = $comp_start_date;
    $calculate_age->contestant_birth = $row_rsRegistrations['contestant_birth'];
    $calculate_age->contestant_birth_max = $row_rsRegistrations['contestant_birth_max'];
    $calculate_age->contestant_team = $row_rsRegistrations['contestant_team'];
    echo 'This is the com start date: '.$calculate_age->comp_start_date.'<br/>';
    echo 'This is the contestant_birth: '.$calculate_age->contestant_birth.'<br/>';
    echo 'This is the contestant_birth_max: '.$calculate_age->contestant_birth_max.'<br/>';
    echo 'Team?: '.$calculate_age->contestant_team.'<br/>';
    echo 'This is the maximum age of the contestant: '.$calculate_age->calculate_age('contestant_age_max').'<br/>';
    echo 'This is the minimum age of the contestant: '.$calculate_age->calculate_age('contestant_age_min').'<br/><br/>';
 */
class AgeCalc {
	public $date1;
        public $date2;
        public $date3;
        public $diff_high;
        public $diff_low;
        public $contestant_age_max;
        public $contestant_age_min;
	public $comp_start_date;
	public $contestant_birth;
	public $contestant_birth_max;

    public function calculate_age() {
	//Different settings if team or individual contestant
        if($this->contestant_team === 1){	
            //Calculate the min and max age of the team at the date of the competition	
            $this->date1 = new DateTime($this->comp_start_date);
            $this->date2 = new DateTime($this->contestant_birth);
            $this->date3 = new DateTime($this->contestant_birth_max);
            $this->diff_high = $this->date2->diff($this->date1);
            $this->diff_low = $this->date3->diff($this->date1);
            $this->contestant_age_max = $this->diff_high->y;
            $this->contestant_age_min = $this->diff_low->y;
            //If age < 10, add a "0" to be able to match with classes
            if($this->contestant_age_min <10){
                return $this->contestant_age_min = '0'.$this->contestant_age_min;
            } else {
                return $this->contestant_age_min;
            }
            if($this->contestant_age_max <10){
                return $this->contestant_age_max = '0'.$this->contestant_age_max;
            } else {
                return $this->contestant_age_max;
            }            
        }else{    
            //Calculate the age of the contestant at the date of the competition
            $this->date1 = new DateTime($this->comp_start_date);
            $this->date2 = new DateTime($this->contestant_birth);
            $this->diff = $this->date2->diff($this->date1);
            $this->contestant_age_min = $this->diff->y;
            //If age > 18, set to 18 to match with classes
            if($this->contestant_age_min >18){
                return $this->contestant_age_min = 18;
            }
            //If age < 10, add a "0" to be able to match with classes
            if($this->contestant_age_min <10){
                return $this->contestant_age_min = '0'.$this->contestant_age_min;
            } else {
                return $this->contestant_age_min;
            }
            return $this->contestant_age_max = $this->contestant_age_min;
        }
    }
}
