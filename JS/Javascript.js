/*Make sure you keep the scroll position after reloading*/
$(window).scroll(function() { sessionStorage.scrollTop = $(this).scrollTop(); });

$(document).ready(function() {
    if (sessionStorage.scrollTop != "undefined") { $(window).scrollTop(sessionStorage.scrollTop); }
    //$('.image').css('height', $('.storeHeight').height());
    startClock();
});

//Makes a cookie
function createCookie(name, value, days)
{
    let expires = '';
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

//Reads the cookie
function readCookie(name)
{
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

//Start the clock
function startClock()
{
    let seconds = readCookie("seconds") != undefined ? readCookie("seconds") : 0;
    let minutes = readCookie("minutes") != undefined ? readCookie("minutes") : 0;
    let counter = setInterval(clock, 1000);
    //handels the clock count.
    function clock()
    {
        if(seconds >= 59) {
            seconds=0;
            minutes++;
        }
        $('.showTime').html(" | " + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2));
        seconds++;
        createCookie("seconds", (seconds + 2), 10);
        createCookie("minutes", minutes, 10);
        if($('button').length <= $('button:disabled').length) {
            clearInterval(counter);
            return;
        }
    }
}
