<html>
    <head>
        <title> article form test </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="../instr.php" method="post">
            <h2>使用者列表</h2>
            <input type="hidden" name="instr" value="memberList" />
            nowPage : <input type="text" name="nowPage" /> <br />
            <button>member list</button>
        </form>

        <form action="../instr.php" method="post">
            <h2>使用者啟用與否</h2>
            <input type="hidden" name="instr" value="memberActive" />
            mem id : <input type="text" name="mid" /> <br />
            <select name="active">
                <option value="Y">啟用</option>
                <option value="N">未啟用</option>
                <option value="D">禁用</option>
            </select>
            <button>修改</button>
        </form>

        <form action="../instr.php" method="post">
            <h2>使用者刪除</h2>
            <input type="hidden" name="instr" value="memberDel" />
            mem id : <input type="text" name="mid" /> <br />
            <button>刪除</button>
        </form>
    </body>
</html>

