$(function(){
  $(document).on('click','.translations-delete',function (e) {
    a = $(this);
    
    url = a.data('url');
    id = a.data('id');
    language = a.data('language');

    $.get(url, {language: language, id: id}, function(data) {
      // console.log(data);
    });

  });
});