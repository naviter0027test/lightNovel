<html>
    <head>
        <title> admin form test </title>
        <meta charset="utf-8" />
    </head>
    <body>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="isLogin" />
            <button>is login</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="login" />
            user : <input type="text" name="user" />
            <br />
            pass : <input type="password" name="pass" />
            <br />
            captcha : <img src="../instr.php?instr=captchaLogin" /><input type="text" name="captcha" />
            <button>login</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="logout" />
            <button>logout</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="cpGet" />
            <button>cp panel get</button>
        </form>

        <form action="../instr.php" method="post">
            get new password <br />
            <input type="hidden" name="instr" value="forget" />
            <button>get new password</button>
        </form>
    </body>
</html>

