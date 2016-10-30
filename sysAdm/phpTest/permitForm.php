<html>
    <head>
        <title> permit adm</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="permitList" />
            <button>permit list</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="permitGet" />
            permit id :
            <input type="text" name="admid" />
            <button>permit get</button>
        </form>
    </body>
</html>
