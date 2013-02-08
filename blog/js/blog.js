/*---------------------------------------------------
    엮인글 보이기-안보이기 처리 함수
---------------------------------------------------*/
function trackback_open_close(id) {

    var eid = $('#trackback'+id);
    
    eid.toggle();
}

/*---------------------------------------------------
    글 수정 버튼
---------------------------------------------------*/
function post_mod(id) {
    location.href = gb4_blog+'/adm_write.php?m=u&mb_id='+member_mb_id+'&id='+id;
}

/*---------------------------------------------------
    글 삭제 버튼
---------------------------------------------------*/
function post_del(id) {
    if( confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?') )
        location.href = gb4_blog+'/adm_delete.php?mb_id='+member_mb_id+'&id='+id;
}

/*---------------------------------------------------
    방명록
---------------------------------------------------*/
function send_guestbook() {

    var send = 'mb_id=' + mb_id;

    if( !member_mb_id ) { 
        var writer_name     = $('#writer_name').val();
        var writer_email    = $('#writer_email').val();
        var writer_url      = $('#writer_url').val();
        var writer_pw       = $('#writer_pw').val();
        var wr_key          = $('#wr_key').val();

        send += '&writer_name='     + encodeURIComponent(writer_name);
        send += '&writer_email='    + encodeURIComponent(writer_email);
        send += '&writer_url='      + encodeURIComponent(writer_url);
        send += '&writer_pw='       + encodeURIComponent(writer_pw);
        send += '&wr_key='          + encodeURIComponent(wr_key);
    }

    writer_content  = encodeURIComponent($('#writer_content').val());

    if( $('#secret').attr('checked') == true ) secret = 1; else secret = 0;

    send += '&secret=' + secret;
    send += '&writer_content=' + writer_content;
    send += '&guestbook_id=' + $('#guestbook_id').val();

    url = gb4_blog+'/guestbook_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            switch( result ) {
                case '101': alert('이름은 필수 입력입니다.'); break;
                case '102': alert('이메일은 필수 입력입니다.'); break;
                case '103': alert('비밀번호는 필수 입력입니다.'); break;
                case '104': alert('댓글 내용은 필수 입력입니다.'); break;
                case '105': alert('자동등록방지 코드를 입력해주세요.'); break;
                case '106': alert('자동등록방지 코드가 잘못되었습니다.'); break;
                case '107': alert('비밀번호를 4자 이상 입력해주세요.'); break;
                case '108': alert('댓글을 허락하지 않았습니다.'); break;
                case '109': alert('잘못된 접근입니다.'); break;
                case '110': alert('블로그 운영자만 답글을 등록할 수 있습니다.'); break;
                case '000': guestbook_on(1); break;
                default:
                    alert('잘못된 접근입니다.\n\n'+result); break;
                } 
            }
        });
}


/* ajax 로 방명록 불러 오는 함수 */
function guestbook_on(p) 
{
    if (p) page = p;

    url = gb4_blog+'/guestbook_list.php';

    send = 'mb_id=' + mb_id;
    send += '&page=' + page;

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
            $("#main").html(result);
            //guestbook_cookie();
            }

        });
}

/*---------------------------------------------------
    방명록 수정/삭제 권한 검사
---------------------------------------------------*/
function guestbook_permission(id, action, re) 
{
    if (!re) re = 0;
    flag  = action;

    url = gb4_blog+'/guestbook_update.php';

    send  = 'mb_id=' + mb_id;
    send += '&id=' + id;
    send += '&action=' + action;
    send += '&re=' + re;
    send += '&m=permission';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            result      = result.split(',');
            msg_num     = result[0];
            id          = result[1];
            re          = result[2];

            switch( msg_num ) {
                case '101': alert('수정/삭제 할 권한이 없습니다.'); break;
                case '102': alert('글의 댓글이 등록되어 있으면 수정/삭제가 불가능합니다.'); break;
                case '106': alert('글이 존재하지 않습니다.'); break;
                case '110': alert('블로그 운영자만 가능합니다. '); break;
                case '000': eval("guestbook_" + flag + "_member(id,re)"); break; // 회원 수정/삭제
                case '001': eval("guestbook_" + flag + "_guest(id,re)"); break; // 비회원 수정/삭제
                default:
                    alert('잘못된 접근입니다.\n\n'+result); break;
                }
            }
        });
}

/*---------------------------------------------------
    방명록 수정
---------------------------------------------------*/
function guestbook_mod_member(id, re) 
{
    if (!re) re = 0;

    var element_mod = $('#m'+id);

    element_mod.toggle();
}

