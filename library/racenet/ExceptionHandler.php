<?php

final class Racenet_ExceptionHandler
{
    static final public function handle( $err ) {

        $err = str_replace( 'Stack trace:', "<br/>", $err );
        //$err = preg_replace( '@(.+): (.+)@', "\\1: \\2", $err );
        $err = preg_replace( '@(.+)\\\([^\\\]+)\((\d+)\):@', "<br/><br/><span style=\"font-size: 11px; color: navy;\">\\1\</span><span style=\"color: blue;\">\\2, line \\3</span>: <br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $err );
        $err = preg_replace( '@(#\d+)@', '<span style="font-size:13px; font-weight: bold; color: red;">\\1</span>', $err );
        $err = preg_replace( '@[\r\n]+@', "<br/>", $err );
        
        // FIXME :>
        $err = str_replace( 'dispatch()', "dispatch()<br/>", $err );
        $err = str_replace( '{main}', "{main}<br/><br/>", $err );
        
        //$err = preg_replace( '@(.{160})(.+)@', "\\1\r\n\t\\2\r\n", $err );
        
        echo '<span style="font-family: lucida console; font-size: 11px;"><h1 style="color: red;">Fatal error</h1> uncaught '. $err;
        
        echo '<hr/><pre>';
        
        echo '<b>$_POST</b><br/><br/>';
        print_r($_POST);
        echo '<br/><hr/>';
        
        echo '<b>$_GET</b><br/><br/>';
        print_r($_GET);
        echo '<br/><hr/>';
        
        echo '<b>$_SESSION</b><br/><br/>';
        if( isset( $_SESSION ) )
          print_r($_SESSION);
         else
          echo 'NULL';
        echo '<br/><hr/>';
        
        echo '</pre>';
    }
}