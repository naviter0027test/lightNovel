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
            <input type="hidden" name="instr" value="postArticle" />
            <input type="hidden" name="mId" value="<?=$_SESSION['mid']?>" />
                <span class="col-xs-12">
                    <label class="col-xs-2">標題</label> 
                    <input class="col-xs-6" name="title" type="text" /> 
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">屬性</label> 
                    <input type="radio" name="articleType" value="article" />文 
                    <input type="radio" name="articleType" value="postArticle" />推文 
                    <br />
                </span>
                <span class="col-xs-12">
                    <label class="col-xs-2">分級</label>
                    <select name="level">
                        <option value="Gen">Gen</option>
                        <option value="PG">PG</option>
                        <option value="NC17">NC17</option>
                    </select>
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">主CP1</label>
                    <input type="text" class="smInput" name="cp1[]" placeholder="點我" /> /
                    <input type="text" class="smInput" name="cp1[]" placeholder="點我" />
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">主CP2</label>
                    <input type="text" class="smInput" name="cp2[]" placeholder="點我" /> /
                    <input type="text" class="smInput" name="cp2[]" placeholder="點我" />
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">副CP</label>
                    <input type="text" name="viceCp" placeholder="以分號隔開" />
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">系列</label>
<!--
                    <select name="series">
                        <option value="">請選擇</option>
                        <option value="1">東卯</option>
                        <option value="2">常歌</option>
                    </select>
                或新系列 
