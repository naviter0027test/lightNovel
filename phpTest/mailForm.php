<?php session_start(); ?>
<html>
    <head>
        <title> mail form </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="../instr.php" method="post">
            <h2>mail test</h2>
            <input type="hidden" name="instr" value="mailTest" />
            mail to : <input type="text" name="mailto" />
            <br />
            content : <textarea name="content"></textarea>
            <button>send</button>
        </form>
    </body>
</html>
