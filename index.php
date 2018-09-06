<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TI-Trash | Gestão de Resíduos</title>
    <link rel="stylesheet" href="css.css">
</head>

<style>
    body {
        background-color: ghostwhite;
    }

    .button {
        background-color: #4CAF50;
        /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        -webkit-transition-duration: 0.4s;
        /* Safari */
        transition-duration: 0.4s;
    }

    .button:hover {
        box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
    }

    input[type=text] {
        width: 22%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 2px solid green;
        border-radius: 4px;
    }

</style>

<body>
    <img class="center" src="img/logo/LOGO1.gif" style="width:400px;height:400px;margin-left: auto;margin-right: auto;display: block;"><br>

    <h1 style="text-align:center">Introduza o seu ID</h1>

    <form action="results.php" method="post" align="center">
        <label for="user_id"></label>
        <input type="text" id="user_id" name="user_id" class="formfield w0"><br>
        <input class="button" type="submit" value="Pesquisar">
    </form>
    <br>
    <!--<p style="text-align: right">Aplicação WEB produzida por<br><b>Tiago Gonçalves&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><br>com ajuda de&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><b>Tiago Cardoso&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></p>-->

    <div style="text-align:center; font-size: 10px;">
        <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">TI-Trash | Residue Management WEB APP</span><br> by<br><span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName">TI-Trash Group<br></span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.
    </div>
    <script>
    $(function() {
    $('#user_id').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });
});
    </script>
</body>

</html>
