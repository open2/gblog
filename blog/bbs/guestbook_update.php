<?
include_once("./_common.php");
include_once("$gb4[bbs_path]/_header.php");

// mb_id 가 없으면 끝낸다.
// mb_id 는 블로그 식별자이기때문에 반듯이 존재해야 한다.
if( empty($mb_id) )
    die("109");

// $m (mode의 약자) 별로 실행 루틴이 다르다.
switch( $m ) {

    case 'delete':

        // 댓글을 검사한다.
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='{$id}'");

        // 댓글이 없으면 끝낸다.
        if( empty($res) ) die('101');

        // 본인의 블로그가 아닐경우
        if( $member['mb_id'] != $current['mb_id'] )
        {
            // 회원일 경우
            if( !empty($member['mb_id']) ) {

                // 본인의 댓글이 아니면 끝낸다.
                if( $res['mb_id'] != $member['mb_id'] ) die('102');

            // 회원이 아닐경우 비밀번호 검사
            } else {

                // 비밀번호가 틀리면 끝낸다.
                if( sql_password($writer_pw) != $res['writer_pw'] ) die('103');
            }
        }

        // DB 에 값을 delete
        if ($re==1)
            sql_query("update {$gb4['guestbook_table']} set answer_content='' where id='{$id}'");
        else
            sql_query("delete from {$gb4['guestbook_table']} where id='{$id}'");

        /* 성공 값을 리턴 */
        die("000");


    // 비밀번호가 맞는지 검사
    case 'password':

        // 넘어온 비밀번호 검사
        if( strlen(trim($input_pw)) < 1 ) {
            header("Content-Type:text/xml;");
            echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
            echo "<items>\n";
            echo "<errnum>109</errnum>\n";
            echo "</items>";
            exit;
        }

        // 댓글 정보 로드
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='$id'");

        // 없는 댓글일 경우
        if( empty($res) ) die('101');

        // 댓글이 맞는지 검사
        if( sql_password($input_pw)==$res['writer_pw'] ) {

            // 맞음
            die("000,$id,{$res['writer_name']},{$res['writer_email']},{$res['writer_url']},$input_pw,{$res['writer_content']}");
            /*
            header("Content-Type:text/xml;");
            echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
            echo "<items>\n";
            echo "<errnum>000</errnum>\n";
            echo "<id>{$id}</id>\n";
            echo "<name>{$res['writer_name']}</name>\n";
            echo "<email>{$res['writer_email']}</email>\n";
            echo "<url>{$res['writer_url']}</url>\n";
            echo "<pw>{$input_pw}</pw>\n";
            echo "<content>{$res['writer_content']}</content>\n";
            echo "</items>";
            exit;
            */
            
        } else {

            // 틀림             
            die('102');
            /*
            header("Content-Type:text/xml;");
            echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
            echo "<items>\n";
            echo "<errnum>102</errnum>\n";
            echo "</items>";
            exit;
            */
        }



    // 댓글의 주인이 회원인지 검사
    case 'permission':

        // 댓글을 검사한다.
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='{$id}'");

        // 댓글이 없으면 끝낸다.
        if( empty($res) ) die('106');

        if ($re==1) {
            if ($member['mb_id']!=$current['mb_id']) die("110");

            // 정상작동
            die("000,$id,1");

        } else {

            // 수정자가 회원일 경우
            if( !empty($member['mb_id']) ) {

                // 본인의 블로그일 경우 수정가능
                if( $member['mb_id'] == $current['mb_id'] )
                    die("000,$id");

                // 작성자와 수정자가 다를경우 에러
                elseif( $member['mb_id'] != $res['mb_id'] )
                    die("101,$id");

                // 현재 댓글의 댓글이 등록되었을 경우 수정불가
                if( !empty($res['answer_content']) )
                    die("102,$id");

                // 본인 댓글 수정 가능
                die("000,$id");

            // 수정자가 회원이 아닐경우
            } else {

                // 회원이 작성한 글이면 에러
                if( !empty($res['mb_id']) )
                    die("101,$id");

                // 현재 댓글의 댓글이 등록되었을 경우 수정불가
                if( !empty($res['answer_content']) )
                    die("102,$id");

                // 정상작동
                die("001,$id");
            }
        }

    // 댓글를 수정한다.
    case 'update':

        // 회원일 경우
        if( !empty($member['mb_id']) ) {

            if( empty($member['writer']) )
                $writer_name = $member['mb_nick'];
            else
                $writer_name = $member['writer'];

            $writer_email   = $member['mb_email'];
            $writer_url     = $member['blog_url'];

        // 회원이 아닐 경우
        } else {

            // 댓글이 식구를 잘 끌고 온건지 조사해본다. 없으면 끝낸다.
            if( !trim($writer_name)     )   die("101");
            //if( !trim($writer_email)    )   die("102");
            if( !trim($writer_pw)       )   die("103");

            // ajax 는 post 로 값을 넘길때 utf-8 로 넘겨주기때문에 CP949 로 변경해야 한다.
            if( strtoupper($g4['charset']) != 'UTF-8' ) {
                $writer_name    = convert_charset('UTF-8','CP949',$writer_name);
                $writer_email   = convert_charset('UTF-8','CP949',$writer_email);
                $writer_pw      = convert_charset('UTF-8','CP949',$writer_pw);
            }
        }

        // 댓글 내용을 저장한다. 없으면 끝낸다.
        if( !trim($writer_content)  ) {
            die("104");
        } else {
            if( strtoupper($g4['charset']) != 'UTF-8' ) 
                $writer_content = convert_charset('UTF-8','CP949',$writer_content);
        }

        // 댓글에 html 이나 스크립트는 안되요^^
        $writer_name    = htmlspecialchars($writer_name);
        $writer_email   = htmlspecialchars($writer_email);
        $writer_url     = htmlspecialchars($writer_url);
        $writer_content = htmlspecialchars($writer_content);

        // 'http://' 제거
        $writer_url     = str_replace('http://', '', $writer_url);

        // 댓글 작성자의 아이피를 알아낸다.
        $writer_ip = $REMOTE_ADDR;

        // 댓글을 검사한다.
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='{$id}'");

        // 댓글이 없으면 끝낸다.
        if( empty($res) ) die('105');

        // 본인의 블로그 일경우 사용자 정보는 변환하지 않는다.
        if( $member['mb_id'] == $current['mb_id'] ) {

            $writer_name    = $res['writer_name'];
            $writer_email   = $res['writer_email'];
            $writer_url     = $res['writer_url'];
            $writer_ip      = $res['writer_ip'];
        }

        // 회원일 경우
        elseif( !empty($member['mb_id']) )

            // 본인의 댓글이 아니면 끝낸다.
            if( $res['mb_id'] != $member['mb_id'] ) die('107');

        // 회원이 아닐경우 비밀번호 검사
        else
            // 비밀번호가 틀리면 끝낸다.
            if( sql_password($writer_pw) != $res['writer_pw'] ) die('106');

        if($re==1) {
            $sql = "update
                        {$gb4['guestbook_table']}
                    set
                        answer_content = '{$writer_content}'
                    where
                        id='{$id}'";
        } else {
            // DB 에 값을 update
            $sql = "update
                        {$gb4['guestbook_table']}
                    set
                        writer_name = '{$writer_name}'
                        ,writer_email = '{$writer_email}'
                        ,writer_url = '{$writer_url}'
                        ,writer_content = '{$writer_content}'
                        ,writer_ip = '{$writer_ip}'
                    where
                        id='{$id}'";
        }
        sql_query($sql);

        /* 성공 값을 리턴 */
        die("000");

    // 댓글들 달아뿐다
    default:

        // 댓글을 달아도 되는지 물어본다.
        //$res = sql_fetch("select * from {$gb4['post_table']} where id='{$post_id}'");
        //if( !$res['use_comment'] || !$current['use_comment'] ) die("108");

        // 방명록 댓글 등록 가능 여부
        if ($re&&$member['mb_id']!=$current['mb_id']) 
            die("110");

        // 회원일 경우
        if( !empty($member['mb_id']) ) {

            if( empty($member['writer']) )
                $writer_name = $member['mb_nick'];
            else
                $writer_name = $member['writer'];

            $writer_email   = $member['mb_email'];
            $writer_url     = $member['blog_url'];

        // 회원이 아닐 경우
        } else {

            // 댓글이 식구를 잘 끌고 온건지 조사해본다. 없으면 끝낸다.
            if( !trim($writer_name)     )   die("101");
            //if( !trim($writer_email)    )   die("102");
            if( !trim($writer_pw)       )   die("103");
            if( !trim($wr_key)    )   die("105");

            // ajax 는 post 로 값을 넘길때 utf-8 로 넘겨주기때문에 CP949 로 변경해야 한다.
            if( strtoupper($g4['charset']) != 'UTF-8' ) {
                $writer_name    = convert_charset('UTF-8','CP949',$writer_name);
                $writer_email   = convert_charset('UTF-8','CP949',$writer_email);
                $writer_pw      = convert_charset('UTF-8','CP949',$writer_pw);
            }

            // 비밀번호 4자 이상 입력
            if( strlen(trim($writer_pw)) < 4 )  die("107");

        }

        // 댓글 내용을 저장한다. 없으면 끝낸다.
        if( !trim($writer_content)  ) {
            die("104");
        } else {
            if( strtoupper($g4['charset']) != 'UTF-8' ) 
                $writer_content = convert_charset('UTF-8','CP949',$writer_content);
        }

        // 댓글에 html 이나 스크립트는 안되요^^
        $writer_name    = htmlspecialchars($writer_name);
        $writer_email   = htmlspecialchars($writer_email);
        $writer_url     = htmlspecialchars($writer_url);
        $writer_content = htmlspecialchars($writer_content);

        // 'http://' 제거
        $writer_url     = str_replace('http://', '', $writer_url);

        // 댓글 작성자의 아이피를 알아낸다.
        $writer_ip = $REMOTE_ADDR;

        // 자동등록방지 검사 (4.30.00 이후의 captcha)
        if (!$is_member) {
            $key = get_session("captcha_keystring");
            if (!($key && $key == $_POST[wr_key])) {
                session_unregister("captcha_keystring");
                die("106");
            }
        }

        if ($guestbook_id) {

            $sql = "update {$gb4['guestbook_table']} set answer_content='{$writer_content}', ansdate='{$g4['time_ymdhis']}' where id='{$guestbook_id}'";

        } else {

            // DB 에 값을 넣는다.
            $sql = "insert into
                        {$gb4['guestbook_table']}
                    set
                        blog_id = '{$current['id']}'
                        ,mb_id = '{$member['mb_id']}'
                        ,secret = '{$secret}'
                        ,writer_name = '{$writer_name}'
                        ,writer_pw = password('{$writer_pw}')
                        ,writer_email = '{$writer_email}'
                        ,writer_url = '{$writer_url}'
                        ,writer_content = '{$writer_content}'
                        ,writer_ip = '{$writer_ip}'
                        ,regdate = '{$g4['time_ymdhis']}'";
        }

        sql_query($sql);

        /* 성공 값을 리턴 */
        die("000");

} // end select

?>