<?php

class Project
{
public static function getInstance()
{
static $instance = null;
if (null === $instance) 
{
$instance = new static();
}
return $instance;
}
protected function __construct()
{

error_reporting(-1);
ini_set('display_errors', 'On');

//Connect code
$db = new PDO('mysql:host=localhost;dbname=finalproject;charset=utf8', 'root', 'protodrake124', array(PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//Pages
echo '<a href="index.php?page=R1">Colleges with the highest percentage of women students</a><br>';
echo '<a href="index.php?page=R2">Colleges with the highest percentage of male students</a><br>';
echo '<a href="index.php?page=R3">Colleges with the largest endowment overall</a><br>';
echo '<a href="index.php?page=R4">Colleges with the largest enrollment of freshman</a><br>';
echo '<a href="index.php?page=R5">Colleges with the highest revenue from tuition</a><br>';
echo '<a href="index.php?page=R6">Colleges with the lowest non zero tuition revenue</a><br>';
echo '<a href="index.php?page=R7">The top 10 colleges by region</a><br>';

echo '<br><br>';
//Page 1
if($_GET['page']=="R1")
{
	$sql = 'select institution, percent_women from (select UNITID, institution from hd2013) as lable1 left join (select UNITID, ((total_women/total)*100) as percent_women from ef2013b) as lable2 on lable1.UNITID = lable2.UNITID WHERE NOT percent_women IS NULL group by lable1.institution order by percent_women desc;';
}
//Page 2
if($_GET['page']=="R2")
{
        $sql = 'select institution, percent_men from (select UNITID, institution from hd2013) as lable1 left join (select UNITID, ((total_men/total)*100) as percent_men from ef2013b) as lable2 on lable1.UNITID = lable2.UNITID WHERE NOT percent_men IS NULL group by lable1.institution order by percent_men desc;';
}
	
//Page 3
if($_GET['page']=="R3")
{
        $sql = 'select institution, endowment from (select UNITID, institution from hd2013) as lable1 left join (select UNITID, endowment from f1213_f1a) as lable2 on lable1.UNITID = lable2.UNITID WHERE NOT endowment IS NULL and not endowment = 0 group by lable1.institution order by endowment desc;';
}
//Page 4
if($_GET['page']=="R4")
{
        $sql = 'select institution, total_of_men_and_women_enrolled from (select UNITID, institution from hd2013) as lable1 left join (select UNITID, total_of_men_and_women_enrolled from ef2013a) as lable2 on lable1.UNITID = lable2.UNITID WHERE NOT total_of_men_and_women_enrolled IS NULL and not total_of_men_and_women_enrolled = 0 group by lable1.institution order by total_of_men_and_women_enrolled desc;';
}
//Page 5
if($_GET['page']=="R5")
{
        $sql = 'select institution, tuition from (select UNITID, institution from hd2013) as lable1 left join (select UNITID, tuition from f1213_f1a) as lable2 on lable1.UNITID = lable2.UNITID WHERE NOT tuition IS NULL and not tuition = 0 group by lable1.institution order by tuition desc;';
}
//Page 6
if($_GET['page']=="R6")
{
        $sql = 'select institution, tuition from (select UNITID, institution from hd2013) as lable1 left join (select UNITID, tuition from f1213_f1a) as lable2 on lable1.UNITID = lable2.UNITID WHERE NOT tuition IS NULL and not tuition = 0 group by lable1.institution order by tuition asc;';
}
//Page 7
if($_GET['page']=="R7")
{       
	 $sql = '';
}

// Sorts the array and prints it
// This statment detects if the button clicked is a request R
if (strpos($_GET['page'],"R")===0)
{
	$num=1;
	echo "<table border='1' style='width:100%' table-layout: fixed>";
	foreach($db->query($sql) as $row)
	{
	
		//remove duplicates	
		$remove=0;
		foreach($row as $x)
		{
			unset($row[$remove]);
			$remove++;
		}
			//Printer each entrey number in the table
                        echo "<tr>";
                        echo "<td>";
                        echo ("Result Number");
                        echo "</td>";
                        echo "<td>";
                        echo ($num);
                        echo "</td>";
                        echo "</tr>";	
		foreach ($row as $key => $value)
		{
			echo "<tr>";
			echo "<td>";
			echo ($key);
			echo "</td>";
			echo "<td>";
			echo ($value);
			echo "</td>";
			echo "</tr>";	
		}
		$num++;
		echo("<tr><td><br></td><td><br></td></tr>");
	}
	echo "</table>";
}
    
}//constructor
}//class

$obj = Project::getInstance();   
?>
