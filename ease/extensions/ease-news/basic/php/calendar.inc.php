<?php

    function monthBack( $timestamp ){
	return mktime(0,0,0, date("m",$timestamp)-1,date("d",$timestamp),date("Y",$timestamp) );
    }
    function yearBack( $timestamp ){
	return mktime(0,0,0, date("m",$timestamp),date("d",$timestamp),date("Y",$timestamp)-1 );
    }
    function monthForward( $timestamp ){
	return mktime(0,0,0, date("m",$timestamp)+1,date("d",$timestamp),date("Y",$timestamp) );
    }
    function yearForward( $timestamp ){
	return mktime(0,0,0, date("m",$timestamp),date("d",$timestamp),date("Y",$timestamp)+1 );
    }

    function getCalender($date,$headline = array('Mo','Di','Mi','Do','Fr','Sa','So')) {
	$sum_days = date('t',$date);
	$LastMonthSum = date('t',mktime(0,0,0,(date('m',$date)-1),0,date('Y',$date)));

	foreach( $headline as $key => $value ) {
	    echo "<div class=\"Day Headline\">".$value."</div>\n";
	}

	for( $i = 1; $i <= $sum_days; $i++ ) {
	    $day_name = date('D',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));
	    $day_number = date('w',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));

	    if( $i == 1) {
		$s = array_search($day_name,array('Mon','Tue','Wed','Thu','Fri','Sat','Sun'));
		for( $b = $s; $b > 0; $b-- ) {
		    $x = $LastMonthSum-$b;
		    /*
		    $intLastMonth = date('m',$date)-1;
		    if( $intLastMonth == 0 )
		    {
			$intLastMonth = 12;
			$intYear = date('Y',$date)-1;
		    }
		    else
			$intYear = date('Y',$date);
		    echo "<div class=\"Day before\"><a href=\"javascript:;\" onclick=\"fctShowCal('". $_POST['id'] ."','". addslashes( $_POST['strName'] ) ."',". mktime(0,0,0,$intLastMonth,$x,$intYear) .")\">".sprintf("%02d",$x)."</a></div>\n";
		    */
		    echo "<div class=\"Day before\">&nbsp;</div>\n";
		}
	    } 

	    if( $i == date('d',$date)) {
		echo "<div class=\"Day current\"><a href=\"javascript:;\" onclick=\"fctShowCal('". $_POST['id'] ."','". addslashes( $_POST['strName'] ) ."',". mktime(0,0,0,date('m',$date),$i,date('Y',$date)) .")\">".sprintf("%02d",$i)."</a></div>\n";
	    } else {
		echo "<div class=\"Day normal\"><a href=\"javascript:;\" onclick=\"fctShowCal('". $_POST['id'] ."','". addslashes( $_POST['strName'] ) ."',". mktime(0,0,0,date('m',$date),$i,date('Y',$date)) .")\">".sprintf("%02d",$i)."</a></div>\n";
	    }

	    if( $i == $sum_days) {
		$next_sum = (6 - array_search($day_name,array('Mon','Tue','Wed','Thu','Fri','Sat','Sun')));
		for( $c = 1; $c <=$next_sum; $c++) {
		    /*
		    $intNextMonth = date('m',$date)+1;
		    if( $intNextMonth == 13 )
		    {
			$intNextMonth = 01;
			$intYear = date('Y',$date)+1;
		    }
		    else
			$intYear = date('Y',$date);
		    echo "<div class=\"Day after\"><a href=\"javascript:;\" onclick=\"fctShowCal('". $_POST['id'] ."','". addslashes( $_POST['strName'] ) ."',". mktime(0,0,0,$intNextMonth,$c,$intYear) .")\">".sprintf("%02d",$c)."</a></div>\n"; 
		    */
		    echo "<div class=\"Day after\">&nbsp;</div>\n"; 
		}
	    }
	}
    }

    if( isset($_REQUEST['timestamp'])) $date = $_REQUEST['timestamp'];
    else $date = time();

    $arrMonth = array(
	"January" => "Januar",
	"February" => "Februar",
	"March" => "M&auml;rz",
	"April" => "April",
	"May" => "Mai",
	"June" => "Juni",
	"July" => "Juli",
	"August" => "August",
	"September" => "September",
	"October" => "Oktober",
	"November" => "November",
	"December" => "Dezember"
    );

    $headline = array('Mo','Di','Mi','Do','Fr','Sa','So');

?>
<style type="text/css">
    .Calender {
	width: 210px;
	border: 1px #fff solid;
	border-radius: 4px;
	color: #fff;
    }
    .Calender div.after,
    .Calender div.before{

    }
    .Calender a,
    .Calender a:link,
    .Calender a:visited,
    .Calender a:active,
    .Calender a:hover {
	color: #fff;
    }
    .Day {
	float:left;
	width:30px;
	height:30px;
	line-height: 30px;
	text-align: center;
    }
    .Day a:link,
    .Day a:visited,
    .Day a:active,
    .Day a:hover {
	display: block;
	width: 30px;
	height: 30px;
    }
    .Day:hover {
	background-color: #fff;
    }
    .Day.Headline {
	background:silver;
	line-height: 30px;
    }
    .Day.current a,
    .Day.current a:link,
    .Day.current a:visited,
    .Day.current a:active,
    .Day.current a:hover {
	font-weight:bold;
	background-color: #f00;
    }
    .Day:hover a:link,
    .Day:hover a:visited,
    .Day:hover a:active,
    .Day:hover a:hover,
    .Day.current a:hover {
	color: #000;
    }
    .Pagination {
	text-align: center;
	height:30px;
	line-height:30px;
	font-weight: bold;
    }
    .Pagination a {
	width:20px;
	height:20px;
    }
    .Pagination a.last,
    .Pagination a.next {
	display: inline-block;
	width: 20px;
	height: 30px;
    }
</style>
<div class="Calender">
    <div class="Pagination">
	<a href="javascript:;" class="last" onclick="fctShowCal('<?= $_POST['id'] ?>','<?= $_POST['strName'] ?>','<?php echo yearBack($date); ?>')">|&laquo;</a> 
	<a href="javascript:;" class="last" onclick="fctShowCal('<?= $_POST['id'] ?>','<?= $_POST['strName'] ?>','<?php echo monthBack($date); ?>')">&laquo;</a> 
	<span><?php echo $arrMonth[date('F',$date)];?> <?php echo date('Y',$date); ?></span>
	<a href="javascript:;" class="next" onclick="fctShowCal('<?= $_POST['id'] ?>','<?= $_POST['strName'] ?>','<?php echo monthForward($date); ?>')">&raquo;</a>
	<a href="javascript:;" class="next" onclick="fctShowCal('<?= $_POST['id'] ?>','<?= $_POST['strName'] ?>','<?php echo yearForward($date); ?>')">&raquo;|</a>  
    </div>
    <?php getCalender($date,$headline); ?>
    <div class="Clear"></div>
</div>