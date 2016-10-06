<?php session_start(); ?>
<html>
    <head>
        <title> message form </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="msgList" />
            aid : <input type="text" name="aid" /><br />
            now page : <input type="text" name="nowPage" /><br />
            <button>msgList</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="msgReply" />
            msid : <input type="text" name="msid" /><br />
            <textarea name="replyText"></textarea><br />
            <button>reply</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="msgDelReply" />
            msid : <input type="text" name="msid" /><br />
            <button>del msg reply</button>
        </form>
    </body>
</html>