/*---------------------------------------------------
    회원 방명록 수정 창
---------------------------------------------------*/
function guestbook_mod_member_form(id, re) 
{
    if (re)
        element = $('#mr'+id);
    else
        element = $('#m'+id);

    var html    = $('#hidden_comment').html();

    html = html.replace(/guestbook_mod_content/g, 'guestbook_mod_content' + id);

    if (re)
        var writer_content  = trim($('#guestbook_writer_re_content' + id).html());
    else
        var writer_content  = trim($('#guestbook_writer_content' + id).html());
    writer_content  = writer_content.replace(/<BR>/ig, '\n');

    html = html.replace(/guestbook_mod_guest_form_send/g, 'guestbook_mod_member_form_send'  );
    html = html.replace(/input_id/g, id );
    html = html.replace(/input_re/g, re );
    html = html.replace(/input_content/g, writer_content );

    element.show();
    element.html(html);
}

/*---------------------------------------------------
    회원 방명록 수정 내용 전송
---------------------------------------------------*/
function guestbook_mod_member_form_send(id, re) 
{
    var writer_content = $('#guestbook_mod_content' + id).val();

    send  = 'id=' + id;
    send += '&mb_id=' + mb_id;
    send += '&re=' + re;
    send += '&m=update';
    send += '&writer_content=' + encodeURIComponent(writer_content);

    url = gb4_blog+'/guestbook_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            switch( result ) {
                case '104': alert('댓글 내용은 필수 입력입니다.'); break;
                case '105': alert('댓글이 존재하지 않습니다.'); break;
                case '107': alert('본인의 댓글만 수정할 수 있습니다.'); break;
                case '109': alert('잘못된 접근입니다.'); break;
                case '000': guestbook_on(); break;
                default:
                    alert( '잘못된 접근입니다.\n\n' + result ); break;
                } 
            }
        });
}

/*---------------------------------------------------
    댓글의 댓글 입력 
---------------------------------------------------*/
function guestbook_reply(id) {
    if (mb_id!=member_mb_id) {
        alert('블로그 운영자만 답글을 등록할 수 있습니다.');
        return;
    }
    var comm = $('#r' + id);

    $('#guestbook_form').insertAfter(comm);
    $('#guestbook_id').val(id);
    $('#form_secret').css('display', 'none');
}

/*---------------------------------------------------
    회원 방명록 삭제
---------------------------------------------------*/
function guestbook_del_member(id,re)
{
    if( !confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n그래도 삭제하시겠습니까?') ) return;

    send  = 'id=' + id;
    send += '&mb_id=' + mb_id;
    send += '&m=delete';
    send += '&re=' + re;

    url = g4_path+'/'+gb4_blog+'/guestbook_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            switch( result ) {
                case '101': alert('댓글이 존재하지 않습니다.'); break;
                case '102': alert('본인의 댓글만 삭제할 수 있습니다.'); break;
                case '103': alert('비밀번호가 맞지 않습니다.'); break;
                case '000': guestbook_on(); break;
                default:
                    alert( '잘못된 접근입니다.\n\n' + result ); break;
                } 
            }
        });
}

function guestbook_del_guest(id) {

    element_pw  = $('#p'+id);
    element_mod = $('#m'+id);

    if (element_mod.css('display') == 'none') {
        if( element_pw.css('display') == 'none' ) {
            element_pw.show();
        } else {
            element_pw.hide();
        }
    } else {
        element_mod.hide();
    }

}

/*---------------------------------------------------
    비회원 댓글 수정/삭제시 비밀번호 검사
---------------------------------------------------*/
function guestbook_password_confirm(id) 
{
    input_pw = $('#input_pw'+id).val();

    send  = 'm=password';
    send += '&mb_id=' + mb_id;
    send += '&id=' + id;
    send += '&input_pw=' + input_pw;

    url =  gb4_blog+'/guestbook_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            result      = result.split(',');
            msg_num     = result[0];

            var item = new Array();
            item[1] = result[1];  // id
            item[2] = result[2];  // writer_name
            item[3] = result[3];  // writer_email
            item[4] = result[4];  // writer_url
            item[5] = result[5];  // pw
            item[6] = result[6];  // writer_content

            switch( msg_num ) 
            {
                case '101': alert('존재하지 않는 댓글입니다.'); break;
                case '102': alert('비밀번호가 맞지 않습니다.'); break;
                case '109': alert('비밀번호를 입력해주세요.'); break;
                case '000':
                    if(flag=='mod') {
                        guestbook_mod_guest_form(item);
                    } else {
                        guestbook_del_send(item);
                    }
                    break;
                default:
                    alert('잘못된 접근입니다.\n\n'+result); break;
                }

            }
        });

}

