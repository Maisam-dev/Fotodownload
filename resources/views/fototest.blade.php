<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<div class="jumbotron text-center" style="background-color:#5cb85c">
    <h1>VOG Linz</h1>
    <h2>Foto Test</h2>
    <p>Bitte entweder Foto hochladen oder den Name vom Foto eintragen, wenn das Foto in der Datenbank gespeichert wurde!</p>
</div>

<div class="container">
    <p style="
         color:  #5cb85c;
          font-size: xx-large;
         text-indent: 30%">
{{$msg}}
    </p>
    <form method="post" action={{url('fototest')}} enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="FotoName">Foto Name:</label>
            <input type="text" class="form-control"  id="FotoName" name="FotoName" placeholder="Enter Foto Name"/>
        </div>
        <div class="form-group">
            <label for="upload">Upload Foto:</label>
            <input type="file" id="upload" name="Foto" class="btn btn-success" placeholder="Enter Foto"/>
        </div>
        <input name="Senden" id="submit" class="btn btn-success" type="submit" value="senden" />
    </form>
    <?php  echo $html; ?>



</div>
<div style="background-color:#5cb85c; min-width:100%; min-height:40px; position: fixed; bottom: 0px; display:flex; align-items:center; justify-content: center"   >
    <p>@VOG Linz 2021</p>
</div>

</body>
</html>
