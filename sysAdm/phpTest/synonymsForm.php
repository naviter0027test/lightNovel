<html>
    <head>
        <title>同義字</title>
        <meta charset="utf-8"/>
    </head>
    <body>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="synonymsAdd" />
            <label class="col-xs-2">同義字</label> 
            <br />
            <input name="key" class="col-xs-3"/>
            <br />
            <label class="col-xs-2">同義字對應</label>
            <br />
            <input name="value" class="col-xs-3"/>
            <br />
            <button>新增</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="synonymsDel" />
            <label class="col-xs-2">sy id</label> 
            <input name="syid" class="col-xs-3"/>
            <br />
            <button>刪除</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="synonymsList" />
            <label class="col-xs-2">nowPage</label> 
            <input name="nowPage" class="col-xs-3"/>
            <br />
            <button>列表</button>
        </form>
    </body>
</html>
