<?php
/*
 *  File Name :
 *	Article.php
 *  Describe :
 *	文章發表 、
 *	文章列表 、
 *  Start Date :
 *	2016.07.06
 *  Author :
 *	Lanker
 */

class Article {
    private $dbAdm;
    private $table;

    public function __construct() {
        if(file_exists("../srvLib/MysqlCon.php")) 
            require_once("../srvLib/MysqlCon.php");
        else
            require_once("srvLib/MysqlCon.php");
        if(file_exists("../server/config.php"))
            require_once("../server/config.php");
        else
            require_once("server/config.php");
        $this->config = new Config();
        $config = $this->config;
        $this->dbAdm = new MysqlCon(
            $config->getHost(), $config->getDBUser(),
            $config->getDBPass(), $config->getDB());
        $this->table = "Article";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function articleAdd($article) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $insData = Array();
        //$insData['a_title'] = $article['title'];
        $insData['a_attr'] = $article['articleType'];
        $insData['a_level'] = $article['level'];
        if(isset($article['series']))
            $insData['as_id'] = $article['series'];
        $insData['at_id'] = $article['atid'];
        $insData['a_author'] = $article['author'];
        $insData['a_mainCp'] = $article['cp1'];
        $insData['a_mainCp2'] = "";
        if(isset($article['subCp']))
            $insData['a_subCp'] = $article['subCp']; 
        $insData['a_alert'] = $article['alert']; 
        $insData['m_id'] = $article['mId'];    
        if(isset($article['sendUser']))
            $insData['g_sendMid'] = $article['sendUser'];
        $insData['a_tag'] = $article['tag'];
        if(isset($article['aTitle']))
            $insData['a_aTitle'] = $article['aTitle'];
        if(isset($article['aChapter']))
            $insData['a_chapter'] = $article['aChapter'];
        if(isset($article['aMemo']))
            $insData['a_memo'] = $article['aMemo'];
        $insData['a_content'] = $article['content'];
        $insData['a_updtime'] = date('Y-m-d H:i:s');
        $insData['a_crtime'] = date('Y-m-d H:i:s');

        $dbAdm->insertData($tablename, $insData);
        //echo $dbAdm->echoSQL();
        $dbAdm->execSQL();
    }

    public function articleDel($aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;
        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }

    public function articleUpd($article) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $updData = Array();
        //$updData['a_title'] = $article['title'];
        $updData['a_attr'] = $article['articleType'];
        $updData['a_level'] = $article['level'];
        if(isset($article['series']))
            $updData['as_id'] = $article['series'];
        $updData['a_author'] = $article['author'];
        $updData['a_mainCp'] = $article['cp1'];
        $updData['a_mainCp2'] = "";
        if(isset($article['subCp']))
            $updData['a_subCp'] = $article['subCp']; 
        $updData['a_alert'] = $article['alert']; 
        $updData['m_id'] = $article['mId'];    
        $updData['a_tag'] = $article['tag'];
        if(isset($article['aTitle']))
            $updData['a_aTitle'] = $article['aTitle'];
        if(isset($article['aChapter']))
            $updData['a_chapter'] = $article['aChapter'];
        if(isset($article['aMemo']))
            $updData['a_memo'] = $article['aMemo']  ;
        $updData['a_content'] = $article['content'];
        $updData['a_updtime'] = date('Y-m-d H:i:s');

        $conditionArr = Array();
        $conditionArr['a_id'] = $article['aid'];

