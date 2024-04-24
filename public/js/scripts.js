(function () {

    // Start Loader

    $(window).on('load',function () {

        $("body").css("overflow-y", "auto");

        $(".img-loads").fadeOut();

    });

    // Start Country Select

    $('#basic').flagStrap({
        placeholder: {
            value: "",
            text: "رمز المدينة"
        },
        countries: {
            "SA": "السعودية",
            "eg": "مصر",
            "US": "أمريكا"
        },
        labelMargin: "10px",
        scrollable: false,
        scrollableHeight: "350px"
    });

    // Upload File 

    $('.image-uploader').on("change",function() 
    { 
        var fileVal = $('.image-uploader').val(); 

        if(fileVal == ' ') { 

            console.log('input file is empty');

        } else {
            
            $(this).parent().find('i:last-of-type').removeClass('fa-camera').addClass('fa-check');
            console.log('input file is full');

         }
   

    }); 

    
})(jQuery);

function goBack() {
    window.history.back();
}