-->
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">警告</label>
                    <input type="checkbox" name="alert[]" value="主要角色死亡" class="validate[required]"/>主要角色死亡
                    <input type="checkbox" name="alert[]" value="血腥暴力"  class="validate[required]"/>血腥暴力
                    <input type="checkbox" name="alert[]" value="性轉"  class="validate[required]"/>性转
                    <br />
                    <input type="checkbox" name="alert[]" value="other" />其他
                    <input type="text" name="alert[]" placeholder="自訂，以分號分隔" />
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">標籤</label>
                    <input type="checkbox" name="tag[]" value="ABO"  class="validate[required]"/>ABO 
                    <input type="checkbox" name="tag[]" value="PWP"  class="validate[required]"/>PWP
                    <input type="checkbox" name="tag[]" value="AU"  class="validate[required]"/>AU
                    <input type="checkbox" name="tag[]" value="哨兵向导"  class="validate[required]"/>哨兵向导
                    <input type="checkbox" name="tag[]" value="互攻"  class="validate[required]"/>互攻
                    <br />
                    <input type="checkbox" name="tag[]" value="other" />其他
                    <input type="text" name="tag[]" placeholder="自訂，以分號分隔" />
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">章節標題</label> <input type="text" name="aTitle"/>
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">章节</label> <input class="smInput" name="aChapter" type="text" /> / <input class="smInput" name="chapterSum" type="text" readonly />
                </span>
                <br />
                <span class="col-xs-12">
                    <label class="col-xs-2">章節備註</label> <textarea name="aMemo" class="col-xs-8" placeholder="最多輸入300字，顯示於文章開頭"> </textarea>
                </span>
                <br />
                <span class="col-xs-12">
                    <h4>文章撰寫</h4>
                    <textarea id="editor1"  class="ckeditor col-xs-12" name="content"></textarea>
                </span>
                <span class="col-xs-12">
                    <button class="col-xs-3 postBtn">發文</button>
                </span>
            </form>
            <form class="cpPanel">
                <a class="col-xs-5 nowChoose" href="#cpAclass">明楼及衍生</a>
                <a class="col-xs-5" href="#cpBclass">明诚及衍生</a>
                <div class="col-xs-12" id="cpAclass">
                    <input type="radio" name="cpClassChoose" value="明楼" />明楼
                    <input type="radio" name="cpClassChoose" value="蔺晨" />蔺晨
                    <input type="radio" name="cpClassChoose" value="谭宗明" />谭宗明
                    <input type="radio" name="cpClassChoose" value="凌远" />凌远
                    <input type="radio" name="cpClassChoose" value="荣石" />荣石
                    <input type="radio" name="cpClassChoose" value="黄志雄" />黄志雄
                    <input type="radio" name="cpClassChoose" value="胡八一" />胡八一
                    <input type="radio" name="cpClassChoose" value="杜见锋" />杜见锋
                    <input type="radio" name="cpClassChoose" value="沈剑秋" />沈剑秋
                    <input type="radio" name="cpClassChoose" value="李川奇" />李川奇
                    <input type="radio" name="cpClassChoose" value="何鸣" />何鸣
                    <input type="radio" name="cpClassChoose" value="徐安" />徐安
                    <input type="radio" name="cpClassChoose" value="刘彻" />刘彻
                    <input type="radio" name="cpClassChoose" value="岳振声" />岳振声
                    <input type="radio" name="cpClassChoose" value="牧良逢" />牧良逢
                    <input type="radio" name="cpClassChoose" value="秦玄策" />秦玄策
                    <input type="radio" name="cpClassChoose" value="周永嘉" />周永嘉
                    <input type="radio" name="cpClassChoose" value="陈近南" />陈近南
                    <input type="radio" name="cpClassChoose" value="刘华盛" />刘华盛
                    <input type="radio" name="cpClassChoose" value="李天北" />李天北
                    <input type="radio" name="cpClassChoose" value="徐世平" />徐世平
                    <input type="radio" name="cpClassChoose" value="董警官" />董警官
                    <input type="radio" name="cpClassChoose" value="刘一魁" />刘一魁
                    <input type="radio" name="cpClassChoose" value="马少飞" />马少飞
                    <input type="radio" name="cpClassChoose" value="刘凯强" />刘凯强
                    <input type="radio" name="cpClassChoose" value="张红兵" />张红兵
                    <input type="radio" name="cpClassChoose" value="王开复" />王开复
                    <input type="radio" name="cpClassChoose" value="重光葵" />重光葵
                    <input type="radio" name="cpClassChoose" value="龟田一郎" />龟田一郎
                </div>
                <div class="col-xs-12" id="cpBclass">
                    <input type="radio" name="cpClassChoose" value="明诚" />明诚
                    <input type="radio" name="cpClassChoose" value="萧景琰" />萧景琰
                    <input type="radio" name="cpClassChoose" value="赵启平" />赵启平
                    <input type="radio" name="cpClassChoose" value="方孟韦" />方孟韦
                    <input type="radio" name="cpClassChoose" value="李熏然" />李熏然
                    <input type="radio" name="cpClassChoose" value="石太璞" />石太璞
                    <input type="radio" name="cpClassChoose" value="季白" />季白
                    <input type="radio" name="cpClassChoose" value="陈亦度" />陈亦度
                    <input type="radio" name="cpClassChoose" value="曲和" />曲和
                    <input type="radio" name="cpClassChoose" value="戴刀" />戴刀
                    <input type="radio" name="cpClassChoose" value="靳以" />靳以
                    <input type="radio" name="cpClassChoose" value="齐勇" />齐勇
                    <input type="radio" name="cpClassChoose" value="郝晨" />郝晨
                    <input type="radio" name="cpClassChoose" value="王瑞" />王瑞
                    <input type="radio" name="cpClassChoose" value="许一霖" />许一霖
                    <input type="radio" name="cpClassChoose" value="公孙泽" />公孙泽
                    <input type="radio" name="cpClassChoose" value="刘承志" />刘承志
                    <input type="radio" name="cpClassChoose" value="陈家明" />陈家明
                    <input type="radio" name="cpClassChoose" value="吴大维" />吴大维
                    <input type="radio" name="cpClassChoose" value="黄元尚" />黄元尚
                    <input type="radio" name="cpClassChoose" value="黄克功" />黄克功
                    <input type="radio" name="cpClassChoose" value="史路威" />史路威
                </div>
                <button class="col-xs-3 check">確定</button>
            <button>post article</button>
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
            account : <input type="text" name="mamber" />
            <button>member login<button>
        </form>
    </body>
</html>