        $dbAdm->updateData($tablename, $updData, $conditionArr);
        //echo $dbAdm->echoSQL();
        $dbAdm->execSQL();
    }

    public function myAllList($mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        $dbAdm->selectData($tablename, $column, $conditionArr, $order);
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function lastList($mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 5;

        $dbAdm->selectData($tablename, $column, $conditionArr, $order, $limit);
        $dbAdm->sqlSet("select a.*, ss.as_name, att.at_title
            from $tablename a 
            inner join ArticleTitle att on att.at_id = a.at_id
            left join ArticleSeries ss on att.as_id = ss.as_id 
            where a.m_id = $mid order by a_crtime desc limit 0, 5;");
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function myArticles($mid, $nowPage) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        $limit = Array();
        $limit['offset'] = ($nowPage - 1) * 25;
        $limit['amount'] = 25;

        //$dbAdm->selectData($tablename, $column, $conditionArr, $order, $limit);
        $dbAdm->sqlSet("select a.*, ss.as_name, 
            att.at_title, att.at_lastCh
            from $tablename a 
            inner join ArticleTitle att on att.at_id = a.at_id
            left join ArticleSeries ss on att.as_id = ss.as_id 
            where a.m_id = $mid order by a_updtime desc limit ". $limit['offset']. ", ". $limit['amount']);
        $dbAdm->execSQL();

        $artList = $dbAdm->getAll();
        foreach($artList as $idx => $article) {

            $dbAdm->sqlSet("select count(b_id) amount from Bookmark where b_bookId = ". $article['a_id']);
            $dbAdm->execSQL();
            $artList[$idx]['bookmarkCount'] = $dbAdm->getAll()[0]['amount'];

            $dbAdm->sqlSet("select count(ss_id) amount from SubScription where a_id = ". $article['a_id']);
            $dbAdm->execSQL();
            $artList[$idx]['subscriptCount'] = $dbAdm->getAll()[0]['amount'];
        }

        return $artList;
    }

    public function articleList($page) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 25;
        if(isset($page['nowPage']))
            $limit['offset'] = ($page['nowPage'] -1) * 25;
        if(isset($page['pageLimit']))
            $limit['amount'] = $page['pageLimit'];

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        /*
        $dbAdm->sqlSet("
            SELECT pp.praiseAmount, ass.as_name, m.m_user, 
            max(a.a_id) maxAid, count(a.a_chapter) chapterSum,
            CASE WHEN (
                att.at_lastCh =0
            )
            THEN '?'
            ELSE att.at_lastCh
            END AS at_lastCh, a.* , att.at_title
            FROM `Article` a
            INNER JOIN Member m ON m.m_id = a.m_id
            INNER JOIN ArticleTitle att ON a.at_id = att.at_id
            LEFT JOIN ArticleSeries ass ON att.as_id = ass.as_id
            LEFT JOIN (

                SELECT count( p.p_id ) praiseAmount, a.a_id
                FROM Praise p, Article a
                WHERE a.a_id = p.a_id
                GROUP BY a.a_id
            )pp ON pp.a_id = a.a_id
            GROUP BY a.at_id
            order by att.at_updtime desc ".
            " limit ". $limit['offset']. ", ". $limit['amount']);
         */
        $dbAdm->sqlSet("select att.at_id, att.at_title, 
                ass.as_name, m.m_user, att.at_updtime,
                CASE WHEN (
                    att.at_lastCh =0
                )
                THEN '?'
                ELSE att.at_lastCh
                END AS at_lastCh
                from ArticleTitle att
                INNER JOIN Member m ON m.m_id = att.m_id
                LEFT JOIN ArticleSeries ass ON att.as_id = ass.as_id
                order by att.at_updtime desc
                limit ". $limit['offset']. ", 25
            ");
        //echo $dbAdm->echoSQL();
        $dbAdm->execSQL();

        $aTitleList = $dbAdm->getAll();
        $articleArray = Array();
        $counter = 0;
        foreach($aTitleList as $aTitle) {

            //找出與at_id相同的文章，並取出按讚數
            $dbAdm->sqlSet("
                select pp.praiseAmount, a.`a_id`,a.`a_attr`,a.`a_level`,a.`at_id`, a.`a_author`, a.`a_mainCp`,a.`a_mainCp2`,a.`a_subCp`,a.`a_alert`,a.`m_id`,a.`g_sendMid`,a.`a_tag`,a.`a_aTitle`,a.`a_chapter`,a.`a_isShow`, a.`a_clickCount`, a.`a_updtime`, a.`a_crtime` 
                FROM `Article` a
                LEFT JOIN (
                    SELECT count( p.p_id ) praiseAmount, a.a_id
                    FROM Praise p, Article a
                    WHERE a.a_id = p.a_id
                    GROUP BY a.a_id
                )pp ON pp.a_id = a.a_id
                where a.at_id = ". $aTitle['at_id']."
                and a.a_isShow = 'Y'
                order by a.a_chapter desc 
                limit 0, 1
            ");
            $dbAdm->execSQL();
            $getGoodRes = $dbAdm->getAll();
            //$articleArray[$counter] = [];
            if(isset($getGoodRes[0])) 
                $articleArray[$counter] = $getGoodRes[0];
            else
                continue;
            if(isset($articleArray[$counter]['a_id'])) {
                $articleArray[$counter]['at'] = Array();
                $articleArray[$counter]['at']['at_id'] = $aTitle['at_id'];
                $articleArray[$counter]['at']['at_title'] = $aTitle['at_title'];
                $articleArray[$counter]['at']['as_name'] = $aTitle['as_name'];
                $articleArray[$counter]['at']['m_user'] = $aTitle['m_user'];
                $articleArray[$counter]['at']['at_lastCh'] = $aTitle['at_lastCh'];
                $articleArray[$counter]['at']['at_updtime'] = $aTitle['at_updtime'];
                $dbAdm->sqlSet("select count(b_id) amount from Bookmark where b_bookId = ". $articleArray[$counter]['a_id']);
                $dbAdm->execSQL();
                $articleArray[$counter]['at']['bookmarkCount'] = $dbAdm->getAll()[0]['amount'];
                $dbAdm->sqlSet("select count(ss_id) amount from SubScription where a_id = ". $articleArray[$counter]['a_id']);
                $dbAdm->execSQL();
                $articleArray[$counter]['at']['subscriptCount'] = $dbAdm->getAll()[0]['amount'];
                ++$counter;
            }
        }

        return $articleArray;
    }

    public function get($aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        //$dbAdm->selectData($tablename, $column, $conditionArr, null, null);
        $dbAdm->sqlSet("select count(p.m_id) praiseAmount, m.m_user,
                case when (att.at_lastCh = 0) then '?' 
                else att.at_lastCh end as at_lastCh, 
                a.*, att.at_title, att.at_lastCh, att.as_id asid,
                ss.as_name
                from `Article` a 
                inner join ArticleTitle att on a.at_id = att.at_id
                left join ArticleSeries ss on ss.as_id = att.as_id
                left join Praise p on p.a_id = a.a_id
                inner join Member m on m.m_id = a.m_id
            where a.a_id = ". $aid. " group by p.a_id");
        $dbAdm->execSQL();

        $data = $dbAdm->getAll()[0];
        $dbAdm->sqlSet("select count(b_id) amount from Bookmark where b_bookId = ". $aid);
        $dbAdm->execSQL();
        $data['bookmarkCount'] = $dbAdm->getAll()[0]['amount'];
        $dbAdm->sqlSet("select count(ss_id) amount from SubScription where a_id = ". $aid);
        $dbAdm->execSQL();
        $data['subscriptCount'] = $dbAdm->getAll()[0]['amount'];
        return $data;
    }

    public function listAmount() {
        $dbAdm = $this->dbAdm;
        $tablename = "ArticleTitle";

	$columns = Array();
	$columns[0] = "count(*) amount";

	$dbAdm->selectData($tablename, $columns);
	$dbAdm->execSQL();
	return $dbAdm->getAll()[0];
    }

    public function articleBySeries($para) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['as_id'] = $para['asid'];
        $conditionArr['m_id'] = $para['mid'];

        $order = Array();
        $order['col'] = "a_chapter";
        $order['order'] = "asc";

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 10;
        if(isset($para['nowPage']))
            $limit['offset'] = ($para['nowPage'] -1) * 10;

	$dbAdm->selectData($tablename, $columns, $conditionArr, $order, $limit);
	$dbAdm->execSQL();
	return $dbAdm->getAll();
    }

    public function allArticleBySeries($seriesId) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['as_id'] = $seriesId;

        $order = Array();
        $order['col'] = "a_chapter";
        $order['order'] = "asc";

	$dbAdm->selectData($tablename, $columns, $conditionArr, $order);
	$dbAdm->execSQL();
	return $dbAdm->getAll();
    }

    public function articlesByArtTitle($atid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "a_id";
	$columns[1] = "a_aTitle";

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;
        $conditionArr['a_isShow'] = "Y";

        $order = Array();
        $order['col'] = "a_chapter";
        $order['order'] = "asc";

	$dbAdm->selectData($tablename, $columns, $conditionArr, $order);
	$dbAdm->execSQL();
	return $dbAdm->getAll();
    }

    public function articleAmountBySeries($seriesId) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "count(*) as amount";

        $conditionArr = Array();
        $conditionArr['as_id'] = $seriesId;

        $order = Array();
        $order['col'] = "a_chapter";
        $order['order'] = "asc";

	$dbAdm->selectData($tablename, $columns, $conditionArr, $order);
	$dbAdm->execSQL();
	return $dbAdm->getAll()[0]['amount'];
    }

    public function articleAmountByMem($mid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "count(*) as amount";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

	$dbAdm->selectData($tablename, $columns, $conditionArr, $order);
	$dbAdm->execSQL();
	return $dbAdm->getAll()[0]['amount'];
    }

    public function changeCh($aid, $chapter) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $colData = Array();
        $colData['a_chapter'] = $chapter;

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        $dbAdm->updateData($tablename, $colData, $conditionArr);
	$dbAdm->execSQL();
    }

    public function deleteFromSeries($atid) {
        $dbAdm = $this->dbAdm;
        $tablename = "ArticleTitle";

        $colData = Array();
        $colData['as_id'] = 0;

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;

        $dbAdm->updateData($tablename, $colData, $conditionArr);
	$dbAdm->execSQL();
    }

    public function articleBySubscriptSeries($para) { 
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['as_id'] = $para['asid'];

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 25;
        if(isset($para['nowPage']))
            $limit['offset'] = ($para['nowPage'] -1) * 25;

	//$dbAdm->selectData($tablename, $columns, $conditionArr, null, $limit);
        $dbAdm->sqlSet("select a.*, att.at_title, att.at_lastCh from Article a inner join ArticleTitle att on att.at_id = a.at_id and att.as_id = ". $para['asid']. " order by a_chapter asc limit ". $limit['offset']. ", ". $limit['amount']);
	$dbAdm->execSQL();
	return $dbAdm->getAll();
    }

    public function search($nowPage, $conditionLike) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $startNum = ($nowPage -1) * 20;

        $sql = "select a.*, att.at_title, att.at_lastCh, att.at_updtime, m.m_user from Article a 
            inner join ArticleTitle att on att.at_id = a.at_id ";
        if(isset($conditionLike['title']))
            $sql .= " and att.at_title like '". $conditionLike['title']. "' ";

        if(isset($conditionLike['series'])) {
            $sql .= " inner join ArticleSeries ass on ass.as_id = att.as_id ";
            $sql .= " and ass.as_name in ('". $conditionLike['series']. "') ";
        }

        $sql .= " inner join Member m on m.m_id = a.m_id ";
        if(isset($conditionLike['member'])) {
            $sql .= " and m.m_user like '". $conditionLike['member']. "' ";
        }

        $sql .= " where a.a_isShow = 'Y' ";
        if(isset($conditionLike['author'])) {
            $authorArr = preg_split('/;/', $conditionLike['author']);
            //print_r($authorArr);
            $sql .= "and ( ";
            foreach($authorArr as $idx => $authorCondition) {
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_author like '%". $authorCondition. "%' ";
            }
            $sql .= " )";
        }
        if(isset($conditionLike['mainCp'])) {
            $mainCpArr = preg_split('/,/', $conditionLike['mainCp']);
            //print_r($mainCpArr);
            $sql .= "and ( ";
            foreach($mainCpArr as $idx => $cpCondition) {
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_mainCp like '%". $cpCondition. "%' or a.a_mainCp2 like '%". $cpCondition. "%' ";
                $sql .= " or a.a_subCp like '%". $cpCondition. "%' ";
            }
            $sql .= " )";
        }
        if(isset($conditionLike['nonMainCp'])) {
            $nonMainCpArr = preg_split('/,/', $conditionLike['nonMainCp']);
            //print_r($nonMainCpArr);
            $sql .= "and ( ";
            foreach($nonMainCpArr as $idx => $cpCondition) {
                if($idx > 0) $sql .= " and ";
                $sql .= " a.a_mainCp not like '%". $cpCondition. "%' and a.a_mainCp2 not like '%". $cpCondition. "%' ";
                $sql .= " and a.a_subCp not like '%". $cpCondition. "%' ";
            }
            $sql .= " )";
        }
        //if(isset($conditionLike['subCp']))
        //if(isset($conditionLike['nonSubCp']))
        if(isset($conditionLike['level']))
            $sql .= " and a.a_level in ('". $conditionLike['level']. "') ";
        if(isset($conditionLike['alert'])) {
            $alertArr = preg_split('/;/', $conditionLike['alert']);
            $sql .= "and ( ";
            //print_r($alertArr);
            foreach($alertArr as $idx => $alertStr) {
                //print_r(gettype($alertStr));
                //print_r(strlen($alertStr)."\n");
                if(trim($alertStr) == "") continue;
                //if(strlen($alertStr) == 0) continue;
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_alert like '%$alertStr%' ";
            }
            $sql .= " )";
        }
        if(isset($conditionLike['tag'])) {
            $tagArr = preg_split('/;/', $conditionLike['tag']);
            $sql .= "and ( ";
            foreach($tagArr as $idx => $tagStr) {
                if(trim($tagStr) == "") continue;
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_tag like '%$tagStr%' ";
            }
            $sql .= " )";
        }
        $sql .= " limit $startNum, 20";
        //echo $sql;
        $dbAdm->sqlSet($sql);
	$dbAdm->execSQL();
	return $dbAdm->getAll();
    }

    public function searchAmount($conditionLike) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $sql = "select count(a.a_id) amount from Article a 
            inner join ArticleTitle att on att.at_id = a.at_id ";
        if(isset($conditionLike['title']))
            $sql .= " and att.at_title like '". $conditionLike['title']. "' ";

        if(isset($conditionLike['series'])) {
            $sql .= " inner join ArticleSeries ass on ass.as_id = att.as_id ";
            $sql .= " and ass.as_name in ('". $conditionLike['series']. "') ";
        }

        $sql .= " inner join Member m on m.m_id = a.m_id ";
        if(isset($conditionLike['member'])) {
            $sql .= " and m.m_user like '". $conditionLike['member']. "' ";
        }

        $sql .= " where a.a_isShow = 'Y' ";
        if(isset($conditionLike['author'])) {
            $authorArr = preg_split('/;/', $conditionLike['author']);
            //print_r($authorArr);
            $sql .= "and ( ";
            foreach($authorArr as $idx => $authorCondition) {
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_author like '%". $authorCondition. "%' ";
            }
            $sql .= " )";
        }
        if(isset($conditionLike['mainCp'])) {
            $mainCpArr = preg_split('/,/', $conditionLike['mainCp']);
            //print_r($mainCpArr);
            $sql .= "and ( ";
            foreach($mainCpArr as $idx => $cpCondition) {
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_mainCp like '%". $cpCondition. "%' or a.a_mainCp2 like '%". $cpCondition. "%' ";
                $sql .= " or a.a_subCp like '%". $cpCondition. "%' ";
            }
            $sql .= " )";
        }
        if(isset($conditionLike['nonMainCp'])) {
            $nonMainCpArr = preg_split('/,/', $conditionLike['nonMainCp']);
            $sql .= "and ( ";
            foreach($nonMainCpArr as $idx => $cpCondition) {
                if($idx > 0) $sql .= " and ";
                $sql .= " a.a_mainCp not like '%". $cpCondition. "%' and a.a_mainCp2 not like '%". $cpCondition. "%' ";
                $sql .= " and a.a_subCp not like '%". $cpCondition. "%' ";
            }
            $sql .= " )";
        }
        /*
        if(isset($conditionLike['subCp']))
            $sql .= " and a.a_subCp like '". $conditionLike['subCp']. "' ";
        if(isset($conditionLike['nonSubCp']))
            $sql .= " and a.a_subCp not like '". $conditionLike['nonSubCp']. "' ";
         */
        if(isset($conditionLike['level']))
            $sql .= " and a.a_level in ('". $conditionLike['level']. "') ";
        if(isset($conditionLike['alert'])) {
            $alertArr = preg_split('/;/', $conditionLike['alert']);
            $sql .= "and ( ";
            foreach($alertArr as $idx => $alertStr) {
                if(trim($alertStr) == "") continue;
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_alert like '%$alertStr%' ";
            }
            $sql .= " )";
        }
        if(isset($conditionLike['tag'])) {
            $tagArr = preg_split('/;/', $conditionLike['tag']);
            $sql .= "and ( ";
            foreach($tagArr as $idx => $tagStr) {
                if(trim($tagStr) == "") continue;
                if($idx > 0) $sql .= " or ";
                $sql .= " a.a_tag like '%$tagStr%' ";
            }
            $sql .= " )";
        }
        //echo $sql;
        $dbAdm->sqlSet($sql);
	$dbAdm->execSQL();
	return $dbAdm->getAll()[0]['amount'];
    }

    public function clicked($aid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;
        $dbAdm->sqlSet("update $tablename set a_clickCount = a_clickCount+1 where a_id = $aid");
	$dbAdm->execSQL();
    }

    public function myGiftList($mid, $nowPage) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $limit = Array();
        $limit['offset'] = ($nowPage -1) * 25;
        $limit['amount'] = 25;

        $dbAdm->sqlSet("select a.*, att.at_title, m.m_user from Article a 
            inner join ArticleTitle att on a.at_id = att.at_id 
            inner join Member m on m.m_id = a.m_id 
            where a.g_sendMid = $mid limit ". $limit['offset']. ", ". $limit['amount']);

	$dbAdm->execSQL();
        return $dbAdm->getAll();
    }
    
    public function myGiftListAmount($mid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $dbAdm->sqlSet("select count(a.a_id) as amount from Article a inner join ArticleTitle att on a.at_id = att.at_id where a.g_sendMid = $mid");

	$dbAdm->execSQL();
        return $dbAdm->getAll()[0]['amount'];
    }

    public function myRecentGiftAmount($mid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;
        $recentDate = date("Y-m-d H:i:s", 
            strtotime(date("Y-m-d H:i:s"). '-3 day'));
        $recentAmount = 0;

        $dbAdm->sqlSet("select a.* from Article a where a.g_sendMid = $mid");
	$dbAdm->execSQL();
        $articles = $dbAdm->getAll();
        foreach($articles as $art) 
            if($art['a_crtime'] > $recentDate)
                ++$recentAmount;

        return $recentAmount;
    }
}
