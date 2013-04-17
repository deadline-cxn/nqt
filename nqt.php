
<?
########################################################################
# nqt.php last revised    02/19/05 (http://www.shat.net/php/nqt/)      #
#         Initial release 03/11/01                                     #
#         NQT is now nearly four years old and still in wide use!      #
#                                                                      #
# Copyright (C) shaun@shat.net. Full license follows update history.   #
#                                                                      #
# Version 1.7                                                          #
# 02/19/05: Added support for Italian domains (.it), with thanks to    #
# Andrea Balestrero. I am planning more changes soon.                  #
#                                                                      #
# Version 1.6                                                          #
# 05/07: Due to transitioning in the administration of the .org TLD,   #
# whois.crsnic.net no longer provides information for .org domains. I  #
# updated the script to use whois.corenic.net for .orgs instead.       #
# Thanks Jim In Cyberspace for the heads-up!                           #
#                                                                      #
# Version 1.5                                                          #
# 11/15: Adjusted the domain name regex to support 4-letter TLDs such  #
# as .info, and added WWWhois support for the .info, .name, .cc, .ws,  #
# .us, and .biz TLDs. Thanks to Andre from reload.de for the great     #
# suggestion!                                                          #
#                                                                      #
# Version 1.4                                                          #
# 08/31: Added support for HTTP GET variables. You can now access your #
# copy of NQT and pass some variables within the URL, e.g.             #
# http://your.host/nqt.php?target=shat.net&queryType=wwwhois           #
# http://your.host/nqt.php?target=shat.net&queryType=all&portNum=80    #
# Credit to hypersven.com for this suggestion!                         #
#                                                                      #
# Version 1.3                                                          #
# 06/28: PHP 4.20+ sets register_globals to "off" by default. This     #
# script expects them to be "on." Added a workaround.                  #
#                                                                      #
# Version 1.2                                                          #
# 03/01: The behavior of the gethostbyaddr() function appears to have  #
# changed in PHP 4.12, it no longer accepts hostnames in fqdn format.  #
# (This is how it really should work anyway, I think the old behavior  #
# was a bug.) Previous versions of NQT relied upon the fact that the   #
# gethostbyaddr() function would accept a hostname. If you're getting' #
# errors about addresses not being in a.b.c.d format, please download  #
# and install this version of the script.                              #
#                                                                      #
# Version 1.1                                                          #
# 10/30: Security update, thanks Dmitry Frolov, Mathijs Futselaar.     #
# Previous versions of script allowed possible execution of arbitrary  #
# commands on the host system. Updated lines marked with #bugfix, code #
# courtesy Dmitry Frolov.                                              #
#                                                                      #
# LICENSE                                                              #
#                                                                      #
# Network Query Tool is Copyright (C) shaun@shat.net. If you use this  #
# script, the copyright notice appended to query results MUST remain   #
# INTACT and UNALTERED, including the link to the distribution site    #
# <http://shat.net/php/nqt/>. This script is FREE SOFTWARE, but you    #
# may NOT claim it as your own. You are welcome to modify this script  #
# as you see fit, both for your own purposes, and for redistribution.  #
# However, if you intend to redistribute this script, a) you MAY NOT   #
# charge any fee, and b) you MUST credit the author. If you are unsure #
# about the terms of this license, you may contact the author by       #
# visiting <http://shat.net/contact.php>.                              #
#                                                                      #
# For runtime optimization purposes, you may wish to remove this block #
# of comments. You may do so only as long as you do not redistribute   #
# any copies of the script with the comment block removed.             #
#                                                                      #
# Requires: PHP3+, unix server preferred                               #
#                                                                      #
# This script takes a given hostname or IP address and attempts to     #
# look up all sorts of information about that address. Basically       #
# it does what network-tools.com does, without all the ads and ASP :)  #
#                                                                      #
# The following steps can be performed separately or all at once:      #
#                                                                      #
#  reverse DNS lookup, DNS query (dig), WWW whois, ARIN whois,         #
#  open-port check, ping, traceroute                                   #
#                                                                      #
# As you can probably guess this script is intended for unix machines. #
# If you use this script under win32, DNS query (dig) will not work.   #
#                                                                      #
# NO INITIAL CONFIGURATION IS REQUIRED. THERE ARE NO VARIABLES TO SET. #
#                                                                      #
# If you encounter problems with traceroute, replace the default path  #
# /usr/sbin/traceroute with the correct path in the tr() function.     #
########################################################################

#Until I rewrite my scripts, this will suffice for compatibility
if(phpversion() >= "4.2.0"){
   extract($_POST);
   extract($_GET);
   extract($_SERVER);
   extract($_ENV);
}

echo "<script>function m(el) {
  if (el.defaultValue==el.value) el.value = \"\"
}
</script>";

?>
<div align="center">
  <h2>Network Query Tool</h2>
  <form method="post" action="<? echo $PHP_SELF; ?>">
  
  <input type=hidden name=action value=nqt>
  
    <table width="60%" border="0" cellspacing="0" cellpadding="1">
      <tr bgcolor="#9999FF">
        <td width="50%" bgcolor="#6666FF"><font size="2" face="Verdana,
Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Host
          Information </b></font><font size="1" color="#6666ff">NQT20030507
