$(function(){
  
  $(document).on('click','.translations-delete',function (e) {
    $(this).next('.translations-delete-confirm').fadeIn();
    e.preventDefault();
    return false;
  });

  $(document).on('click','.translations-delete-cancel-btn',function (e) {
    $(this).parent('.translations-delete-confirm').fadeOut();
    e.preventDefault();
    return false;
  });
  
  $(document).on('click','.translations-delete-confirm-btn',function (e) {
    a = $(this);  
    url = a.data('url');
    id = a.data('id');
    language = a.data('language');

    $.get(url, {language: language, id: id}, function(data) {
      // console.log(data);
    });

  });
});