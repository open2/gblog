<?
include_once("./_common.php");
include_once("./_header.php");

// mb_id �� ������ ������.
// mb_id �� ��α� �ĺ����̱⶧���� �ݵ��� �����ؾ� �Ѵ�.
if( empty($mb_id) ) die('109');

// $m (mode�� ����) ���� ���� ��ƾ�� �ٸ���.
switch( $m ) {

    case 'delete':

        // ����� �˻��Ѵ�.
        $res = sql_fetch("select * from {$gb4['comment_table']} where id='{$comment_id}'");

        // ����� ������ ������.
        if( empty($res) ) die('101');

        $post_id = $res['post_id'];

        // ������ ��αװ� �ƴҰ��
        if( $member['mb_id'] != $current['mb_id'] )
        {
            // ȸ���� ���
            if( !empty($member['mb_id']) ) {

                // ������ ����� �ƴϸ� ������.
                if( $res['mb_id'] != $member['mb_id'] ) die('102');

            // ȸ���� �ƴҰ�� ��й�ȣ �˻�
            } else {

                // ��й�ȣ�� Ʋ���� ������.
                if( sql_password($writer_pw) != $res['writer_pw'] ) die('103');
            }
        }

        // DB �� ���� delete
        $sql = "delete from {$gb4['comment_table']} where id='{$comment_id}'";

        sql_query($sql);

        // �� ���� ���̺� ��� ī��Ʈ�� ���ҽ�Ų��.
        $sql = "update {$gb4['post_table']} set comment_count = comment_count - 1 where blog_id='{$current['id']}' and id='{$post_id}'";
        sql_query($sql);

        // ��α� ���� ���̺� ��� ī��Ʈ�� ���ҽ�Ų��.
        $sql = "update {$gb4['blog_table']} set comment_count = comment_count - 1 where id='{$current['id']}'";
        sql_query($sql);

        /* ���� ���� ���� */
        die("000");


    // ��й�ȣ�� �´��� �˻�
    case 'password':

        // �Ѿ�� ��й�ȣ �˻�
        if( strlen(trim($input_pw)) < 1 ) {
            header("Content-Type:text/xml;");
            echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
            echo "<items>\n";
            echo "<errnum>109</errnum>\n";
            echo "</items>";
            exit;
        }

        // ��� ���� �ε�
        $res = sql_fetch("select * from {$gb4['comment_table']} where id='$comment_id'");

        // ���� ����� ���
        if( empty($res) ) die('101');

        // ����� �´��� �˻�
        if( sql_password($input_pw)==$res['writer_pw'] ) {

            // ����
            die("000,$comment_id,{$res['writer_name']},{$res['writer_email']},{$res['writer_url']},$input_pw,{$res['writer_content']}");

/*
            header("Content-Type:text/xml;");
            echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
            echo "<items>\n";
            echo "<errnum>000</errnum>\n";
            echo "<id>{$comment_id}</id>\n";
            echo "<name>{$res['writer_name']}</name>\n";
            echo "<email>{$res['writer_email']}</email>\n";
            echo "<url>{$res['writer_url']}</url>\n";
            echo "<pw>{$input_pw}</pw>\n";
            echo "<content>{$res['writer_content']}</content>\n";
            echo "</items>";
            exit;
*/

        } else {

            // Ʋ��
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

    // ����� ������ ȸ������ �˻�
    case 'permission':

        // ����� �˻��Ѵ�.
        $res = sql_fetch("select * from {$gb4['comment_table']} where id='{$comment_id}'");

        // ����� ������ ������.
        if( empty($res) ) die('106');

        // �����ڰ� ȸ���� ���
        if( !empty($member['mb_id']) ) {

            // ������ ��α��� ��� ��������
            if( $member['mb_id'] == $current['mb_id'] )
                die("000,$comment_id");

            // �ۼ��ڿ� �����ڰ� �ٸ���� ����
            elseif( $member['mb_id'] != $res['mb_id'] )
                die("101,$comment_id");

            // ���� ����� ����� ��ϵǾ��� ��� �����Ұ�
            $r = sql_fetch("select * from {$gb4['comment_table']} where post_id='{$res['post_id']}' and comment_num='{$res['comment_num']}' and comment_re_num>{$res['comment_re_num']}");
            if( !empty($r) )
                die("102,$comment_id");

            // ���� ��� ���� ����
            die("000,$comment_id");

        // �����ڰ� ȸ���� �ƴҰ��
        } else {

            // ȸ���� �ۼ��� ���̸� ����
            if( !empty($res['mb_id']) )
                die("101,$comment_id");

            // ���� ����� ����� ��ϵǾ��� ��� �����Ұ�
            $r = sql_fetch("select * from {$gb4['comment_table']} where post_id='{$res['post_id']}' and comment_num='{$res['comment_num']}' and comment_re_num>{$res['comment_re_num']}");
            if( !empty($r) )
                die("102,$comment_id");

            // �����۵�
            die("001,$comment_id");
        }

    // ��۸� �����Ѵ�.
    case 'update':

        // ȸ���� ���
        if( !empty($member['mb_id']) ) {

            if( empty($member['writer']) )
                $writer_name = $member['mb_nick'];
            else
                $writer_name = $member['writer'];

            $writer_email   = $member['mb_email'];
            
            if($member['mb_homepage'])
                $writer_url     = $member['mb_homepage'];
            else
                $writer_url     = $member['blog_url'];

        // ȸ���� �ƴ� ���
        } else {

            // ����� �ı��� �� ���� �°��� �����غ���. ������ ������.
            if( !trim($writer_name)     )   die("101");
            //if( !trim($writer_email)    )   die("102");
            if( !trim($writer_pw)       )   die("103");

            // ajax �� post �� ���� �ѱ涧 utf-8 �� �Ѱ��ֱ⶧���� CP949 �� �����ؾ� �Ѵ�.
            if( strtoupper($g4['charset']) != 'UTF-8' ) {
                $writer_name    = convert_charset('UTF-8','CP949',$writer_name);
                $writer_email   = convert_charset('UTF-8','CP949',$writer_email);
                $writer_pw      = convert_charset('UTF-8','CP949',$writer_pw);
            }
        }

        // ��� ������ �����Ѵ�. ������ ������.
        if( !trim($writer_content)  ) {
            die("104");
        } else {
            if( strtoupper($g4['charset']) != 'UTF-8' ) 
                $writer_content = convert_charset('UTF-8','CP949',$writer_content);
        }

        // ��ۿ� html �̳� ��ũ��Ʈ�� �ȵǿ�^^
        $writer_name    = htmlspecialchars($writer_name);
        $writer_email   = htmlspecialchars($writer_email);
        $writer_url     = htmlspecialchars($writer_url);
        $writer_content = htmlspecialchars($writer_content);

        // 'http://' ����
        $writer_url     = str_replace('http://', '', $writer_url);

        // ��� �ۼ����� �����Ǹ� �˾Ƴ���.
        $writer_ip = $REMOTE_ADDR;

        // ����� �˻��Ѵ�.
        $res = sql_fetch("select * from {$gb4['comment_table']} where id='{$comment_id}'");

        // ����� ������ ������.
        if( empty($res) ) die('105');

        // ������ ��α� �ϰ�� ����� ������ ��ȯ���� �ʴ´�.
        if( $member['mb_id'] == $current['mb_id'] ) {

            $writer_name    = $res['writer_name'];
            $writer_email   = $res['writer_email'];
            $writer_url     = $res['writer_url'];
            $writer_ip      = $res['writer_ip'];
        }

        // ȸ���� ���
        elseif( !empty($member['mb_id']) )

            // ������ ����� �ƴϸ� ������.
            if( $res['mb_id'] != $member['mb_id'] ) die('107');

        // ȸ���� �ƴҰ�� ��й�ȣ �˻�
        else
            // ��й�ȣ�� Ʋ���� ������.
            if( sql_password($writer_pw) != $res['writer_pw'] ) die('106');

        // DB �� ���� update
        $sql = "update
                    {$gb4['comment_table']}
                set
                    writer_name = '{$writer_name}'
                    ,writer_email = '{$writer_email}'
                    ,writer_url = '{$writer_url}'
                    ,writer_content = '{$writer_content}'
                    ,writer_ip = '{$writer_ip}'
                    ,moddate = '{$g4['time_ymdhis']}'
                where
                    id='{$comment_id}'";
        sql_query($sql);

        /* ���� ���� ���� */
        die("000");

    // ��۵� �޾ƻӴ�
    //case 'new':
    default:

        // ����� �޾Ƶ� �Ǵ��� �����.
        $res = sql_fetch("select * from {$gb4['post_table']} where id='{$post_id}'");
        if( !$res['use_comment'] || !$current['use_comment'] ) die("108");

        // ȸ���� ���
        if( !empty($member['mb_id']) ) {

            if( empty($member['writer']) )
                $writer_name = $member['mb_nick'];
            else
                $writer_name = $member['writer'];

            $writer_email   = $member['mb_email'];

            if($member['mb_homepage'])
                $writer_url     = $member['mb_homepage'];
            else
                $writer_url     = $member['blog_url'];

        // ȸ���� �ƴ� ���
        } else {

            // ����� �ı��� �� ���� �°��� �����غ���. ������ ������.
            if( !trim($writer_name)     )   die("101");
            //if( !trim($writer_email)    )   die("102");
            if( !trim($writer_pw)       )   die("103");
            if( !trim($wr_key)    )         die("105");

            // ajax �� post �� ���� �ѱ涧 utf-8 �� �Ѱ��ֱ⶧���� CP949 �� �����ؾ� �Ѵ�.
            if( strtoupper($g4['charset']) != 'UTF-8' ) {
                $writer_name    = convert_charset('UTF-8','CP949',$writer_name);
                $writer_email   = convert_charset('UTF-8','CP949',$writer_email);
                $writer_pw      = convert_charset('UTF-8','CP949',$writer_pw);
            }

            // ��й�ȣ 4�� �̻� �Է�
            if( strlen(trim($writer_pw)) < 4 )  die("107");

        }

        // ��� ������ �����Ѵ�. ������ ������.
        if( !trim($writer_content)  ) {
            die("104");
        } else {
            if( strtoupper($g4['charset']) != 'UTF-8' ) 
                $writer_content = convert_charset('UTF-8','CP949',$writer_content);
        }

        // ��ۿ� html �̳� ��ũ��Ʈ�� �ȵǿ�^^
        $writer_name    = htmlspecialchars($writer_name);
        $writer_email   = htmlspecialchars($writer_email);
        $writer_url     = htmlspecialchars($writer_url);
        $writer_content = htmlspecialchars($writer_content);

        // 'http://' ����
        $writer_url     = str_replace('http://', '', $writer_url);

        // ��� �ۼ����� �����Ǹ� �˾Ƴ���.
        $writer_ip = $REMOTE_ADDR;

        // �ڵ���Ϲ��� �˻� (4.30.00 ������ captcha)
        if (!$is_member) {
            $key = get_session("captcha_keystring");
            if (!($key && $key == $_POST[wr_key])) {
                session_unregister("captcha_keystring");
                die("105");
            }
        }

        // �׳� ����̸�  (1�� ���)
        if( empty($comment_num) ) {

            $res = sql_fetch("select max(comment_num) as comment_num from {$gb4['comment_table']} where post_id='{$post_id}'");
            $comment_num = $res['comment_num'] + 1;

        // ����� ����̸� (2�� ���) $comment_re_num ���� �ִ´�.
        } else {

            $res = sql_fetch("select max(comment_re_num) as comment_re_num from {$gb4['comment_table']} where post_id='{$post_id}' and comment_num='{$comment_num}'");
            $comment_re_num = $res['comment_re_num'] + 1;
        }

        // DB �� ���� �ִ´�.
        $sql = "insert into
                    {$gb4['comment_table']}
                set
                    blog_id = '{$current['id']}'
                    ,post_id = '{$post_id}'
                    ,comment_num = '{$comment_num}'
                    ,comment_re_num = '{$comment_re_num}'
                    ,mb_id = '{$member['mb_id']}'
                    ,secret = '{$secret}'
                    ,writer_name = '{$writer_name}'
                    ,writer_pw = password('{$writer_pw}')
                    ,writer_email = '{$writer_email}'
                    ,writer_url = '{$writer_url}'
                    ,writer_content = '{$writer_content}'
                    ,writer_ip = '{$writer_ip}'
                    ,moddate = '{$g4['time_ymdhis']}'
                    ,regdate = '{$g4['time_ymdhis']}'";
        sql_query($sql);

        // �� ���� ���̺� ��� ī��Ʈ�� ������Ų��.
        $sql = "update {$gb4['post_table']} set comment_count = comment_count + 1 where blog_id='{$current['id']}' and id='{$post_id}'";
        sql_query($sql);

        // ��α� ���� ���̺� ��� ī��Ʈ�� ������Ų��.
        $sql = "update {$gb4['blog_table']} set comment_count = comment_count + 1 where id='{$current['id']}'";
        sql_query($sql);

        /* ���� ���� ���� */
        die("000");

} // end select



?>