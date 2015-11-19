<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>
  	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

		<div class="container white" >
		    <h1>home</h1>
		<?php
		include('db_form2.php');
		$form = new Db_form;

		$form->create(['name'=>'Name', 'email'=>'Email', 'pass'=>'Password']);

		$qr = [ 
			['id'=>1, 'user_name'=>'Test 1', 'user_level'=>'admin'],
			['id'=>2, 'user_name'=>'Test 2', 'user_level'=>'admin'] 
		];

		$form->field('name')->sort(1)
			->type('dropdown')
			->options( $form->setOptions($qr, 'id', 'user_name') )
			->attr( ['disabled'=>true] )
			->value(2);

		$form->field('email')
			->sort(2)
			->type('empty')
			->value('<h2>My Content</h2>');

		$form->render();

		?>

		</div>
  
 	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>