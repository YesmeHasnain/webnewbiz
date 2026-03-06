jQuery(function ($) {
    'use strict';

    var $form = $('#wnb-generator-form');
    var $progress = $('#wnb-progress');
    var $result = $('#wnb-result');
    var $error = $('#wnb-error');
    var $bar = $('#wnb-progress-bar');
    var $step = $('#wnb-progress-step');
    var $title = $('#wnb-progress-title');
    var generating = false;

    var steps = {
        1: { pct: 10,  text: 'Installing theme...',           label: 'Theme' },
        2: { pct: 30,  text: 'Generating AI content...',      label: 'Content' },
        3: { pct: 55,  text: 'Downloading images...',         label: 'Images' },
        4: { pct: 75,  text: 'Creating pages...',             label: 'Pages' },
        5: { pct: 90,  text: 'Setting up navigation...',      label: 'Menu' },
        6: { pct: 100, text: 'Done!',                         label: 'Done' }
    };

    $form.on('submit', function (e) {
        e.preventDefault();
        if (generating) return;
        generating = true;

        // Reset UI
        $form.closest('.wnb-generator-form-card').slideUp(300);
        $error.hide();
        $result.hide();
        $progress.slideDown(300);
        setProgress(1);

        // Animate progress while waiting
        var progressInterval = setInterval(function () {
            var currentWidth = parseFloat($bar.css('width')) / $bar.parent().width() * 100;
            if (currentWidth < 85) {
                $bar.css('width', (currentWidth + 0.3) + '%');
            }
        }, 500);

        $.ajax({
            url: wnbGenerator.ajaxUrl,
            type: 'POST',
            data: $form.serialize() + '&action=wnb_generate_website',
            timeout: 300000, // 5 minutes
            success: function (response) {
                clearInterval(progressInterval);
                if (response.success) {
                    setProgress(6);
                    setTimeout(function () {
                        $progress.slideUp(300);
                        showResult(response.data);
                    }, 800);
                } else {
                    $progress.slideUp(300);
                    showError(response.data ? response.data.message : 'Unknown error occurred');
                }
                generating = false;
            },
            error: function (xhr, status, err) {
                clearInterval(progressInterval);
                $progress.slideUp(300);
                var msg = 'Request failed';
                if (status === 'timeout') {
                    msg = 'Request timed out. The AI generation may take a while — please try again.';
                } else if (xhr.responseJSON && xhr.responseJSON.data) {
                    msg = xhr.responseJSON.data.message || err;
                }
                showError(msg);
                generating = false;
            }
        });
    });

    function setProgress(stepNum) {
        var s = steps[stepNum];
        if (!s) return;
        $bar.css('width', s.pct + '%');
        $step.text(s.text);

        // Update step indicators
        $('.wnb-step').each(function () {
            var n = parseInt($(this).data('step'));
            $(this).toggleClass('active', n <= stepNum);
            $(this).toggleClass('current', n === stepNum);
        });
    }

    function showResult(data) {
        var $links = $('#wnb-result-links');
        $links.empty();

        if (data.pages && data.pages.length) {
            var html = '<div class="wnb-result-pages">';
            data.pages.forEach(function (page) {
                html += '<div class="wnb-result-page">' +
                    '<strong>' + escapeHtml(page.title) + '</strong>' +
                    '<div class="wnb-result-page-links">' +
                        '<a href="' + page.url + '" target="_blank" class="button">View Page</a> ' +
                        '<a href="' + page.edit_url + '" target="_blank" class="button button-primary">Edit with Elementor</a>' +
                    '</div>' +
                '</div>';
            });
            html += '</div>';

            if (data.home) {
                html += '<p style="margin-top:16px;"><a href="' + data.home + '" target="_blank" class="button button-hero button-primary">View Your Website</a></p>';
            }

            $links.html(html);
        }

        $result.slideDown(300);
    }

    function showError(msg) {
        $('#wnb-error-message').text(msg);
        $error.slideDown(300);
    }

    // Retry / Generate Another
    $('#wnb-retry, #wnb-generate-another').on('click', function (e) {
        e.preventDefault();
        $error.hide();
        $result.hide();
        $form.closest('.wnb-generator-form-card').slideDown(300);
    });

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