function guestbook_del_send(item) {

    id = item[1];
    writer_pw = item[5];

    send  = 'id=' + id;
    send += '&m=delete';
    send += '&mb_id=' + mb_id;
    send += '&writer_pw=' + writer_pw;
    
    url = gb4_blog+'/guestbook_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            switch( result ) {
                case '101': alert('댓글이 존재하지 않습니다.'); break;
                case '102': alert('본인의 댓글만 삭제할 수 있습니다.'); break;
                case '103': alert('비밀번호가 맞지 않습니다.'); break;
                case '000': guestbook_on(); break;
                default:
                    alert( '잘못된 접근입니다.\n\n' + result ); break;
                }
            }
        });
}


/*---------------------------------------------------
    댓글 보이기-안보이기 처리 함수
---------------------------------------------------*/
function comment_open_close(post_id) 
{
    eid = $("#comment"+post_id);

    if( eid.css('display') == 'none') {
        // 메인에서 코멘트 열기를 하면, post_id가 없어서 오류나 가요.
        // 그래서, post_id를 반드시 인수로 넘겨줘야 합니다.
        comment_on(post_id);
    } else {
        eid.hide();
    }
}

/* ajax 로 댓글 불러 오는 함수 */
function comment_on(post_id)
{
    url = gb4_blog+'/comment.php';

    send = 'mb_id=' + mb_id;
    send += '&id=' + post_id;

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
            return_comment_on(result, post_id)
            }
        });

}

/* ajax 로 불러온 내용을 화면에 출력 */
function return_comment_on(result, post_id)
{  
    result = result.split("\n");

    $('#comment_count' + post_id).html(result[0]);

    var html = '';
    for(i=1; i<result.length; i++) {
        html += result[i];
    }
    $('#comment' + post_id).html(html);

    if( !member_mb_id ) {
        // 입력창에 쿠키 넣기, 냠냠;
        //$('#writer_name'  + post_id).val(unescape( get_cookie('writer_name') ));
        //$('#writer_email' + post_id).val(unescape( get_cookie('writer_email') ));
        //$('#writer_url'   + post_id).val(unescape( get_cookie('writer_url') ));
    }

    $('#comment' + post_id).show();
}

/*---------------------------------------------------
    댓글 입력
---------------------------------------------------*/
function send_comment(id) 
{
    post_id = id;

    send  = 'post_id=' + post_id;
    send += '&mb_id=' + mb_id;

    if( !member_mb_id ) { 
        writer_name     = $('#writer_name'     +   post_id).val();
        writer_email    = $('#writer_email'    +   post_id).val();
        writer_url      = $('#writer_url'      +   post_id).val();
        writer_pw       = $('#writer_pw'       +   post_id).val();
        wr_key          = $('#wr_key').val();
        
        //set_cookie('writer_name',   writer_name,    720, g4_cookie_domain);
        //set_cookie('writer_email',  writer_email,   720, g4_cookie_domain);
        //set_cookie('writer_url',    writer_url,     720, g4_cookie_domain);

        send += '&writer_name='     + encodeURIComponent(writer_name);
        send += '&writer_email='    + encodeURIComponent(writer_email);
        send += '&writer_url='      + encodeURIComponent(writer_url);
        send += '&writer_pw='       + encodeURIComponent(writer_pw);
        send += '&wr_key='          + encodeURIComponent(wr_key);
    }
    comment_num     = $('#comment_num' + post_id).val();
    writer_content  = encodeURIComponent($('#writer_content' + post_id).val());

    if( $('#secret' + post_id).attr('checked') == true ) secret = 1; else secret = 0;

    send += '&comment_num=' + comment_num;
    send += '&secret=' + secret;
    send += '&writer_content=' + writer_content;

    url = gb4_blog+'/comment_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            switch( result ) {
                case '101': alert('이름은 필수 입력입니다.'); break;
                case '102': alert('이메일은 필수 입력입니다.'); break;
                case '103': alert('비밀번호는 필수 입력입니다.'); break;
                case '104': alert('댓글 내용은 필수 입력입니다.'); break;
                case '105': alert('자동등록방지 코드를 입력해주세요.'); break;
                case '106': alert('자동등록방지 코드가 잘못되었습니다.'); break;
                case '107': alert('비밀번호를 4자 이상 입력해주세요.'); break;
                case '108': alert('댓글을 허락하지 않았습니다.'); break;
                case '109': alert('잘못된 접근입니다.'); break;
                case '000': comment_on(post_id); break;
                default:
                    alert('잘못된 접근입니다.\n\n'+result); break;
                }
            }
    });

}


/*---------------------------------------------------
    댓글의 댓글 입력 
---------------------------------------------------*/
function comment_reply(id,comment_id,comment_num) {
    post_id = id;
    comm = $('#r' + comment_id);
    $('#comment_form' + post_id).insertAfter(comm);
    $('#comment_num' + post_id).val(comment_num);
}

