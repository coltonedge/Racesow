<?php

/**
 * Award model
 * 
 */
class Award extends BaseAward
{
    /**
     * Award type to title map
     * 
     * @var array
     */
    public static $AWARD_TITLES = array(
        'points_week'     => 'for most points in a week',
        'races_week'      => 'for most races in a week',
        'points_month'    => 'for most points in a month',
        'races_month'     => 'for most races in a month',
        'raceathon'       => 'for winning the raceathon race competition',
        'raceathon_style' => 'for winning the raceathon style competition',
        'special'         => 'special award (individual)',
    );

    /**
     * get the awards title
     * 
     * @return unknown_type
     */
    public function getTitle()
    {
        if ($this->type=='special') {
            
            return $this->info;
            
        } else {
        
            return self::getTitleByType($this->type);
        }
    }
    
    /**
     * Getter for titlte by type
     * 
     * @param string $type
     * @return string
     */
    public static function getTitleByType($type)
    {
        if (array_key_exists($type, self::$AWARD_TITLES)) {
            
            return self::$AWARD_TITLES[$type];
        }
        
        return '';
    }

    /**
     * Get the details for the award
     * 
     * @return string
     */
    public function getDetails()
    {
        switch ($this->type){
            
            case 'points_week' :
                $date = new DateTime($this->date);
                return ('for winning <strong>'.$this->value.'</strong> points in week '.$date->format('W').' of '.$date->format('Y'));
                
            case 'points_month' :
                $date = new DateTime($this->date);
                return ('for winning <strong>'.$this->value.'</strong> points in '.$date->format('F Y'));
                
            case 'races_week' :
                $date = new DateTime($this->date);
                return ('for doing <strong>'.$this->value.'</strong> races in week '.$date->format('W').' of '.$date->format('Y'));
                
            case 'races_month' :
                $date = new DateTime($this->date);
                return ('for doing <strong>'.$this->value.'</strong> races in '.$date->format('F Y'));
                
            case 'raceathon' :
                return ('for winning the raceathon race competition');
                
            case 'raceathon_style' :
                return ('for winning the raceathon style competition');
                
            case 'special' :
                return $this->info;
        }
   }
}