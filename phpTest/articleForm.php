<?php session_start(); ?>
<html>
    <head>
        <title> series test</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <h1> mid : <?=$_SESSION['mid']?></h1>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="articleList" />
            now page : 
            <input type="text" name="nowPage" /><br />
            page limit : 
            <input type="text" name="pageLimit" /><br />
            <button>list</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="articleDel" />
            <input type="hidden" name="mId" value="<?=$_SESSION['mid']?>" />
            article delete id :
            <input type="text" name="articleId" /><br />
            <button>delete</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="articleGet" />
            <input type="hidden" name="mId" value="<?=$_SESSION['mid']?>" />
            article id :
            <input type="text" name="articleId" /><br />
            <button>delete</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="mySeriesList" />
            now page : 
            <input type="text" name="nowPage" />
            <br />
            page limit : 
            <input type="text" name="pageLimit" />
            <br />
            <button>my series list</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="myLastArticle" />
            now page : 
            <input type="text" name="nowPage" />
            <br />
            page limit : 
            <input type="text" name="pageLimit" />
            <br />
            <button>my last article list</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="memSrsPages" />
            <br />
            <button>member series pages</button>
        </form>
    </body>
</html>
