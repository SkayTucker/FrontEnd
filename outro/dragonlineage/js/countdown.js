/*  Change the items below to create your countdown target date and announcement once the target date and time are reached.  */
var targetDate = new Date("April 19, 2024 18:00:00 GMT-0500");

function countdown() {
    var now = new Date();
    var distance = targetDate - now;

    if (distance <= 0) {
        // Target date reached
        document.getElementById('count2').innerHTML = "Evento concluído";
        document.getElementById('count2').style.display = "inline";
        document.getElementById('count2').style.width = "390px";
        document.getElementById('dday').style.display = "none";
        document.getElementById('dhour').style.display = "none";
        document.getElementById('dmin').style.display = "none";
        document.getElementById('dsec').style.display = "none";
        document.getElementById('days').style.display = "none";
        document.getElementById('hours').style.display = "none";
        document.getElementById('minutes').style.display = "none";
        document.getElementById('seconds').style.display = "none";
        document.getElementById('spacer1').style.display = "none";
        document.getElementById('spacer2').style.display = "none";
        return;
    } else {
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('dday').innerHTML = days;
        document.getElementById('dhour').innerHTML = hours;
        document.getElementById('dmin').innerHTML = minutes;
        document.getElementById('dsec').innerHTML = seconds;

        setTimeout(countdown, 1000);
    }
}

countdown();
