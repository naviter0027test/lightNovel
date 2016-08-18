<html>
    <head>
        <title> article form test </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="test" />
            <button>test</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="articleList" />
            nowPage : <input type="text" name="nowPage" /> <br />
            <button>article list</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="articleDel" />
            aid : <input type="text" name="aid" /> <br />
            <button>article del</button>
        </form>
    </body>
</html>
