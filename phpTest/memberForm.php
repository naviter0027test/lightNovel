<?php
session_start();
?>
<html>
    <head>
        <title> member form </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <label><?=$_SESSION['mid']?></label>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="test" />
            <button>test</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="isLogin" />
            <button>isLogin</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="register" />
            user:<input name="user" /><br />
            pass:<input type="password" name="pass" /><br />
            email:<input name="email" /><br />
            <img src="../instr.php?instr=captchaRegister" />
            <input type="text" name="captcha" />
            <br />
            <button>register</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="login" />
            user:<input name="user" /><br />
            pass:<input type="password" name="pass" /><br />
            <img src="../instr.php?instr=captchaLogin" />
            <input type="text" name="captcha" />
            <br />
            <button>login</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="logout" />
            <button>logout</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="addMessage" />
            aid : <input type="text" name="aid" /><br />
            message : <textarea name="message"></textarea><br />
            <button>leave message</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="findMem" />
            account : <input type="text" name="user" />
            <br />
            <button>find member</button>
        </form>

        <form action="memLogin.php" method="post">
            account : <input type="text" name="member" />
            <button>member login</button>
        </form>

        <br />
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="giftList" />
            gift list<br />
            now page : <input type="text" name="nowPage" /><br />
            <button>see</button>
        </form>

        <form action="../instr.php" method="post">
            praise amount
            <input type="hidden" name="instr" value="praiseAmount" />
            <button>check</button>
        </form>

        <form action="../instr.php" method="post">
            gift amount
            <input type="hidden" name="instr" value="giftAmount" />
            <button>check </button>
        </form>

        <form action="../instr.php" method="post">
            message amount
            <input type="hidden" name="instr" value="msgAmount" />
            <button>check </button>
        </form>

        <form action="../instr.php" method="post">
            subscript update amount
            <input type="hidden" name="instr" value="subscriptUpdAmount" />
            <button>check</button>
        </form>
    </body>
</html>