/*---------------------------------------------------
    댓글 수정/삭제 권한 검사
---------------------------------------------------*/
function comment_permission(id, comment_id, action) 
{
    post_id = id;
    flag    = action;

    send  = 'mb_id=' + mb_id;
    send += '&comment_id=' + comment_id;
    send += '&action=' + action;
    send += '&m=permission';

    url = gb4_blog+'/comment_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            result      = result.split(',');
            msg_num     = result[0];
            comment_id  = result[1];

            switch( msg_num ) {
                case '101': alert('수정/삭제 할 권한이 없습니다.'); break;
                case '102': alert('댓글의 댓글이 등록되어 있으면 수정/삭제가 불가능합니다.'); break;
                case '106': alert('댓글이 존재하지 않습니다.'); break;
                case '000': eval("comment_" + flag + "_member(comment_id)"); break; // 회원 수정/삭제
                case '001': eval("comment_" + flag + "_guest(comment_id)"); break; // 비회원 수정/삭제
                default:
                    alert('잘못된 접근입니다.\n\n'+result); break;
                }
            }
        });
}


/*---------------------------------------------------
    회원 댓글 삭제
---------------------------------------------------*/
function comment_del_member(comment_id)
{
    if( !confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n그래도 삭제하시겠습니까?') ) return;

    send  = 'comment_id=' + comment_id;
    send += '&mb_id=' + mb_id;
    send += '&post_id=' + post_id;
    send += '&m=delete';

    url = gb4_blog+'/comment_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            err = false;
            switch( result ) {
                case '101': alert('댓글이 존재하지 않습니다.'); break;
                case '102': alert('본인의 댓글만 삭제할 수 있습니다.'); break;
                case '103': alert('비밀번호가 맞지 않습니다.'); break;
                case '000': comment_on(post_id); break;
                default:
                    alert( '잘못된 접근입니다.\n\n' + result ); break;
                } 
            }
        });

}

function comment_del_guest(comment_id) {

    element_pw  = $('#p'+comment_id);
    element_mod = $('#m'+comment_id);

    if( element_mod.css('display') == 'none') {
        if( element_pw.css('display') == 'none' ) {
            element_pw.show();
        } else {
            element_pw.hide();
        }
    } else {
        element_mod.hide();
    }
}

function comment_del_send(item) {

    comment_id = item[1];
    writer_pw = item[5];

    send  = 'comment_id=' + comment_id;
    send += '&m=delete';
    send += '&mb_id=' + mb_id;
    send += '&post_id=' + post_id;
    send += '&writer_pw=' + writer_pw;
    
    url = gb4_blog+'/comment_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            switch( result ) {
                case '101': alert('댓글이 존재하지 않습니다.'); break;
                case '102': alert('본인의 댓글만 삭제할 수 있습니다.'); break;
                case '103': alert('비밀번호가 맞지 않습니다.'); break;
                case '000': comment_on(post_id); break;
                default:
                    alert( '잘못된 접근입니다.\n\n' + result ); break;
                } 
            }

        });

}


/*---------------------------------------------------
    댓글 수정
---------------------------------------------------*/
function comment_mod_member(comment_id) 
{
    var element_mod = $('#m'+comment_id);

    if( element_mod.css('display') == 'none') {
        element_mod.show();
        comment_mod_member_form(comment_id);
    } else {
        element_mod.hide();
    }
}


/*---------------------------------------------------
    회원 댓글 수정 창
---------------------------------------------------*/
function comment_mod_member_form(comment_id) 
{
    var element = $('#m'+comment_id);
    var html    = $('#hidden_comment').html();

    html = html.replace(/comment_mod_content/g, 'comment_mod_content'   + comment_id );

    var writer_content  = trim($('#comment_writer_content' + comment_id).html());
    writer_content  = writer_content.replace(/<BR>/ig, '\n');

    html = html.replace(/comment_mod_guest_form_send/g, 'comment_mod_member_form_send'  );
    html = html.replace(/input_id/g, comment_id );
    html = html.replace(/input_content/g, writer_content );

    element.show();
    element.html(html);
}

/*---------------------------------------------------
    회원 댓글 수정 내용 전송
---------------------------------------------------*/
function comment_mod_member_form_send(comment_id) 
{
    var writer_content = $('#comment_mod_content' + comment_id).val();

    send  = 'comment_id=' + comment_id;
    send += '&mb_id=' + mb_id;
    send += '&m=update';
    send += '&writer_content=' + encodeURIComponent(writer_content);

    url = gb4_blog+'/comment_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            switch( result ) {
                case '104': alert('댓글 내용은 필수 입력입니다.'); break;
                case '105': alert('댓글이 존재하지 않습니다.'); break;
                case '107': alert('본인의 댓글만 수정할 수 있습니다.'); break;
                case '109': alert('잘못된 접근입니다.'); break;
                case '000': comment_on(post_id); break;
                default:
                    alert( '잘못된 접근입니다.\n\n' + result ); break;
                } 
            }
        });

}


