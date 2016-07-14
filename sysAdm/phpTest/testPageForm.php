<form action="../instr.php" method="post">
    <input type="hidden" name="instr" value="test" />
    test instr <br />
    <button>test</button>
</form>

<form action="../instr.php" method="post">
    <input type="hidden" name="instr" value="pageShow"/>
    頁面輸入
    <input type="text" name="page" />
    <button>post</button>
</form>

<form action="../instr.php" method="post">
    <input type="hidden" name="instr" value="pageEdit"/>
    <input type="hidden" name="page" value="test"/>
    頁面修改<br />
    <input type="text" name="p_title" /><br />
    <textarea name="p_content"></textarea><br />
    <button>edit</button>
</form>
