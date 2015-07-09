(function($) {
    $.fn.magnetic = function() {
        return this.each(function() {
            var magnet = $(this);
            var metal = $('.metal');
            var top = magnet.position().top;
            var magnetBottom = top + magnet.outerHeight();
            var metalTop = $('.metal').position().top;
            var scrollTrigger = metalTop - magnetBottom;

            var magnetBorderRule = magnet.css("border-radius");
            var metalBorderRule = metal.css("border-radius");

            $(window).scroll(function() {
                if ($(window).scrollTop() >= scrollTrigger) {
                    //Collect a css property
                    magnet.css('border-radius', '0px');
                    metal.css('border-radius' , '10px 0px 10px 10px');
                    $('.metal').css({
                        'position': 'fixed',
                        'top': magnetBottom + "px"
                    });

                } else {
                    magnet.css("border-radius", magnetBorderRule);
                    metal.css("border-radius", metalBorderRule);
                    $('.metal').css({
                        'position': 'absolute',
                        'top': metalTop
                    })
                }
            })
        });
    }
})(jQuery)