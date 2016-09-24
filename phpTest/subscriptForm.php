<?php session_start(); ?>
<html>
    <head>
        <title> subscription test</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <h1> mid : <?=$_SESSION['mid']?></h1>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="subscript" />
            aid : 
            <input type="text" name="aid" /><br />
            asid : 
            <input type="text" name="asid" /><br />
            mid : 
            <input type="text" name="mid" /><br />
            <button>訂閱</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="subScriptList" />
            now page :
            <input type="text" name="nowPage"/><br />
            aid : 
            <input type="radio" name="subCls" value="a_id" /><br />
            asid : 
            <input type="radio" name="subCls" value="as_id" /><br />
            mid : 
            <input type="radio" name="subCls" value="m_id" /><br />
            <button>列表</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="subScriptDel" />
            aid : 
            <input type="text" name="aid" /><br />
            asid : 
            <input type="text" name="asid" /><br />
            mid : 
            <input type="text" name="mid" /><br />
            <button>取消訂閱</button>
        </form>
    </body>
</html>

