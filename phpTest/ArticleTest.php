<meta charset="utf-8" />
<?php
/*
 *  File Name :
 *	ArticleTest.php
 *  Describe :
 *      測試以下功能
 *	文章新增、文章修改、文章刪除、文章列表
 *  Start Date :
 *	2016.07.05
 *  Author :
 *	Lanker
 */
require_once("../srvLib/simpletest/autorun.php");

class ArticleTest extends UnitTestCase {
    function testInit() {
        require_once("../Article/Article.php");
        $this->assertEqual(true, true);
    }

    function testAdd() {
        require_once("../Article/Series.php");
        require_once("../Article/Article.php");
        $series = new Series();
        $articleAdm = new Article();

        $content = "活他家市車的……條來資大你校朋：牛海於藝然些球滿此日難各服興星、作苦要中神裡假自受英必須；輪獨存的提般著熱時超事：必不陸養推……那一會，人年港德物得英進東，合物一性民智十魚算倒提異西分不？

            位人信龍：回常明和座，它什位：是我病年不生時對。研斷育可受嗎他的奇工的不加背生。只望園感少何說文錢領師以唱因費形術樣的，神象角他外一健中河數來、看媽決？進期工標母望經原我後時行人，過品個小清類，有哥直資了一界……基人國、樣那市到來金事公王個的件孩關故收長人急？思人界合代，大師頭新落萬面股藝低多日會……廣自的些由的、東落樂微，以實亞化覺考技學氣……包英關提急算驚中朋；出陽實：年造的，字打健自為樂但老但程安重兩從民弟不策不題上她確此嚴老安黃！如及班一作係了媽青著客王兒而告點質書，上他上聯獨；轉人園生。

            工可散牛常的散現語，級急生還小怕林，賣布老，流己部團西時到興，一長過故達知嗎的者的；太本這小了相輕臺業到成第去是可樣現，看大麗手天一車一靜像樣政，病器再前園沒不家，十來層學負機至也形簡程來制主李成服知言率長本家很的內人可其通有傳一成告配生與了樹他裡進停聽制帶似去益的南期臺親媽？活法也預他麼並能死日利得應這著院場夫靈且怕大得找，兒聯白爸現個才有兒品、研個活近差房飯電山寫這，情在裡。任動西麼們歌帶技內美的微代時臺國出體品和世病也品喜今：也熱接金陽電備以許、臺生一子你車加紅。

            大導國根樣所式想土裡制和受找吃名，大先然。保息教資物年……經我提人己分不處長不所破西河經安者：人影我對！業市有行出了能要學。他產院天化孩但土不我臺動立不華處論講元客來放國。成麼想當一似角續麼策我親展他，成坡史坐子個阿太；民會族回改家成由加配器開藝麼十新去遠被，權經。

            靜生銀，一手日過書才知知多不該方情精國。房員視形病告果子。生感我臺因手表關其價看、流室學無。片生下形就目高，業理情覺和賽是個始特已因，越口變。

            出爸不：對性離斷跟不怕調助！

            什假死我、以際我體在以言系馬天力物利發太英，們一車破快機市不我；有養金；學的動天全我，布終東方主一不感優雖代開此，空到去立兩便事上頭其減山叫外子一時近：了五事有談我說樣，規長時行第果有著習才藝見上，黃我成美出了民了十影持今受向告。

            算手個案達開，差價都……方魚助低高產；好外準，信此夫、土生太，知情年：不機服依真，包隨心一送一提越班動！小長住環之對小生園麼入直情聯們熱，口方可子不氣，記樹告來臺教離境？

            用明上，出學樣特市題內臺羅是議反還科才種工環身代化，式化隊能景約了歡生裡政有紀……輕才院小？先眼趣主議東港節業人倒我傳立：不前資你，能北園細間防死股的。

            目夫的原更很物得到音家長家東創？線頭只景樂立，夜不平行我息身和連道校要或資克走國輕無安文而送臺許，合那火級空程：所春時優招來，小引克別下整們狀地下學，當增腳農心那人縣！小進上大，為點成費說演造成媽到；用書日時處些。來童年名稱向女風所草當年充有英樓生康這！";
        $cpAArr = Array( "明楼", "蔺晨", "谭宗明", "凌远", "荣石" ,"黄志雄" ,"胡八一" ,"杜见锋" ,"沈剑秋" ,"李川奇" ,"何鸣" ,"徐安" ,"刘彻" ,"岳振声" ,"牧良逢" ,"秦玄策" ,"周永嘉" ,"陈近南" ,"刘华盛" ,"李天北" ,"徐世平" ,"董警官" ,"刘一魁" ,"马少飞" ,"刘凯强" ,"张红兵" ,"王开复", "重光葵");
        $cpBArr = Array("明诚" ,"萧景琰" ,"赵启平" ,"方孟韦" ,"李熏然" ,"石太璞" ,"季白" ,"陈亦度" ,"曲和" ,"戴刀" ,"靳以" ,"齐勇" ,"郝晨" ,"王瑞" ,"许一霖" ,"公孙泽" ,"刘承志" ,"陈家明" ,"吴大维" ,"黄元尚" ,"黄克功" ,"史路威");
        $article = Array();
        $article['title'] = "文章標題". rand(1000, 9999);
        $articleType = rand(0, 1);
        $article['articleType'] = ($articleType == 0 ? "article" : "postArticle");
        $articleLevel = rand(0, 2);
        if($articleLevel == 0)
            $article['level'] = "Gen";
        else if($articleLevel == 1)
            $article['level'] = "PG";
        else
            $article['level'] = "NC17";
        $article['cp1'] = $cpAArr[rand(0, count($cpAArr)-1)]. ",". $cpBArr[rand(0, count($cpBArr)-1)];
        $article['cp2'] = $cpAArr[rand(0, count($cpAArr)-1)]. ",". $cpBArr[rand(0, count($cpBArr)-1)];
        $article['subCp'] = $cpAArr[rand(0, count($cpAArr)-1)]. ",". $cpBArr[rand(0, count($cpBArr)-1)]; 
        $article['series'] = $series->getOne(2)['as_id'];

        $alertArr = Array("OMC", "OFC", "DEATH", "NON-CON", "BF");
        $article['alert'] = $alertArr[rand(0, count($alertArr)-1)];
        $tagArr = Array("ABO", "PWP", "AU", "domsub", "crossover");
        $article['tag'] = $tagArr[rand(0, count($tagArr)-1)];
        $article['aTitle'] = "chapter XX";
        $article['aChapter'] = "";
        $article['aMemo'] = "memo test insert";
        $contentStart = rand(0, 400);
        echo $contentStart. ",";
        echo $contentStart%3;
        $article['content'] = substr($content, $contentStart-$contentStart%3, rand(400, strlen($content)-1));
        require_once("../Member/Member.php");
        $randMember = new Member();
        $article['mId'] = $randMember->randOneMem()['m_id'];
        print_r($article);
        $articleAdm->articleAdd($article);
    }

    function testList() {
        require_once("../Article/Article.php");
        $articleAdm = new Article();

        $mid = 1;
        $lists = $articleAdm->lastList($mid);
        //print_r($lists);
        $article1 = $lists[0];
        $article2 = $lists[1];
        $this->assertTrue($article1['a_crtime'] > $article2['a_crtime']);
    }

    function testGet() {
        require_once("../Article/Article.php");
        $articleAdm = new Article();
        $data = $articleAdm->get(2);
        $this->assertEqual($data['a_title'], "文章標題6939");
        $this->assertEqual($data['a_id'], 2);
    }
}
