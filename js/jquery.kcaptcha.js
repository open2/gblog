if (typeof(KCAPTCHA_JS) == 'undefined') // �ѹ��� ����
{
    if (typeof g4_path == 'undefined')
        alert('g4_path ������ ������� �ʾҽ��ϴ�. js/jquery.kcaptcha.js');

    var KCAPTCHA_JS = true;

var md5_norobot_key = ''; 
$(document).ready(function() {
    $('#kcaptcha_image').attr('width', '120').attr('height','60');
    $('#kcaptcha_image').attr('src', g4_path + '/img/captcha_loading.gif');
    $('#kcaptcha_image').attr('title', '���ڰ� �߾Ⱥ��̴� ��� Ŭ���Ͻø� ���ο� ���ڰ� ���ɴϴ�.');
    $('#kcaptcha_image').click(function() {
        $.post(g4_path + "/" + g4_bbs + '/kcaptcha_session.php', function(data) {
            $('#kcaptcha_image').attr('src', g4_path + "/" + g4_bbs + '/kcaptcha_image.php?t=' + (new Date).getTime());
            md5_norobot_key = data;
        });
    });
    $('#kcaptcha_image').click();
});
}