/*---------------------------------------------------
    비회원 댓글 수정 비밀번호 창 오픈
---------------------------------------------------*/
function comment_mod_guest(comment_id)
{
    element_pw  = $('#p'+comment_id);
    element_mod = $('#m'+comment_id);

    if( element_mod.css('display') == 'none') {
        if( element_pw.css('display') == 'none' )  {
            element_pw.show();
        } else {
            element_pw.hide();
        }
    } else {
        element_mod.hide();
    }
}

/*---------------------------------------------------
    비회원 댓글 수정/삭제시 비밀번호 검사
---------------------------------------------------*/
function comment_password_confirm(comment_id) 
{
    input_pw = $('#input_pw'+comment_id).val();

    send  = 'm=password';
    send += '&mb_id=' + mb_id;
    send += '&comment_id=' + comment_id;
    send += '&input_pw=' + input_pw;

    url =  gb4_blog+'/comment_update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
            return_comment_password_confirm(result) 
            }
        });        
        
}
function return_comment_password_confirm(result) 
{
    result      = result.split(',');
    msg_num     = result[0];

    var item = new Array();
    item[1] = result[1];  // comment_id
    item[2] = result[2];  // writer_name
    item[3] = result[3];  // writer_email
    item[4] = result[4];  // writer_url
    item[5] = result[5];  // pw
    item[6] = result[6];  // writer_content

    switch( msg_num ) 
    {
        case '101': alert('존재하지 않는 댓글입니다.'); err=true; break;
        case '102': alert('비밀번호가 맞지 않습니다.'); err=true; break;
        case '109': alert('비밀번호를 입력해주세요.'); err=true; break;
        case '000':
            if(flag=='mod') {
                comment_mod_guest_form(item);
            } else {
                comment_del_send(item);
            }
            break;
        default:
            alert('잘못된 접근입니다.\n\n'+result); err=true; break;
    }
}

/*---------------------------------------------------
    비회원 댓글 수정 창 오픈 
---------------------------------------------------*/
function comment_mod_guest_form(item)
{
    comment_id = item[1];
    writer_name = item[2];
    writer_email = item[3];
    writer_url = item[4];
    writer_pw = item[5];
    writer_content = item[6];

    $('#p'+comment_id).css('display', 'none');

    element = $('#m'+comment_id);
    html    = $('#hidden_comment').html();

    html = html.replace(/comment_mod_name/g,    'comment_mod_name'      + comment_id );
    html = html.replace(/comment_mod_email/g,   'comment_mod_email'     + comment_id );
    html = html.replace(/comment_mod_url/g,     'comment_mod_url'       + comment_id );
    html = html.replace(/comment_mod_pw/g,      'comment_mod_pw'        + comment_id );

    html = html.replace(/comment_mod_content/g, 'comment_mod_content'   + comment_id );

    writer_content  = writer_content.replace(/<BR>/g, '\n');

    html = html.replace(/input_name/g,      writer_name     );
    html = html.replace(/input_email/g,     writer_email    );
    html = html.replace(/input_url/g,       writer_url      );
    html = html.replace(/input_content/g,   writer_content  );
    html = html.replace(/input_pw/g,        writer_pw       );
    html = html.replace(/input_id/g,        comment_id      );

    element.show();
    element.html(html);
}

/*---------------------------------------------------
    비회원 댓글 수정 내용 전송
---------------------------------------------------*/
function comment_mod_guest_form_send(comment_id) 
{
    send  = "comment_id=" + comment_id;
    send += "&mb_id=" + mb_id;
    send += "&m=update";

    if( !member_mb_id ) {
        writer_name     = $("#comment_mod_name"     +   comment_id).val();
        writer_email    = $("#comment_mod_email"    +   comment_id).val();
        writer_url      = $("#comment_mod_url"      +   comment_id).val();
        writer_pw       = $("#comment_mod_pw"       +   comment_id).val();

        //set_cookie("writer_name",   writer_name,    720, g4_cookie_domain);
        //set_cookie("writer_email",  writer_email,   720, g4_cookie_domain);
        //set_cookie("writer_url",    writer_url,     720, g4_cookie_domain);

        send += "&writer_name="     + encodeURIComponent(writer_name);
        send += "&writer_email="    + encodeURIComponent(writer_email);
        send += "&writer_url="      + encodeURIComponent(writer_url);
        send += "&writer_pw="       + encodeURIComponent(writer_pw);
    }

    writer_content  = $("#comment_mod_content" + comment_id).val();

    send += "&writer_content=" + encodeURIComponent(writer_content);

    url = gb4_blog+"/comment_update.php";

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
            return_comment_mod_guest_form_send(result);
            }
        });
}

