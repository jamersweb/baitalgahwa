// Bait Al Gahwa — light theme JavaScript (no heavy dependencies).
// @package   theme_baitalgahwa

define(['jquery'], function($) {
    'use strict';

    /**
     * Course cards: third line is course end date (default) or last access when the site is configured
     * for that mode and timeaccess is present in the card payload (often requires a privacy review).
     */
    var formatBaitInstructorChipDates = function() {
        var mode = 'end';
        var hint = '';
        if (document.body) {
            mode = document.body.getAttribute('data-bag-instructor-line') || 'end';
            hint = document.body.getAttribute('data-bag-instructor-hint') || '';
        }
        var nodes = document.querySelectorAll('.baitalgahwa-mycourse-card .baitalgahwa-instructor-chip__date');
        for (var i = 0; i < nodes.length; i++) {
            var el = nodes[i];
            if (el.getAttribute('data-bagdate-applied') === '1') {
                continue;
            }
            if (hint) {
                el.setAttribute('title', hint);
            }
            var raw = (mode === 'lastaccess') ?
                el.getAttribute('data-bag-timeaccess') :
                el.getAttribute('data-bag-enddate');
            if (raw === null || raw === undefined || raw === '' || raw === '0' || raw === '[[enddate]]' || raw === '[[timeaccess]]') {
                el.setAttribute('data-bagdate-applied', '1');
                continue;
            }
            var ts = parseInt(raw, 10);
            if (isNaN(ts) || ts < 1) {
                el.setAttribute('data-bagdate-applied', '1');
                continue;
            }
            var d = new Date(ts * 1000);
            if (isNaN(d.getTime())) {
                el.setAttribute('data-bagdate-applied', '1');
                continue;
            }
            try {
                el.textContent = d.toLocaleDateString(undefined, {day: 'numeric', month: 'long', year: 'numeric'});
            } catch (e) {
                // keep default string
            }
            el.setAttribute('data-bagdate-applied', '1');
        }
    };

    var observeInstructorChipRegion = function() {
        var target = document.getElementById('block-region-content') || document.getElementById('region-main') || document.body;
        if (typeof window.MutationObserver === 'undefined') {
            return;
        }
        var obs = new window.MutationObserver(function() {
            formatBaitInstructorChipDates();
        });
        obs.observe(target, {childList: true, subtree: true});
    };

    var init = function() {
        document.documentElement.classList.add('baitalgahwa-js');
        formatBaitInstructorChipDates();
        observeInstructorChipRegion();
        $('.baitalgahwa-course a.stretched-link').each(function() {
            var t = $(this).text().trim();
            if (t) {
                $(this).attr('title', t);
            }
        });

        // My overview: tab-style grouping (theme) — core only re-renders course list, not the nav.
        $(document).on('click', '.baitalgahwa-mycourse-grouping a[data-filter="grouping"]', function() {
            var $link = $(this);
            window.setTimeout(function() {
                var $tabs = $link.closest('ul.baitalgahwa-mycourse-grouping__tabs');
                if (!$tabs.length) {
                    return;
                }
                $tabs.find('a.nav-link').removeClass('active').attr('aria-selected', 'false');
                $link.addClass('active').attr('aria-selected', 'true');
            }, 0);
        });
    };

    return {
        init: init
    };
});
