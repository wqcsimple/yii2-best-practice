function topSearch()
{
    var s = $('#top-search').val();
    if (!s) return;

    window.location.href = 'http://www.tongguanedu.com/?s=' + encodeURI(s);
}

new Marquee(["course-list-holder","course-list-slide-box"],0,1,1200,180,20,0,0);

var teacherMQ = new Marquee(["teacher-slide-box","teacher-slide-items"],2,12,1200,350,30,2000,1000,244);
function teacherSlidePrev()
{
    if (!teacherMQ) return;

    teacherMQ.Run(3);
}

function teacherSlideNext()
{
    if (!teacherMQ) return;

    teacherMQ.Run(2);
}

new Marquee(["school-slide-box","school-slide-items","school-slide-pointer"],2,20,190,175,30,2000,1000);

var mainMQ = new Marquee(["main-slide-box","main-slide-items","","onclick"],2,0.3,720,370,30,2000,1000);
function mainSlidePrev()
{
    if (!mainMQ) return;

    mainMQ.Run(3);
}

function mainSlideNext()
{
    if (!mainMQ) return;

    mainMQ.Run(2);
}


var envMQ = new Marquee(["env-slide-box","env-slide-items","","onclick"],2,20,1200,195,30,2000,0,305);
function envSlidePrev()
{
    if (!envMQ) return;

    envMQ.Run(3);
}

function envSlideNext()
{
    if (!envMQ) return;

    envMQ.Run(2);
}



function hideNavChild()
{
    $('#main .nav-child').hide();
    $('#main .nav-child .item-holder').hide();
}

$(function(){

    $('#main .nav .top .item').mouseover(function(){
        var id = $(this).attr('id');
        id = id.substring(5);
        
        hideNavChild();
        $('#main .nav-child').show();
        $('#iitem-' + id).show();

    });
    $('#main .nav .head').mouseenter(hideNavChild);
    $('#main .nav .detector').mouseenter(hideNavChild);
    $('#main').mouseleave(hideNavChild);
    $('#main .nav-child').mouseleave(hideNavChild);


    $('#nav-item-qr').mouseenter(function(){
        hideNavChild();
        $('#main .nav-child').show();
        $('#iitem-qr').show();
    });
    // $('#iitem-qr').mouseleave(hideNavChild);
    $('#nav-item-school').mouseenter(hideNavChild);

    $('#content .cright .tab-header .th').mouseenter(function(){
        var id = $(this).attr('id');
        id = id.substring(17);

        $('#content .cright .tab-content .section').hide();
        $('#content-right-' + id).show();

        $('#content .cright .tab-header .th').removeClass('on');
        $(this).addClass('on');
    });
}); 