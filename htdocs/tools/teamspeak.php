<?php

$ts = file("http://www.tsviewer.com/ts_viewer_pur.php?ID=102481&bg=3E3452&type=ffffff&type_size=11&type_family=5&info=0&channels=1&users=1");

if( is_array( $ts ) ) $var = join("\r\n", $ts);
echo preg_replace( '@<script.*?<\/script>@is', '', $var );

?>