</font></td>
        <td bgcolor="#6666FF"><font size="2" face="Verdana, Arial,
Helvetica, sans-serif" color="#FFFFFF"><b>Host
          Connectivity</b></font></td>
      </tr>
      <tr valign="top" bgcolor="#CCCCFF">
        <td>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <input type="radio" name="queryType" value="lookup">
            Resolve/Reverse Lookup<br>
            <input type="radio" name="queryType" value="dig">
            Get DNS Records<br>
            <input type="radio" name="queryType" value="wwwhois">
            Whois (Web)<br>
            <input type="radio" name="queryType" value="arin">
            Whois (IP owner)</font></p>
        </td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
          <input type="radio" name="queryType" value="checkp">
          Check port:
          <input
type="text" name="portNum" size="5" maxlength="5" value="80">
          <br>
          <input type="radio" name="queryType" value="p">
          Ping host<br>
          <input type="radio" name="queryType" value="tr">
          Traceroute to host<br>
          <input type="radio" name="queryType" value="all" checked>
          Do it all</font></td>
      </tr>

      </table>
  <table width="60%" border="0" cellspacing="0" cellpadding="1"><tr
bgcolor="#9999FF">
        <td colspan="2">
          <div align="center">
            <input type="text" name="target"
value="Enter host or IP" onFocus="m(this)">
            <input type="submit" name="Submit" value="Do It">
          </div>
        </td>
      </tr>
    </table>
  </form>
</div>
<?

#Global kludge for new gethostbyaddr() behavior in PHP 4.1x
$ntarget = "";

#Some functions

function message($msg){
echo "<font face=\"verdana,arial\" size=2>$msg</font>";
flush();
}

function lookup($target){
global $ntarget;
$msg = "$target resolved to ";
if( eregi("[a-zA-Z]", $target) )
  $ntarget = gethostbyname($target);
else
  $ntarget = gethostbyaddr($target);
$msg .= $ntarget;
message($msg);
}

function dig($target){
global $ntarget;
message("<p><b>DNS Query Results:</b><blockquote>");
#$target = gethostbyaddr($target);
#if (! eregi("[a-zA-Z]", ($target = gethostbyaddr($target))) )
if( (!eregi("[a-zA-Z]", $target) && (!eregi("[a-zA-Z]", $ntarget))))
  $msg .= "Can't do a DNS query without a hostname.";
else{
  if(!eregi("[a-zA-Z]", $target)) $target = $ntarget;
  if (! $msg .= trim(nl2br(`dig any '$target'`))) #bugfix
    $msg .= "The <i>dig</i> command is not working on your system.";
  }
//#TODO: Clean up output, remove ;;'s and DiG headers
$msg .= "</blockquote></p>";
message($msg);
}

function wwwhois($target){
global $ntarget;
$server = "whois.crsnic.net";
message("<p><b>WWWhois Results:</b><blockquote>");
#Determine which WHOIS server to use for the supplied TLD
if((eregi("\.com\$|\.net\$|\.edu\$", $target)) || (eregi("\.com\$|\.net\$|\.edu\$", $ntarget)))
  $server = "whois.crsnic.net";
else if((eregi("\.info\$", $target)) || (eregi("\.info\$", $ntarget)))
  $server = "whois.afilias.net";
else if((eregi("\.org\$", $target)) || (eregi("\.org\$", $ntarget)))
  $server = "whois.corenic.net";
else if((eregi("\.name\$", $target)) || (eregi("\.name\$", $ntarget)))
  $server = "whois.nic.name";
else if((eregi("\.biz\$", $target)) || (eregi("\.biz\$", $ntarget)))
  $server = "whois.nic.biz";
else if((eregi("\.us\$", $target)) || (eregi("\.us\$", $ntarget)))
  $server = "whois.nic.us";
else if((eregi("\.cc\$", $target)) || (eregi("\.cc\$", $ntarget)))
  $server = "whois.enicregistrar.com";
else if((eregi("\.ws\$", $target)) || (eregi("\.ws\$", $ntarget)))
  $server = "whois.nic.ws";
else if((eregi("\.it\$", $target)) || (eregi("\.it\$", $ntarget)))
  $server = "whois.nic.it";
else{
  $msg .= "I only support .com, .net, .org, .edu, .info, .name, .us, .cc, .ws, and .biz.</blockquote>";
  message($msg);
  return;
}

message("Connecting to $server...<br><br>");
if (! $sock = fsockopen($server, 43, $num, $error, 10)){
  unset($sock);
  $msg .= "Timed-out connecting to $server (port 43)";
}
else{
  fputs($sock, "$target\n");
  while (!feof($sock))
    $buffer .= fgets($sock, 10240);
}
 fclose($sock);
 if(! eregi("Whois Server:", $buffer)){
   if(eregi("no match", $buffer))
     message("NOT FOUND: No match for $target<br>");
   else
     message("Ambiguous query, multiple matches for $target:<br>");
 }
 else{
   $buffer = split("\n", $buffer);
   for ($i=0; $i<sizeof($buffer); $i++){
     if (eregi("Whois Server:", $buffer[$i]))
       $buffer = $buffer[$i];
   }
   $nextServer = substr($buffer, 17, (strlen($buffer)-17));
   $nextServer = str_replace("1:Whois Server:", "", trim(rtrim($nextServer)));
   $buffer = "";
   message("Deferred to specific whois server: $nextServer...<br><br>");
   if(! $sock = fsockopen($nextServer, 43, $num, $error, 10)){
     unset($sock);
     $msg .= "Timed-out connecting to $nextServer (port 43)";
   }
   else{
     fputs($sock, "$target\n");
     while (!feof($sock))
       $buffer .= fgets($sock, 10240);
     fclose($sock);
   }
}
$msg .= nl2br($buffer);
$msg .= "</blockquote></p>";
message($msg);
}

