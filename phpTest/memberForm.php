<html>
    <head>
        <title> member form </title>
        <meta charset="utf-8" />
    </head>
    <body>
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
    </body>
</html>
