<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
//Connect code
$db = new PDO('mysql:host=localhost;dbname=employees;charset=utf8', 'root', 'protodrake124', array(PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//Pages
echo '<a href="index.php?page=R1">Who is the highest paid employee?</a><br>';
echo '<a href="index.php?page=R2">Who is the highest paid employee between 1985 and 1981?</a><br>';
echo '<a href="index.php?page=R3">Which department currently has highest paid department manager?</a><br>';
echo '<a href="index.php?page=R4">What are the titles of all the departments?</a><br>';
echo '<a href="index.php?page=R5">Who are the current department heads?</a><br>';
echo '<a href="index.php?page=R6">Who is highest paid employee that is not a department head?</a><br>';
echo '<a href="index.php?page=R7">Who is currently the lowest paid employee?</a><br>';
echo '<a href="index.php?page=R8">How many employees currently work in each department?</a><br>';
echo '<a href="index.php?page=R9">How much does each department currently spend on salaries? </a><br>';
echo '<a href="index.php?page=R10">How much is currently spent for all salaries?</a><br>';
echo '<a href="index.php?page=insert">Insert a new Employee</a><br>';
echo '<a href="index.php?page=update">Update an Employee</a><br>';
echo '<br><br>';
//Page 1
if($_GET['page']=="R1")
{
	$sql = 'select employees.emp_no, employees.first_name, employees.last_name, salaries.salary from
	 employees left join salaries on employees.emp_no = salaries.emp_no order by salary DESC limit 1;';
}
//Page 2
if($_GET['page']=="R2")
{
        $sql = 'select employees.emp_no, employees.first_name, employees.last_name, salaries.salary from employees left join salaries on employees.emp_no = salaries.emp_no where salaries.from_date between "1981-01-01" and "1985-01-01" order by salary DESC limit 1;';
}
	
//Page 3
if($_GET['page']=="R3")
{
        $sql = 'select employees.emp_no, employees.first_name, employees.last_name, departments.dept_name, salaries.salary from employees left join salaries on employees.emp_no = salaries.emp_no join titles on employees.emp_no = titles.emp_no join dept_manager on  dept_manager.emp_no = employees.emp_no join departments on departments.dept_no = dept_manager.dept_no where titles.title = "Manager" and salaries.to_date = "9999-01-01" order by salaries.salary DESC limit 1;';
}
//Page 4
if($_GET['page']=="R4")
{
        $sql = 'select dept_name from departments group by dept_name;';
}
//Page 5
if($_GET['page']=="R5")
{
        $sql = 'select employees.emp_no, employees.first_name, employees.last_name, titles.title from employees left join titles on employees.emp_no = titles.emp_no where titles.title = "Manager" and titles.to_date = "9999-01-01";';
}
//Page 6
if($_GET['page']=="R6")
{
        $sql = 'select employees.emp_no, employees.first_name, employees.last_name, titles.title, salaries.salary from employees left join salaries on employees.emp_no = salaries.emp_no join titles on employees.emp_no = titles.emp_no where titles.title != "Manager" order by salaries.salary DESC limit 1;';
}
//Page 7
if($_GET['page']=="R7")
{       
	 $sql = 'select employees.emp_no, employees.first_name, employees.last_name, salaries.salary from employees left join salaries on employees.emp_no = salaries.emp_no order by salaries.emp_no  ASC limit 1;';
}
//Page 8
if($_GET['page']=="R8")
{       
	 $sql = 'select departments.dept_name, count(departments.dept_name) from dept_emp left join employees on employees.emp_no = dept_emp.emp_no join departments on departments.dept_no = dept_emp.dept_no where to_date = "9999-01-01" group by dept_name;';
}
//Page 9
if($_GET['page']=="R9")
{       
	 $sql = 'select departments.dept_name, sum(salaries.salary) from dept_emp left join employees on employees.emp_no = dept_emp.emp_no join departments on departments.dept_no = dept_emp.dept_no join salaries on employees.emp_no = salaries.emp_no where dept_emp.to_date = "9999-01-01" group by dept_name;';
}
//Page 10
if($_GET['page']=="R10")
{       
	 $sql = 'select sum(salaries.salary) from employees left join salaries on employees.emp_no = salaries.emp_no join titles on employees.emp_no = titles.emp_no;';
}
//Insert a new Employee
if($_GET['page']=="insert")
{
	echo "<form name='Insert Employee' action='insert.php' method='get'>";
	echo "First Name <input type=text name='firstname' placeholder='First Name'><br>";
	echo "Last Name <input type=text name='lastname' placeholder='Last Name'><br>";
	echo "Birth data <input type=text name='birthdate' placeholder='yyyy-mm-dd'><br>";
	echo "Hire data <input type=text name=hiredate placeholder='yyyy-mm-dd'><br>";
	echo "<input type='radio' name='gender' value='M'>Male<br>";
	echo "<input type='radio' name='gender' value='F'>Female<br>";	
	
	echo "<input type=submit id='Insert' value='Add Employee'>";
        echo "</form>";
}
//Update an employee
if($_GET['page']=="update")
{
	$sql = "select * from employees order by emp_no desc limit 5;";
	
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
			echo "<form name='Update Employee' action='update.php' method='get'>";
			$emp = ($row['emp_no']);
                        //Printer each entrey number in the table
                        echo "<tr>";
                        echo "<td>";
                        echo ("Result Number");
                        echo "</td>";
                        echo "<td>";
			echo ($num."   ");
			echo "<input type=submit id='Update' value='Update'>";
			echo "<input type='hidden' name='emp' value='$emp'";
                        echo "</td>";
                        echo "</tr>";
			echo "</form>";   
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
/*
$sql = 'SELECT * FROM departments LIMIT 5';
foreach($db->query($sql) as $row) {
print_r($row);
print("<br> /");
}
print_r($db);
*/
?>
