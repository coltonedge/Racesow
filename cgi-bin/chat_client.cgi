#!/usr/bin/perl 

# NO MORE SEARCH FOR THE REASON OF SERVER ERROR 500
# FIX IT BY MAKING THIS FILE IN UNIX FORMAT AND MAKE IT EXECUTABLE

use IO::Socket::Multicast;
use CGI::Session;
use CGI ':standard';
use CGI::Carp qw(fatalsToBrowser);
use utf8;

print "Content-type: text/html"; 

my $base_path = "/cgi-bin/";

if (length ($ENV{'QUERY_STRING'}) > 0){
  $buffer = $ENV{'QUERY_STRING'};
  @pairs = split(/&/, $buffer);
  foreach $pair (@pairs){
    ($name, $value) = split(/=/, $pair);
    $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
    $in{$name} = $value; 
  }
}

my @colors = ('#000000','#ff0000','#00ff00','#fff000','#0000ff','#00ffff','#ff00ff','#ffffff','#ff6000','#666666');
my $data, $tmp_data;
my $session;

if( $in{'sid'} ) {
 $session = new CGI::Session( $in{'sid'} );
} else {
	$session = new CGI::Session();
}

my $SID = $session->id();
my $port = $in{'port'};
my $ticket = $in{'ticket'};

$session->name("chat_session");
$session->param("ticket$port", $ticket );
$data = $session->param("chat_data$port");

my $s = IO::Socket::Multicast->new(LocalPort=>$port,ReuseAddr=>1);
$s->mcast_add('224.0.0.1');


print $session->header();
print "<html><body style=\"background-color: #45395F; font-family: verdana; font-size: 12px;\"><br/>\r\n";
$|++;
print( $data );

my $n = 0;
my $offset = 0;
while( $n++ < 10 + $offset ) {
  $s->recv($data,$port);
	$data = $data =~ m/^(.+)\n/ ? $1 : $data; ##remove the random(?) char behind newline Ú”
	
	if( $data =~ m/^CHTCMD:([\d\w]+):(.+)$/ )
	{
		$offset++;
		if($ticket eq $1)
		{
			if("quit" eq $2) {
				next;
			}
		}
	}
	
	$data =~ s/\^\^/::DOUBLECIRCUMFLEX::/g; # save ^^
	$data =~ s/\^([^\^])([^\^]*)/<span style="color: @colors[int($1)];">$2<\/span>/g; # colors
	$data =~ s/::DOUBLECIRCUMFLEX::/\^/g; # restore ^
	$data =~ s/^(.+)$/\<span style="color: @colors[7];">$1<\/span>/g; # make sure uncolored text is white
	# $data =~ s/^(.+):(.+)$/<b>$1:<\/b>$2/; # make nickname bold
	
	utf8::decode($data);
	
	$tmp_data = $session->param("chat_data$port");
	$session->param("chat_data$port", $tmp_data . $data ."<br/>");
	$session->flush();
	
	print $data ."<br/>";
	$|++;
  select(undef, undef, undef, 0.05);
}

$s->mcast_drop('224.0.0.1');

print "<script type=\"text/javascript\">\n";
print "window.location.href='". $base_path ."chat_client.cgi?sid=$SID&port=$port';\n";
print "</script>";
print "</body></html>";
exit();