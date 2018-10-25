<?php
	include_once "init.php";
	include_once "users.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Helli Second Grade Codeforces Ranking</title>

		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1 class="text-center"> Helli second grade Codeforces Ranking </h1>
				<p class="text-center">
					Started from: <?php echo date("D M j G:i:s T Y", $db->select("logInfo", array("startTime"))[0]["startTime"]); ?>
				</p>
			</div>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th> # </th>
							<th> Name </th>
							<th> Score </th>
							<?php
								foreach ($problems as $problem => $score)
								{
							?>
									<th> <?php echo $problem; ?> <div class="small"> <?php echo $score; ?> </div> </th>
							<?php
								}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
							$users = getUsers(true);
							$count = 1;
							foreach ($users as $user)
							{
						?>
								<tr>
									<td> <?php echo $count; ?> </td>
									<td> <?php echo $user->getName(); ?> </td>
									<th> <?php echo $user->calculateScore(); ?> </td>
									<?php
										foreach ($problems as $problem => $score)
										{
									?>
											<td><?php echo $user->getProblemCnt($problem); ?></td>
									<?php
										}
									?>
								</tr>
						<?php
								$count++;
							}
						?>
					</tbody>
				</table>
			</div>

		</div>
		<div class="footer">
			<p class="text-center">The scoreboard will be updated every 30 minutes</p>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>


