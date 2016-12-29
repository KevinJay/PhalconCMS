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

    $('#login-btn').on('click', function(){
        var username = $.trim($('#username').val());
        var usernamePattern = /^[\w-]{4,20}$/i;
        if(!usernamePattern.test(username)){
            tips_message('用户名由4-20个英文字符、数字、中下划线组成');
            return false;
        }

        var password = $.trim($('#password').val());
        if(password.length < 6 || password.length > 32){
            tips_message('密码由6-32个字符组成');
            return false;
        }

        $('#login-form').submit();
    });

    $('#category-btn').on('click', function(){
        var categoryName = $.trim($('#category-name').val());
        var categoryNamePattern = /^[\u4e00-\u9fa5\w-]+$/i;
        if(!categoryNamePattern.test(categoryName)){
            tips_message('分类名称由中英文字符、数字、下划线和横杠组成');
            return false;
        }

        var categorySlug = $.trim($('#category-slug').val());
        var categorySlugPattern = /^[\w-]+$/i;
        if(categorySlug == true && !categorySlugPattern.test(categorySlug)){
            tips_message('分类缩略名由英文字符、数字、下划线和横杠组成');
            return false;
        }

        $('#category-form').submit();
    });

    //$('#category-checkbox').on('click', function(event){
    //    var ischeck = $(this).prop('checked');
    //    $('#category-list-box :checkbox').prop('checked', ischeck);
    //    event.stopPropagation();
    //});

    $('#tag-btn').on('click', function(){
        var tagName = $.trim($('#tag-name').val());
        var tagNamePattern = /^[\u4e00-\u9fa5\w-]+$/i;
        if(!tagNamePattern.test(tagName)){
            tips_message('标签名称由中英文字符、数字、下划线和横杠组成');
            return false;
        }

        var tagSlug = $.trim($('#tag-slug').val());
        var tagSlugPattern = /^[\w-]+$/i;
        if(tagSlug == true && !tagSlugPattern.test(tagSlug)){
            tips_message('标签缩略名由英文字符、数字、下划线和横杠组成');
            return false;
        }

        $('#tag-form').submit();
    });

    $('.delete-tag').on('click', function(){
        var dataUrl = $.trim($(this).attr('data-url'));
        if(!window.confirm('确定要删除选中标签吗？此操作不可挽回')){
            return false;
        }
        window.location.href = dataUrl;
    });

    $('#base-options-btn').on('click', function(){
        var siteName = $.trim($('#site-name').val());
        var siteNamePattern = /^[\u4e00-\u9fa5\w-]+$/i;
        if(!siteNamePattern.test(siteName)){
            tips_message('站点名称由中英文字符、数字、下划线和横杠组成');
            return false;
        }

        $('#base-options-form').submit();
    });

    $('#save-profile-btn').on('click', function(){
        var nickname = $.trim($('#nickname').val());
        var nicknamePattern = /^[\u4e00-\u9fa5\w-]{2,20}$/i;
        if(!nicknamePattern.test(nickname)){
            tips_message('昵称由2-20个中英文字符、数字、下划线和横杠组成');
            return false;
        }

        var email = $.trim($('#user-email').val());
        var emailPattern = /^[_a-z0-9-\.]+@([-a-z0-9]+\.)+[a-z]{2,}$/i;
        if(!emailPattern.test(email)){
            tips_message('请填写正确的邮箱地址');
            return false;
        }

        $('#save-profile-form').submit();
    });

    $('#save-password-btn').on('click', function(){
        var oldpwd = $.trim($('#old-password').val());
        if(oldpwd == '' || oldpwd == false){
            tips_message('请填写原始密码');
            return false;
        }
        if(oldpwd.length < 6 || oldpwd.length > 20){
            tips_message('密码由6-20个字符组成');
            return false;
        }

        var newpwd = $.trim($('#new-password').val());
        if(newpwd == '' || newpwd == false){
            tips_message('请填写新密码');
            return false;
        }
        if(newpwd.length < 6 || newpwd.length > 20){
            tips_message('密码由6-20个字符组成');
            return false;
        }

        var confirmpwd = $.trim($('#confirm-password').val());
        if(newpwd != confirmpwd){
            tips_message('两次输入的新密码不一致');
            return false;
        }

        if(oldpwd == newpwd){
            tips_message('新密码不能与原始密码相同');
            return false;
        }
        $('#save-password-form').submit();
    });


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
