#!/usr/bin/perl

# NO MORE SEARCH FOR THE REASON OF SERVER ERROR 500
# FIX IT BY MAKING THIS FILE IN UNIX FORMAT

use CGI::Carp qw(fatalsToBrowser);
use utf8;

my %in;
my $name_value;
my @name_value_pairs = split /&/, $ENV{QUERY_STRING};

if ( $ENV{REQUEST_METHOD} eq 'POST' )
{
		my $query = "";
		read( STDIN, $query, $ENV{CONTENT_LENGTH} ) == $ENV{CONTENT_LENGTH}
			or return undef;
		push @name_value_pairs, split /&/, $query;
}

foreach $name_value ( @name_value_pairs )
{
		my( $name, $value ) = split /=/, $name_value;
		
		$name =~ tr/+/ /;
		$name =~ s/%([\da-f][\da-f])/chr( hex($1) )/egi;
		
		$value = "" unless defined $value;
		$value =~ tr/+/ /;
		$value =~ s/%([\da-f][\da-f])/chr( hex($1) )/egi;
		$value =~ s/"/'/g;
		
		utf8::decode($value);
		$in{$name} = $value;
}

my $action = $in{"action"};
my $port = $in{"port"};
my $nick = $in{"nick"};
my $id = $in{"id"};

# $text =~ s/\^(?![\d\w])/^^$1/g;
# $text =~ s/Ã/ss/g;


print "Content-type: text/html\n\n";
print $text . "\n";

my $cmd = "";
if( $action eq "join" )
{
	$cmd = "cd /home/warsow/kkrcon/ && ./kkrcon.pl -a localhost -p $port test123 \"rsay ^7$nick ^7entered the chat\"";
}
elsif( $action eq "ping" )
{
	$cmd = "cd /home/warsow/kkrcon/ && ./kkrcon.pl -a localhost -p $port test123 \"rsay ^5ping from ^7$id\"";
}

if( $cmd ne "" )
{
	#print $cmd . "\n";
	qx/$cmd/;
}