$(document).ready(function(){
    (function(){
        var alertTips = $.trim($('#alert-tips').html());
        if(typeof alertTips != 'undefined' && alertTips != null && alertTips){
            var classname = $('#alert-tips div').attr('class');
            switch(classname){
                case 'errorMessage':
                    $('#alert-tips div').addClass('alert').addClass('alert-danger');
                    break;
                case 'successMessage':
                    $('#alert-tips div').addClass('alert').addClass('alert-success');
                    break;
                case 'noticeMessage':
                    $('#alert-tips div').addClass('alert').addClass('alert-info');
                    break;
                case 'warningMessage':
                    $('#alert-tips div').addClass('alert').addClass('alert-warning');
                    break;
                default:
                    $('#alert-tips div').addClass('alert').addClass('alert-info');
                    break;
            }
            $('#alert-tips').slideToggle(0).delay(3000).slideToggle(300);
        }
    })();
});

function tips_message(message, level){
    var str = '';
    switch(level){
        case 'error':
            str = '<div class="alert alert-danger">' + message + '</div>';
            break;
        case 'success':
            str = '<div class="alert alert-success">' + message + '</div>';
            break;
        case 'notice':
            str = '<div class="alert alert-info">' + message + '</div>';
            break;
        case 'warning':
            str = '<div class="alert alert-warning">' + message + '</div>';
            break;
        default:
            str = '<div class="alert alert-danger">' + message + '</div>';
            break;
    }
    $('#alert-tips').html(str);
    $('#alert-tips').slideToggle('fast').delay(3000).slideToggle(300);
}
