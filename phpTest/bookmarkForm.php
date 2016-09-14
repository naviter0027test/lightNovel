<html>
    <head>
        <title> book marks test </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="../instr.php" method="post">
            <h3>bookmark</h3>
            <input type="hidden" name="instr" value="bookmark" />
            book id: <input type="text" name="bookId" />
            <br />
            <button>bookmark</button>
        </form>
        <form action="../instr.php" method="post">
            <h3>bookmark cancel</h3>
            <input type="hidden" name="instr" value="bookmarkCancel" />
            book id: <input type="text" name="bookId" />
            <br />
            <button>bookmark cancel</button>
        </form>
        <form action="../instr.php" method="post">
            <h3>bookmark list</h3>
            <input type="hidden" name="instr" value="bookmarkList" />
            now page : <input type="text" name="nowPage" />
            <br />
            <button>bookmark list</button>
        </form>
    </body>
</html>
