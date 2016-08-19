<?php session_start(); ?>
<html>
    <head>
        <title> series test</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="seriesAdd" />
            <input type="hidden" name="mId" value="<?=$_SESSION['mid']?>" />
            series name : 
            <input type="text" name="seriesName" /> <br />
            <button>add</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="seriesList" />
            <input type="hidden" name="mId" value="<?=$_SESSION['mid']?>" />
            now page : 
            <input type="text" name="nowPage" /><br />
            page limit : 
            <input type="text" name="pageLimit" /><br />
            <button>list</button>
        </form>

        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="seriesUpd" />
            id : 
            <input type="text" name="asId" /><br />
            series update name : 
            <input type="text" name="seriesName" /><br />
            <button>update</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="seriesDel" />
            <input type="hidden" name="mId" value="<?=$_SESSION['mid']?>" />
            series delete id :
            <input type="text" name="seriesId" /><br />
            <button>delete</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="articleBySid" />
            series id :
            <input type="text" name="seriesId" /><br />
            now page : 
            <input type="text" name="nowPage" /><br />
<!--
            page limit : 
            <input type="text" name="pageLimit" /><br />
-->
            <button>show article</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="allArticleBySid" />
            series id :
            <input type="text" name="seriesId" /><br />
            <button>show all article</button>
        </form>
        <form action="../instr.php" method="post">
            <input type="hidden" name="instr" value="changeArticleChapter" />
            aid : <input type="text" name="aid" /><br />
            chapter : <input type="text" name="chapter" /><br />
            <button>文章的篇章修改</button>
        </form>
    </body>
</html>