function return_comment_mod_guest_form_send(result)
{
     result      = result.split(',');
     msg_num     = result[0];

    switch( msg_num ) {
        case '101': alert('이름은 필수 입력입니다.'); break;
        //case '102': alert('이메일은 필수 입력입니다.'); break;
        case '103': alert('비밀번호는 필수 입력입니다.'); break;
        case '104': alert('댓글 내용은 필수 입력입니다.'); break;
        case '105': alert('댓글이 존재하지 않습니다.'); break;
        case '106': alert('비밀번호가 일치하지 않습니다.'); break;
        case '107': alert('본인의 댓글만 수정할 수 있습니다.'); break;
        case '109': alert('잘못된 접근입니다.'); break;
        case '000': comment_on(post_id); break;
        default:
            alert( '잘못된 접근입니다.\n\n' + result ); break;

    } 
}

/*---------------------------------------------------
    비회원 댓글 수정 비밀번호 창 오픈
---------------------------------------------------*/
function guestbook_mod_guest(id)
{
    element_pw  = $('#p'+id);
    element_mod = $('#m'+id);

    if( element_mod.css('display') == 'none') {
        if( element_pw.css('display') == 'none' )  {
            element_pw.show();
        } else {
            element_pw.hide();
        }
    } else {
        element_mod.css('display', 'none');
    }
}

/*---------------------------------------------------
    비회원 댓글 수정 창 오픈 
---------------------------------------------------*/
function guestbook_mod_guest_form(item)
{
    id = item[1];
    writer_name = item[2];
    writer_email = item[3];
    writer_url = item[4];
    writer_pw = item[5];
    writer_content = item[6];

    re = "'mod'";

    $('#p'+id).css('display', 'none');

    element = $('#m'+id);
    html    = $('#hidden_comment').html();

    html = html.replace(/guestbook_mod_name/g,    'guestbook_mod_name'      + id );
    html = html.replace(/guestbook_mod_email/g,   'guestbook_mod_email'     + id );
    html = html.replace(/guestbook_mod_url/g,     'guestbook_mod_url'       + id );
    html = html.replace(/guestbook_mod_pw/g,      'guestbook_mod_pw'        + id );

    html = html.replace(/guestbook_mod_content/g, 'guestbook_mod_content'   + id );

    writer_content  = writer_content.replace(/<BR>/g, '\n');

    html = html.replace(/input_name/g,      writer_name     );
    html = html.replace(/input_email/g,     writer_email    );
    html = html.replace(/input_url/g,       writer_url      );
    html = html.replace(/input_content/g,   writer_content  );
    html = html.replace(/input_pw/g,        writer_pw       );
    html = html.replace(/input_id/g,        id      );
    html = html.replace(/input_re/g,        re );

    element.show();
    element.html(html);
}

/*---------------------------------------------------
    비회원 댓글 수정 내용 전송
---------------------------------------------------*/
function guestbook_mod_guest_form_send(id) 
{
    send  = "id=" + id;
    send += "&mb_id=" + mb_id;
    send += "&m=update";

    if( !member_mb_id ) {
        writer_name     = $("#guestbook_mod_name"     +   id).val();
        writer_email    = $("#guestbook_mod_email"    +   id).val();
        writer_url      = $("#guestbook_mod_url"      +   id).val();
        writer_pw       = $("#guestbook_mod_pw"       +   id).val();

        //set_cookie("writer_name",   writer_name,    720, g4_cookie_domain);
        //set_cookie("writer_email",  writer_email,   720, g4_cookie_domain);
        //set_cookie("writer_url",    writer_url,     720, g4_cookie_domain);

        send += "&writer_name="     + encodeURIComponent(writer_name);
        send += "&writer_email="    + encodeURIComponent(writer_email);
        send += "&writer_url="      + encodeURIComponent(writer_url);
        send += "&writer_pw="       + encodeURIComponent(writer_pw);
    }

    writer_content  = $("#guestbook_mod_content" + id).val();

    send += "&writer_content=" + encodeURIComponent(writer_content);

    url = gb4_blog+"/guestbook_update.php";

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
            return_guestbook_mod_guest_form_send(result);
            }
        });
}

function return_guestbook_mod_guest_form_send(result)
{
     result      = result.split(',');
     msg_num     = result[0];

    switch( msg_num ) {
        case '101': alert('이름은 필수 입력입니다.'); break;
        case '102': alert('이메일은 필수 입력입니다.'); break;
        case '103': alert('비밀번호는 필수 입력입니다.'); break;
        case '104': alert('댓글 내용은 필수 입력입니다.'); break;
        case '105': alert('댓글이 존재하지 않습니다.'); break;
        case '106': alert('비밀번호가 일치하지 않습니다.'); break;
        case '107': alert('본인의 댓글만 수정할 수 있습니다.'); break;
        case '109': alert('잘못된 접근입니다.'); break;
        case '000': guestbook_on(); break;
        default:
            alert( '잘못된 접근입니다.\n\n' + result ); break;
    } 
}


