<?php 
// Math operators
$operators=array('+','-','*');

// first number random value
$first_num = rand(1,5);

// second number random value
$second_num = rand(6,11);

shuffle($operators);

$expression = $second_num. "&nbsp;&nbsp;".$operators[0]."&nbsp;&nbsp;".$first_num;

eval("\$session_var=".$second_num.$operators[0].$first_num.";");

$_SESSION['security_number'] = $session_var;
?>

<span style="font-size:25px;"><?php echo $expression?>&nbsp;&nbsp;=&nbsp;&nbsp;</span><input style="width:30%;" placeholder="value here" name="captcha_code" type="text" class="forminput required">