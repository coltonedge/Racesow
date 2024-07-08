<?php

class AclRacenet
{
    /**
     * Racenet/Racesow roles
     * 
     * @example                      0xAAAABBBB
     *     A = INGAME PART
     *     B = WEBSITE PART          
     */
    
    const USER_GUEST                = 0x00000000;  // 0
    const USER_MEMBER               = 0x00000001;  // 1
    const ADMIN_NEWS                = 0x00000002;  // 2
    const ADMIN_HELP                = 0x00000004;  // 4
    const ADMIN_MAPS                = 0x00000008;  // 8
    
    const INGAME_CONNECT            = 0x00010000;  // 65536
    const INGAME_VOTE               = 0x00020000;  // 131072
    const INGAME_MAP                = 0x00040000;  // 262144
    const INGAME_RESTART            = 0x00080000;  // 524288
    const INGAME_KICK               = 0x00100000;  // 1048576
    const INGAME_BAN                = 0x00200000;  // 2097152
    const INGAME_UNBAN              = 0x00400000;  // 4194304
    const INGAME_TIMELIMIT          = 0x00800000;  // 8388608
                                                   
    // connect, map, restart, timelimit, kick =      10289152
    
    const ANY_ADMIN                 = 0x00FE000E;
    
    const SUPERADMIN                = 0x00FF000F;  //16711695
    
    /**
     * Get default rights 
     *
     */
    static public function getDefault()
    {
        return  self::USER_MEMBER |
                self::INGAME_CONNECT |
                self::INGAME_VOTE;
    }
    
    /**
     * Get users by their access rights (racenet_flags)
     *
     * @param integer|null $type
     * @return array
     */
    public static function getUsers($flags)
    {
        if(!$flags & self::SUPERADMIN) {
            throw new Exception("invalid racenet flags given");
        }
        
        return Doctrine::getTable('PhpbbUsers')
            ->createQuery()
            ->where("racenet_flags & ?")
            ->execute($flags);
    }
}
