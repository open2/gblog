<?
include_once("./_common.php");
include_once("$gb4[bbs_path]/_header.php");

// mb_id �� ������ ������.
// mb_id �� ��α� �ĺ����̱⶧���� �ݵ��� �����ؾ� �Ѵ�.
if( empty($mb_id) )
    die("109");

// $m (mode�� ����) ���� ���� ��ƾ�� �ٸ���.
switch( $m ) {

    case 'delete':

        // ����� �˻��Ѵ�.
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='{$id}'");

        // ����� ������ ������.
        if( empty($res) ) die('101');

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
        if ($re==1)
            sql_query("update {$gb4['guestbook_table']} set answer_content='' where id='{$id}'");
        else
            sql_query("delete from {$gb4['guestbook_table']} where id='{$id}'");

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
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='$id'");

        // ���� ����� ���
        if( empty($res) ) die('101');

        // ����� �´��� �˻�
        if( sql_password($input_pw)==$res['writer_pw'] ) {

            // ����
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
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='{$id}'");

        // ����� ������ ������.
        if( empty($res) ) die('106');

        if ($re==1) {
            if ($member['mb_id']!=$current['mb_id']) die("110");

            // �����۵�
            die("000,$id,1");

        } else {

            // �����ڰ� ȸ���� ���
            if( !empty($member['mb_id']) ) {

                // ������ ��α��� ��� ��������
                if( $member['mb_id'] == $current['mb_id'] )
                    die("000,$id");

                // �ۼ��ڿ� �����ڰ� �ٸ���� ����
                elseif( $member['mb_id'] != $res['mb_id'] )
                    die("101,$id");

                // ���� ����� ����� ��ϵǾ��� ��� �����Ұ�
                if( !empty($res['answer_content']) )
                    die("102,$id");

                // ���� ��� ���� ����
                die("000,$id");

            // �����ڰ� ȸ���� �ƴҰ��
            } else {

                // ȸ���� �ۼ��� ���̸� ����
                if( !empty($res['mb_id']) )
                    die("101,$id");

                // ���� ����� ����� ��ϵǾ��� ��� �����Ұ�
                if( !empty($res['answer_content']) )
                    die("102,$id");

                // �����۵�
                die("001,$id");
            }
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
        $res = sql_fetch("select * from {$gb4['guestbook_table']} where id='{$id}'");

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

        if($re==1) {
            $sql = "update
                        {$gb4['guestbook_table']}
                    set
                        answer_content = '{$writer_content}'
                    where
                        id='{$id}'";
        } else {
            // DB �� ���� update
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

        /* ���� ���� ���� */
        die("000");

    // ��۵� �޾ƻӴ�
    default:

        // ����� �޾Ƶ� �Ǵ��� �����.
        //$res = sql_fetch("select * from {$gb4['post_table']} where id='{$post_id}'");
        //if( !$res['use_comment'] || !$current['use_comment'] ) die("108");

        // ���� ��� ��� ���� ����
        if ($re&&$member['mb_id']!=$current['mb_id']) 
            die("110");

        // ȸ���� ���
        if( !empty($member['mb_id']) ) {

            if( empty($member['writer']) )
                $writer_name = $member['mb_nick'];
            else
                $writer_name = $member['writer'];

            $writer_email   = $member['mb_email'];
            $writer_url     = $member['blog_url'];

        // ȸ���� �ƴ� ���
        } else {

            // ����� �ı��� �� ���� �°��� �����غ���. ������ ������.
            if( !trim($writer_name)     )   die("101");
            //if( !trim($writer_email)    )   die("102");
            if( !trim($writer_pw)       )   die("103");
            if( !trim($wr_key)    )   die("105");

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
                die("106");
            }
        }

        if ($guestbook_id) {

            $sql = "update {$gb4['guestbook_table']} set answer_content='{$writer_content}', ansdate='{$g4['time_ymdhis']}' where id='{$guestbook_id}'";

        } else {

            // DB �� ���� �ִ´�.
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

        /* ���� ���� ���� */
        die("000");

} // end select

?>