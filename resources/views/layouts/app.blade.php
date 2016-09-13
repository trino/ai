<?php $start_loading_time = microtime(true); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://select2.github.io/select2/select2-3.5.2/select2.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js"></script>
    <script src="//select2.github.io/select2/select2-3.4.2/select2.js"></script>
</head>
<body
style="background-color: silver;
background-image: linear-gradient(335deg, #b00 23px, transparent 23px),
linear-gradient(155deg, #d00 23px, transparent 23px),
linear-gradient(335deg, #b00 23px, transparent 23px),
linear-gradient(155deg, #d00 23px, transparent 23px);
background-size: 58px 58px;
background-position: 0px 2px, 4px 35px, 29px 31px, 34px 6px;"
>
<div class="container p-a-0 m-t-1">
@yield('content')
</div>
</body>
</html>
<?php
$end_loading_time = microtime(true);
printf("Page generated in %f seconds. ", $end_loading_time - $start_loading_time);
?>