function arin($target){
$server = "whois.arin.net";
message("<p><b>IP Whois Results:</b><blockquote>");
if (!$target = gethostbyname($target))
  $msg .= "Can't IP Whois without an IP address.";
else{
  message("Connecting to $server...<br><br>");
  if (! $sock = fsockopen($server, 43, $num, $error, 20)){
    unset($sock);
    $msg .= "Timed-out connecting to $server (port 43)";
    }
  else{
    fputs($sock, "$target\n");
    while (!feof($sock))
      $buffer .= fgets($sock, 10240);
    fclose($sock);
    }
   if (eregi("RIPE.NET", $buffer))
     $nextServer = "whois.ripe.net";
   else if (eregi("whois.apnic.net", $buffer))
     $nextServer = "whois.apnic.net";
   else if (eregi("nic.ad.jp", $buffer)){
     $nextServer = "whois.nic.ad.jp";
     #/e suppresses Japanese character output from JPNIC
     $extra = "/e";
     }
   else if (eregi("whois.registro.br", $buffer))
     $nextServer = "whois.registro.br";
   if($nextServer){
     $buffer = "";
     message("Deferred to specific whois server: $nextServer...<br><br>");
     if(! $sock = fsockopen($nextServer, 43, $num, $error, 10)){
       unset($sock);
       $msg .= "Timed-out connecting to $nextServer (port 43)";
       }
     else{
       fputs($sock, "$target$extra\n");
       while (!feof($sock))
         $buffer .= fgets($sock, 10240);
       fclose($sock);
       }
     }
  $buffer = str_replace(" ", "&nbsp;", $buffer);
  $msg .= nl2br($buffer);
  }
$msg .= "</blockquote></p>";
message($msg);
}

function checkp($target,$portNum){
message("<p><b>Checking Port $portNum</b>...<blockquote>");
if (! $sock = fsockopen($target, $portNum, $num, $error, 5))
  $msg .= "Port $portNum does not appear to be open.";
else{
  $msg .= "Port $portNum is open and accepting connections.";
  fclose($sock);
  }
$msg .= "</blockquote></p>";
message($msg);
}

function p($target){
message("<p><b>Ping Results:</b><blockquote>");
if (! $msg .= trim(nl2br(`ping -c5 '$target'`))) #bugfix
  $msg .= "Ping failed. Host may not be active.";
$msg .= "</blockquote></p>";
message($msg);
}

function tr($target){
message("<p><b>Traceroute Results:</b><blockquote>");
if (! $msg .= trim(nl2br(`/usr/sbin/traceroute '$target'`))) #bugfix
  $msg .= "Traceroute failed. Host may not be active.";
$msg .= "</blockquote></p>";
message($msg);
}


//#If the form has been posted, process the query, otherwise there's
#nothing to do yet

if($queryType)
{
  

#Make sure the target appears valid

if( (!$target) || (!preg_match("/^[\w\d\.\-]+\.[\w\d]{1,4}$/i",$target)) ){ #bugfix
  message("Error: You did not specify a valid target host or IP.");
  
  }

#Figure out which tasks to perform, and do them

if( ($queryType=="all") || ($queryType=="lookup") )
  lookup($target);
if( ($queryType=="all") || ($queryType=="dig") )
  dig($target);
if( ($queryType=="all") || ($queryType=="wwwhois") )
  wwwhois($target);
if( ($queryType=="all") || ($queryType=="arin") )
  arin($target);
if( ($queryType=="all") || ($queryType=="checkp") )
  checkp($target,$portNum);
if( ($queryType=="all") || ($queryType=="p") )
  p($target);
if( ($queryType=="all") || ($queryType=="tr") )
  tr($target);
}

/*
<hr>
<p align="right"><font face="verdana,arial" size=1 color="#ffffff">NQT20050219
<a href="http://shat.net/php/nqt/"><font color="#cccccc">Network Query Tool 1.7</a>
<br>Copyright &copy; 2001-<?php echo date('Y');?>, shaun@shat.net</font></p>



// include("http://www.sethcoder.com/");
*/
?>

