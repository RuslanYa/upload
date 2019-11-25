$(".delete").click(function(){
   alert('hellow');
});

$('#btn1').click(function () {
   $.ajax({
       url: 'respons.php',
       success: function (respons) {
           
       }
   });
    $('#par1');
    alert('u');

});

$(".file-link").click(function (e) {
    e.preventDefault();
    let inf = $(this).data('inf');
    $.ajax({
        url: 'respons',
        data: {inf: inf},
        // type: 'POST',
        success: function (inf) {
            // $('td').html(data);
            alert(inf);
        },
        error: function () {
            alert('не работает');

        }

    });

});


