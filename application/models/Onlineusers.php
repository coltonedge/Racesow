<?php

/**
 * onlineusersModel
 *
 * @uses       Racenet_Model_Abstract 
 * @copyright  
 * @license    
 */
class Onlineusers
{
    public function setMinutes( $min )
    {
        $this->_minutes = (integer)$min;
        return $this;
    }

    public static function count()
    {
        
        $qry = "
                            SELECT       COUNT(*) as num
                            FROM         phpbb_sessions
                            WHERE        session_time >= ". strtotime('-15 minutes') ."
                            GROUP BY     session_ip
                        ";
        $stmt = Doctrine_Manager::connection()->getDbh()->query($qry);
        if ($o =$stmt->fetch(PDO::FETCH_OBJ)) {
        
            return $o->num;
        }
    }        
    public function getOnlineUsers()
    {
        
        $qry = "
                            SELECT            u.user_id AS userId,
                                                    u.username AS userName,
                                                    p.id AS playerId,
                                                    p.name AS playerName
                            FROM                phpbb_users AS u
                            LEFT JOIN    phpbb_sessions_keys AS s ON u.user_id = s.user_id
                            LEFT JOIN    phpbb_sessions AS s2 ON u.user_id = s2.session_user_id
                            LEFT JOIN        player_phpbbuser AS pu ON pu.user_id = u.user_id
                            LEFT JOIN        player AS p ON p.id = pu.player_id
                            WHERE                (s.last_login >= ". strtotime('-15 minutes') ." OR s2.session_time >= ". strtotime('-15 minutes') .")
                                AND                u.username != 'Anonymous'
                            GROUP BY        u.user_id
                            ORDER BY        u.username ASC
                        ";
        $stmt = Doctrine_Manager::connection()->getDbh()->query($qry);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

?>