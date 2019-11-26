"use strict";

$('.btn1').click(function (e) {
    e.preventDefault();
    let inf = $(this).data('inf');
   $.ajax({
       url: 'respons',
       type: 'GET',
       data: {inf: inf},
       success: function (res) {
           alert(res);
       },
       error: function () {
           alert('не работает');

       }
   });

});

$(".file-link").click(function (e) {
    e.preventDefault();
    let inf = $(this).data('inf');
    $.ajax({
        url: 'upload',
        data: {param: inf},
        type: 'GET',
        success: function () {

            location.reload();
        },
        error: function () {
            alert('не работает');

        }
    });
});

$(".delete").click(function (e) {
    e.preventDefault();
    let name = $(this).data('name');
    let path = $(this).data('path');
    $.ajax({
        url: 'delete',
        data: {name: name, path: path},
        type: 'GET',
        success: function () {
            location.reload();
        },
        error: function () {
            alert('не работает');
        }
    });
});

let name = '';
let path = '';

$(".rename").click(function (e) {
    e.preventDefault();

    name = $(this).data('name');
    path = $(this).data('path');
    $("#basicModal input[name='input']").val(name);
    $('#basicModal').modal('show');

});

$("#basicModal .safe-input").click(function (e) {
    e.preventDefault();

    let newname = $("#basicModal input[name='input']").val();
    $('#basicModal').modal('hide');

    $.ajax({
        url: 'rename',
        data: {name: name, newname: newname, path: path},
        type: 'GET',
        success: function (res) {
            location.reload();
        },
        error: function () {
            alert('не работает');
        }
    });
});

/*$(".download").click(function (e) {
    e.preventDefault();
    let name1 = $(this).data('name');
    let path1 = $(this).data('path');

    $.ajax({
        url: 'download',
        data: {name1: name1, path1: path1},
        type: 'GET',
        success: function () {
            // alert(res);
            // location.reload();
        },
        error: function () {
            alert('не работает');
        }
    });
});*/




