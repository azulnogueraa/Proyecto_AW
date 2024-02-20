window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.querySelector(".topbar").style.backgroundColor = "#111";
    } else {
        document.querySelector(".topbar").style.backgroundColor = "#333";
    }
}