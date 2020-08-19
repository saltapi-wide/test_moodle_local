define(
    [
        'jquery',
        'block_course_completion_counter/progressbar'
    ],
    function (
        $,
        ProgressBar
    ) {

        var flag=0;
        var init = function (max,res1, res2,res3, title1,title2,title3,title4) {

            // $( document ).ready(function() {

            $.fn.isInViewport = function() {
                var elementTop = $(this).offset().top;
                var elementBottom = elementTop + $(this).outerHeight();

                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();

                return elementBottom > viewportTop && elementTop < viewportBottom;
            };

            if (($(".container_counters").isInViewport()) && (flag==0)) {


                var min = 1/max;

                console.log(min);

                if(min == Infinity){
                    min=1;
                }

                generate_counter('#counter1','#3174b7',1,title1,max);
                generate_counter('#counter2','#bd2130',res1*min,title2,max);
                generate_counter('#counter3','#28a745',res2*min,title3,max);
                generate_counter('#counter4','#a7a650',res3*min,title4,max);

                flag=1;

            }

            $(window).on('resize scroll', function() {
                if (($(".container_counters").isInViewport()) && (flag==0)) {


                    var min = 1/max;

                    console.log(min);

                    if(min == Infinity){
                        min=1;
                    }

                    generate_counter('#counter1','#3174b7',1,title1,max);
                    generate_counter('#counter2','#bd2130',res1*min,title2,max);
                    generate_counter('#counter3','#28a745',res2*min,title3,max);
                    generate_counter('#counter4','#a7a650',res3*min,title4,max);

                    flag=1;

                }
            });


            //});
        }
        var generate_counter=function(element,color,result,title,max){


                    var circle = new ProgressBar.Circle(element, {
                        color: '#aaa',

                        // This has to be the same size as the maximum width to
                        // prevent clipping
                        strokeWidth: 4,
                        trailWidth: 1,
                        easing: 'easeInOut',
                        duration: 2000,
                        text: {
                            autoStyleContainer: false,
                            //value: '12343325235',
                            className: 'text'
                        },
                        from: { color: color, width: 4 },

                        to: { color: color, width: 4 },

                        // Set default step function for all animate calls
                        step: function(state, circle) {
                            circle.path.setAttribute('stroke', state.color);
                            circle.path.setAttribute('stroke-width', state.width);

                            var value = Math.round(circle.value() * max);
                            if (value === 0) {
                                circle.setText(value);
                            } else {
                                circle.setText(value);
                            }

                        }

                    });


            circle.text.style.fontFamily = '\"Raleway\", Helvetica, sans-serif';
            circle.text.style.fontSize = '38px';
            circle.text.style.color = 'white';
            circle.text.style.padding = '30px 0px 0px 0px';

            $(element).prepend("<p class='title-counter'>"+title+"</p>");

            circle.animate(result);

        }
        return {
            init: init
        };
    });