function search() {
	if( $("#search_content") != 'undefined' ) {
		search = $("#search_content").val();
		url = $("#search_url").val();
        location.href = url + encodeURIComponent(search);
	}
}

function profile_image_window(img,width,height)
{
    //var w = img.tmp_width; 
    //var h = img.tmp_height; 
    var w = width; 
    var h = height; 
    var winl = (screen.width-w)/2; 
    var wint = (screen.height-h)/3; 

    if (w >= screen.width) { 
        winl = 0; 
        h = (parseInt)(w * (h / w)); 
    } 

    if (h >= screen.height) { 
        wint = 0; 
        w = (parseInt)(h * (w / h)); 
    } 

    var js_url = "<script language='JavaScript1.2'> \n"; 
        js_url += "<!-- \n"; 
        js_url += "var ie=document.all; \n"; 
        js_url += "var nn6=$&&!document.all; \n"; 
        js_url += "var isdrag=false; \n"; 
        js_url += "var x,y; \n"; 
        js_url += "var dobj; \n"; 
        js_url += "function movemouse(e) \n"; 
        js_url += "{ \n"; 
        js_url += "  if (isdrag) \n"; 
        js_url += "  { \n"; 
        js_url += "    dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x; \n"; 
        js_url += "    dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y; \n"; 
        js_url += "    return false; \n"; 
        js_url += "  } \n"; 
        js_url += "} \n"; 
        js_url += "function selectmouse(e) \n"; 
        js_url += "{ \n"; 
        js_url += "  var fobj      = nn6 ? e.target : event.srcElement; \n"; 
        js_url += "  var topelement = nn6 ? 'HTML' : 'BODY'; \n"; 
        js_url += "  while (fobj.tagName != topelement && fobj.className != 'dragme') \n"; 
        js_url += "  { \n"; 
        js_url += "    fobj = nn6 ? fobj.parentNode : fobj.parentElement; \n"; 
        js_url += "  } \n"; 
        js_url += "  if (fobj.className=='dragme') \n"; 
        js_url += "  { \n"; 
        js_url += "    isdrag = true; \n"; 
        js_url += "    dobj = fobj; \n"; 
        js_url += "    tx = parseInt(dobj.style.left+0); \n"; 
        js_url += "    ty = parseInt(dobj.style.top+0); \n"; 
        js_url += "    x = nn6 ? e.clientX : event.clientX; \n"; 
        js_url += "    y = nn6 ? e.clientY : event.clientY; \n"; 
        js_url += "    document.onmousemove=movemouse; \n"; 
        js_url += "    return false; \n"; 
        js_url += "  } \n"; 
        js_url += "} \n"; 
        js_url += "document.onmousedown=selectmouse; \n"; 
        js_url += "document.onmouseup=new Function('isdrag=false'); \n"; 
        js_url += "//--> \n"; 
        js_url += "</"+"script> \n"; 

    var settings;

    if (g4_is_gecko) {
        settings  ='width='+(w+10)+','; 
        settings +='height='+(h+10)+','; 
    } else {
        settings  ='width='+w+','; 
        settings +='height='+h+','; 
    }
    settings +='top='+wint+','; 
    settings +='left='+winl+','; 
    settings +='scrollbars=no,'; 
    settings +='resizable=yes,'; 
    settings +='status=no'; 


    win=window.open("","image_window",settings); 
    win.document.open(); 
    win.document.write ("<html><head> \n<meta http-equiv='imagetoolbar' CONTENT='no'> \n<meta http-equiv='content-type' content='text/html; charset="+g4_charset+"'>\n"); 
    var size = "이미지 사이즈 : "+w+" x "+h;
    win.document.write ("<title>"+size+"</title> \n"); 
    if(w >= screen.width || h >= screen.height) { 
        win.document.write (js_url); 
        var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n 이미지 사이즈가 화면보다 큽니다. \n 왼쪽 버튼을 클릭한 후 마우스를 움직여서 보세요. \n\n 더블 클릭하면 닫혀요. '"; 
    } 
    else 
        var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n 클릭하면 닫혀요. '"; 
    win.document.write ("<style>.dragme{position:relative;}</style> \n"); 
    win.document.write ("</head> \n\n"); 
    win.document.write ("<body leftmargin=0 topmargin=0 bgcolor=#dddddd style='cursor:arrow;'> \n"); 
    win.document.write ("<table width=100% height=100% cellpadding=0 cellspacing=0><tr><td align=center valign=middle><img src='"+img.src+"' width='"+w+"' height='"+h+"' border=0 class='dragme' "+click+"></td></tr></table>");
    win.document.write ("</body></html>"); 
    win.document.close(); 

    if(parseInt(navigator.appVersion) >= 4){win.window.focus();} 
}

