/*
 * Hospital System dashboard shell: sidebar, collapsible menu groups, menu search, dark mode,
 * default data tables and a helper for live notifications. Plain JS + jQuery (already on the page).
 */
(function () {
    'use strict';

    var root = document.documentElement;
    var isRtl = root.getAttribute('dir') === 'rtl';
    var mobile = window.matchMedia('(max-width: 991.98px)');

    function store(key, value) {
        try { localStorage.setItem(key, value); } catch (e) { /* private mode: ignore */ }
    }

    /* ---------- Sidebar open / collapse ---------- */
    function toggleSidebar() {
        if (mobile.matches) {
            document.body.classList.toggle('hms-sidebar-open');
        } else {
            root.classList.toggle('hms-collapsed');
            store('hms-sidebar', root.classList.contains('hms-collapsed') ? 'collapsed' : 'open');
        }
    }

    /* ---------- Theme ---------- */
    function toggleTheme() {
        var dark = root.getAttribute('data-theme') === 'dark';
        if (dark) {
            root.removeAttribute('data-theme');
        } else {
            root.setAttribute('data-theme', 'dark');
        }
        store('hms-theme', dark ? 'light' : 'dark');
        adaptLegacyColors();
    }

    /*
     * Older pages hard-code light backgrounds (#fff, #f8f9fa, ...) and near-black text in their own
     * <style> blocks. In dark mode, swap those computed colours for the theme's; in light mode, undo it.
     * Coloured elements (badges, buttons, icons on colour) are left alone because only
     * near-white backgrounds and near-black text are touched.
     */
    function parseRgb(value) {
        var m = /rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?/.exec(value || '');
        if (!m) return null;
        return { r: +m[1], g: +m[2], b: +m[3], a: m[4] === undefined ? 1 : +m[4] };
    }
    function lum(c) { return (0.2126 * c.r + 0.7152 * c.g + 0.0722 * c.b) / 255; }

    function adaptLegacyColors() {
        var dark = root.getAttribute('data-theme') === 'dark';
        var scope = document.querySelector('.hms-content');
        if (!scope) return;

        scope.querySelectorAll('[data-hms-bg], [data-hms-fg], [data-hms-bd]').forEach(function (el) {
            if (el.hasAttribute('data-hms-bg')) { el.style.backgroundColor = el.getAttribute('data-hms-bg'); el.removeAttribute('data-hms-bg'); }
            if (el.hasAttribute('data-hms-fg')) { el.style.color = el.getAttribute('data-hms-fg'); el.removeAttribute('data-hms-fg'); }
            if (el.hasAttribute('data-hms-bd')) {
                ['Top', 'Right', 'Bottom', 'Left'].forEach(function (s) { el.style['border' + s + 'Color'] = ''; });
                if (el.getAttribute('data-hms-bd')) el.style.borderColor = el.getAttribute('data-hms-bd');
                el.removeAttribute('data-hms-bd');
            }
        });
        if (!dark) return;

        scope.querySelectorAll('*').forEach(function (el) {
            if (el.closest('.btn, .badge, .alert, svg, .flatpickr-calendar, .hms-keep-colors')) return;
            var cs = getComputedStyle(el);
            var bg = parseRgb(cs.backgroundColor);
            if (bg && bg.a > 0.5 && lum(bg) > 0.88) {
                el.setAttribute('data-hms-bg', el.style.backgroundColor || '');
                el.style.backgroundColor = lum(bg) > 0.97 ? 'var(--hms-surface)' : 'var(--hms-surface-2)';
            }
            // Light borders only, side by side, so coloured accent borders keep their colour.
            ['Top', 'Right', 'Bottom', 'Left'].forEach(function (side) {
                var c = parseRgb(cs['border' + side + 'Color']);
                if (c && parseFloat(cs['border' + side + 'Width']) > 0 && c.a > 0.3 && lum(c) > 0.82) {
                    if (!el.hasAttribute('data-hms-bd')) el.setAttribute('data-hms-bd', el.style.borderColor || '');
                    el.style['border' + side + 'Color'] = 'var(--hms-border)';
                }
            });
            var fg = parseRgb(cs.color);
            if (fg && lum(fg) < 0.35 && el.childNodes.length) {
                el.setAttribute('data-hms-fg', el.style.color || '');
                el.style.color = lum(fg) < 0.2 ? 'var(--hms-text)' : 'var(--hms-text-2)';
            }
        });
    }

    var adaptTimer = null;
    function scheduleAdapt() {
        if (root.getAttribute('data-theme') !== 'dark') return;
        clearTimeout(adaptTimer);
        adaptTimer = setTimeout(adaptLegacyColors, 120);
    }

    document.addEventListener('click', function (e) {
        var t = e.target.closest('[data-hms-toggle]');
        if (!t) return;
        e.preventDefault();
        if (t.getAttribute('data-hms-toggle') === 'sidebar') toggleSidebar();
        if (t.getAttribute('data-hms-toggle') === 'theme') toggleTheme();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') document.body.classList.remove('hms-sidebar-open');
    });

    /* ---------- Menu groups (markup: li.slide > a[data-toggle=slide] + ul.slide-menu) ---------- */
    function setOpen(li, open) {
        li.classList.toggle('is-expanded', open);
        var link = li.querySelector(':scope > [data-toggle="slide"]');
        if (link) link.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    function initMenu() {
        var nav = document.querySelector('.hms-nav');
        if (!nav) return;

        nav.addEventListener('click', function (e) {
            var link = e.target.closest('[data-toggle="slide"]');
            if (!link || !nav.contains(link)) return;
            e.preventDefault();
            var li = link.parentElement;
            var open = !li.classList.contains('is-expanded');
            // keep one group open at a time
            Array.prototype.forEach.call(li.parentElement.children, function (sib) {
                if (sib !== li && sib.classList.contains('slide')) setOpen(sib, false);
            });
            setOpen(li, open);
        });

        // Highlight the link for the current page and open its group.
        var here = window.location.href.split('#')[0].replace(/\/$/, '');
        var best = null;
        nav.querySelectorAll('a[href]:not([data-toggle="slide"])').forEach(function (a) {
            var raw = a.getAttribute('href') || '';
            // "#" and javascript: links point nowhere (the browser resolves "#" to the current page).
            if (!raw || raw.indexOf('#') !== -1 || raw.indexOf('javascript') === 0) return;
            var href = a.href.split('#')[0].replace(/\/$/, '');
            if (here === href || here.indexOf(href + '/') === 0 || here.indexOf(href + '?') === 0) {
                if (!best || href.length > best.href.length) best = a;
            }
        });
        if (best) {
            best.classList.add('active');
            var group = best.closest('li.slide');
            if (group) {
                group.classList.add('active');
                if (best.closest('.slide-menu')) setOpen(group, true);
            }
        }
        // Groups the server marked as active stay open too.
        nav.querySelectorAll('li.slide.is-expanded, li.slide.active').forEach(function (li) {
            if (li.querySelector('.slide-menu')) setOpen(li, true);
        });

        // Menu search: filter items by their text.
        var search = document.getElementById('hmsMenuSearch');
        if (search) {
            search.addEventListener('input', function () {
                var q = search.value.trim().toLowerCase();
                nav.classList.toggle('is-searching', q.length > 0);
                nav.querySelectorAll('li').forEach(function (li) {
                    if (li.classList.contains('side-item-category')) {
                        li.style.display = q ? 'none' : '';
                        return;
                    }
                    var hit = !q || li.textContent.toLowerCase().indexOf(q) !== -1;
                    li.style.display = hit ? '' : 'none';
                    if (q && hit && li.querySelector('.slide-menu')) li.classList.add('is-search-open');
                    if (!q) li.classList.remove('is-search-open');
                });
            });
        }
    }

    /* ---------- Default data tables (pages mark them id="example1") ---------- */
    var dtLanguage = isRtl ? {
        search: '', searchPlaceholder: 'بحث...', lengthMenu: '_MENU_',
        info: 'عرض _START_ إلى _END_ من _TOTAL_', infoEmpty: 'لا توجد بيانات', infoFiltered: '(من أصل _MAX_)',
        zeroRecords: 'لا توجد نتائج مطابقة', emptyTable: 'لا توجد بيانات',
        paginate: { first: 'الأول', last: 'الأخير', next: 'التالي', previous: 'السابق' }
    } : { search: '', searchPlaceholder: 'Search...', lengthMenu: '_MENU_' };

    function initTables() {
        if (!window.jQuery || !jQuery.fn.DataTable) return;
        jQuery('#example, #example1, #example2').each(function () {
            if (!jQuery.fn.dataTable.isDataTable(this)) {
                jQuery(this).DataTable({ responsive: true, language: dtLanguage });
            }
        });
    }

    /* ---------- Default form widgets (pages mark inputs with these classes) ---------- */
    function initFormWidgets() {
        if (!window.jQuery) return;
        var $ = jQuery;
        if ($.fn.select2) {
            $('select.select2').not('.select2-hidden-accessible').each(function () {
                $(this).select2({ width: '100%', dir: isRtl ? 'rtl' : 'ltr', placeholder: $(this).data('placeholder') || '' });
            });
            $('select.select2-no-search').not('.select2-hidden-accessible').select2({
                width: '100%', dir: isRtl ? 'rtl' : 'ltr', minimumResultsForSearch: Infinity
            });
        }
        $('.fc-datepicker').each(function () {
            if (this._flatpickr || $(this).hasClass('hasDatepicker')) return;
            if (window.flatpickr) {
                window.flatpickr(this, { dateFormat: 'Y-m-d', locale: isRtl ? 'ar' : 'default' });
            } else if ($.fn.datepicker) {
                $(this).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true });
            }
        });
    }

    /* ---------- Live notification helper (used by Echo listeners) ---------- */
    window.hms = {
        pushNotification: function (text, time) {
            var list = document.getElementById('hmsNotificationList');
            var badge = document.getElementById('generalNotificationsCountBadge');
            if (list) {
                var empty = list.querySelector('.hms-dropdown__empty');
                if (empty) empty.remove();
                var item = document.createElement('div');
                item.className = 'hms-dropdown__item is-new';
                item.innerHTML = '<span class="hms-dropdown__icon"><i class="fas fa-file-medical"></i></span><span><span class="d-block"></span><small class="hms-dropdown__time"></small></span>';
                item.querySelector('.d-block').textContent = text;
                item.querySelector('.hms-dropdown__time').textContent = time || '';
                list.insertBefore(item, list.firstChild);
            }
            if (badge) {
                badge.textContent = (parseInt(badge.textContent, 10) || 0) + 1;
                badge.style.display = '';
            }
        }
    };

    function ready(fn) {
        if (document.readyState !== 'loading') fn(); else document.addEventListener('DOMContentLoaded', fn);
    }

    ready(function () {
        initMenu();
        // Let page scripts set up their own tables first, then fill in the defaults.
        setTimeout(function () { initTables(); initFormWidgets(); adaptLegacyColors(); }, 0);
        // Livewire and other scripts change the page later: re-check colours (dark mode only).
        var content = document.querySelector('.hms-content');
        if (content && window.MutationObserver) {
            new MutationObserver(scheduleAdapt).observe(content, { childList: true, subtree: true });
        }
    });
})();
