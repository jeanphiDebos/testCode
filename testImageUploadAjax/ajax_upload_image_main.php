<html>
<head>
    <title>Ajax Image Upload Using PHP and jQuery</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
<div class="main">
    <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
		<input type="file" name="file" id="file" required/>
		<input type="submit" value="Upload" class="submit"/>
    </form>
</div>
<div id="message"></div>
</body>
</html>