function trackback_del(t_id, post_id) {
    
    if( !confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?') ) return;

    trackback_id = t_id;

    send = "mb_id="+mb_id;
    send += '&trackback_id=' + trackback_id;
    send += '&post_id=' + post_id;

    url = gb4_blog+'/adm_trackback_delete.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
            return_trackback_del(result);
            }
        });
}

function return_trackback_del(result) {

    result      = result.split(',');
    msg_num     = result[0];
  
    switch( msg_num ) {
        case '000' : 
            tc = $('#trackback_count'+post_id).html();
            tc = parseInt(tc)-1;
            $('#trackback_count'+post_id).html(tc);
            $('#t'+trackback_id).css('display', 'none'); 
            break;
        case '101' : alert('자신의 블로그만 접근할 수 있습니다.'); break;
        case '109' : alert('mb_id 가 없습니다.'); break;
    }
}

/*---------------------------------------------------
    댓글 쿠키 적용
---------------------------------------------------*/
if (typeof(member_mb_id) == "undefined")
    var member_mb_id = "";
if (typeof(post_id) == "undefined")
    var post_id = "";

if( post_id && count ) {
    element = $('#comment' + post_id );
    /*
    if( !member_mb_id && use_comment ) {
        $('#writer_name' + post_id).val( unescape( get_cookie('writer_name') ) );
        $('#writer_email' + post_id).val( unescape( get_cookie('writer_email') ) );
        if( get_cookie('writer_url') != '' )
            $('#writer_url' + post_id).val( unescape( get_cookie('writer_url') ) );
    }
    */
}



/*---------------------------------------------------
    방명록 쿠키 적용
---------------------------------------------------*/
/*
function guestbook_cookie() {
    if( $('#writer_name') != null ) {
        if( !member_mb_id ) {
            $('#writer_name').val( unescape( get_cookie('writer_name') ) );
            $('#writer_email').val( unescape( get_cookie('writer_email') ) );
            if( get_cookie('writer_url') != '' )
                $('#writer_url').val( unescape( get_cookie('writer_url') ) );
        }
    }
}
*/
//guestbook_cookie();

// 그누보드 게시글을 gblog에 등록 하기
function send_to_gblog(bo_table, wr_id) {
    
    var send = "";

    send = 'bo_table=' + bo_table;
    send += '&wr_id=' + wr_id;

    url = gb4_blog+'/g4_to_gblog.update.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

             result      = result.split(',');
             msg_num     = result[0];

            switch( msg_num ) {
                case '101': alert('게시판 또는 게시글 정보가 없습니다.'); break;
                case '102': alert('비회원은 사용할 수 없습니다. 로그인 하시기 바랍니다.'); break;
                case '103': alert('블로그가 없습니다. 블로그를 먼저 만드시기 바랍니다.'); break;
                case '104': alert('게시글정보가 없습니다.'); break;
                case '105': alert('본인의 글만 블로그로 보내는 것이 가능 합니다.'); break;
                case '106': alert('이미 블로그로 보내진 글 입니다. 블로그 게시글 번호는 '+result[1]+' 입니다.'); break;
                case '107': alert('첨부파일을 이동할 수 없습니다.'); break;
                case '000': alert('게시글이 블로그에 등록되었습니다'); break;
                default:
                    alert('잘못된 접근입니다.\n\n'+result); break;
                } 
            }
        });
}

function get_bitly_gblog(tid, mb_id, id, blog_url_encode, blog_url) {
  // set up default options 
  var defaults = { 
    version:    '2.0.1', 
    login:      bitly_id, 
    apiKey:     bitly_key, 
    history:    '0', 
    longUrl:    blog_url_encode
  }; 
 
  // Build the URL to query 
  var daurl = "http://api.bit.ly/shorten?" 
    +"version="+defaults.version 
    +"&longUrl="+defaults.longUrl 
    +"&login="+defaults.login 
    +"&apiKey="+defaults.apiKey 
    +"&history="+defaults.history 
    +"&format=json&callback=?"; 

    // Utilize the bit.ly API 
    $.getJSON(daurl, function(data){ 

        var bitly_url = data.results[blog_url].shortUrl;

        // Make a good use of short URL 
        $(tid).html('<a href='+bitly_url+' target=new>'+bitly_url+'</a>');

        url = gb4_blog+'/bitly_update.php';

        send  = 'mb_id=' + mb_id;
        send += '&id=' + id;
        send += '&bitly_url=' + bitly_url;

        $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            result      = result.split(',');
            msg_num     = result[0];

            if (msg_num !== '000')
                alert('잘못된 접근입니다.\n\n'+result); 
            }

        });
 
    });

};