  $(document).ready(function() {
            // Mobile menu toggle
            $('#mobile-menu-toggle').click(function() {
                $('#mobile-menu').toggleClass('active');
            });

            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 80
                    }, 1000);
                }
            });

            // FAQ accordion
            $('.faq-button').click(function() {
                var content = $(this).next('.faq-content');
                var icon = $(this).find('svg');
                
                // Close all other FAQ items
                $('.faq-content').not(content).slideUp().addClass('hidden');
                $('.faq-button svg').not(icon).removeClass('rotate-180');
                
                // Toggle current FAQ item
                content.slideToggle().toggleClass('hidden');
                icon.toggleClass('rotate-180');
            });

            // Counter animation
            function animateCounters() {
                $('.counter').each(function() {
                    var $this = $(this);
                    var target = parseInt($this.data('target'));
                    var current = 0;
                    var increment = target / 100;
                    var timer = setInterval(function() {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        $this.text(Math.floor(current).toLocaleString());
                    }, 20);
                });
            }

            // Trigger counter animation when section is visible
            $(window).scroll(function() {
                var statisticsSection = $('#statistics');
                if (statisticsSection.length) {
                    var sectionTop = statisticsSection.offset().top;
                    var sectionHeight = statisticsSection.outerHeight();
                    var windowTop = $(window).scrollTop();
                    var windowHeight = $(window).height();
                    
                    if (windowTop + windowHeight > sectionTop && windowTop < sectionTop + sectionHeight) {
                        if (!statisticsSection.hasClass('animated')) {
                            statisticsSection.addClass('animated');
                            animateCounters();
                        }
                    }
                }
            });

            // Header background on scroll
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('header').addClass('bg-dark-card/90 backdrop-blur-md');
                } else {
                    $('header').removeClass('bg-dark-card/90 backdrop-blur-md');
                }
            });

            // Waveform animation
            function animateWaveform(waveformId) {
                var waveform = $('#' + waveformId);
                var spans = waveform.find('span');
                
                spans.each(function(index) {
                    var height = Math.random() * 80 + 20;
                    $(this).css('height', height + '%');
                });
            }

            // Animate waveforms periodically
            setInterval(function() {
                animateWaveform('waveform1');
                animateWaveform('waveform2');
            }, 1500);

            // Theme toggle (placeholder functionality)
            $('#theme-toggle, #theme-toggle-mobile').click(function() {
                // Add theme switching logic here
                console.log('Theme toggle clicked');
            });
        });