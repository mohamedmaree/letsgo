$(".bars,.navbar-layer").click(function(){
    $(".nav-list.respon").toggleClass("navBar2open");
    $(".navbar-layer").toggleClass("navbar-layeropen");
    $(".bars").toggleClass("x-bars")
    $(".bars").css("z-index" , "99")
});

function resize()
{
    var heights = window.innerHeight;
    document.getElementById("header").style.height = heights + "px";
}
resize();
window.onresize = function() {
    resize();
};

window.addEventListener("scroll", function() {
    if (window.scrollY > 80) {
      $('#fixedHead').addClass("fixedhead")
  } else {
    $('#fixedHead').removeClass("fixedhead")
  }
});

$(document).ready(function(){
    $(".about").on("click" , function(){
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#about").offset().top
        }, 1000);
    })
})
$(document).ready(function(){
    $(".how").on("click" , function(){
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#how").offset().top
        }, 1000);
    })
})
$(document).ready(function(){
    $(".slider").on("click" , function(){
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#slider").offset().top
        }, 1000);
    })
})
$(document).ready(function(){
    $(".contact").on("click" , function(){
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#contact").offset().top
        }, 1000);
    })
})

// var myElement = document.getElementById('element_within_div');
// var topPos = myElement.offsetTop;