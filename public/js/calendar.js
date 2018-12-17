$(document).ready(function() {

    // mark weekends
    /*$(".ferienm .wkend").each( function() {
     $('td:nth-child('+($(this).index()+2)+')').css('border-left','4px solid #CCD');
     $('th:nth-child('+($(this).index()+2)+')').css('border-left','4px solid #CCD');
     });*/

    // for region page
    // $("table .wkend").parent('tr').nextAll().addBack().find('td').addClass('bordrleft');
    $('.wkend').each(function () {
        var $tr = $(this).parent();
        var col = $tr.children().index($(this)) + 1;
        // mark cell above
        $tr.prev().children().eq(col).addClass('bordrleft');
        // mark cell itsefl
        $tr.children().eq(col).addClass('bordrleft');
        // mark all cells below
        $tr.nextAll().find('td:nth-child(' + ($(this).index() + 2) + ')').addClass('bordrleft');
    });

    $('.today').each(function () {
        var cellpos = $(this).index() + 1;
        var ttr = $(this).parent();
        // th border at the right
        $(this).next().addClass('bordrtoday');
        // mark all cells below today
        // all borders at the left
        ttr.nextAll().find('td:nth-child(' + cellpos + ')').addClass('bordrtoday');
        // all borders at the right
        ttr.nextAll().find('td:nth-child(' + (cellpos + 1) + ')').addClass('bordrtoday');
        // last cell with border bottom
        ttr.nextAll().last().find('td:nth-child(' + cellpos + ')').addClass('bordrBTMtoday');
    });

    // mark sa and so in head
    $('.bordered td').each(function () {
        if ($(this).text() == 'Sa' || $(this).text() == 'So') {
            $(this).addClass('saso-mark');
        }
    });


    $('.bordered td:first-child a').tipsy({gravity: 'w', fade: false, offset: 5, html: true});
    $('.bordered tr:first-child td').tipsy({gravity: 's', fade: false, offset: 5});
    $('.bordered tr:nth-child(-n+2) td').tipsy({gravity: 's', fade: false, offset: 5});
    $('.bordered .free').tipsy({gravity: 's', fade: false, offset: 5, html: true});
    $('.today').tipsy({gravity: 's', fade: false, offset: 5});
    $('.navpre').tipsy({gravity: 'w', fade: false, offset: 5});
    $('.navfwd').tipsy({gravity: 'w', fade: false, offset: 5});

})