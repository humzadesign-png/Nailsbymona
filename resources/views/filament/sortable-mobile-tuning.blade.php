{{-- Mobile-friendly reorder behaviour for Filament's drag-sort tables.

     Filament v4 uses SortableJS for `reorderable('sort_order')`. The default
     config grabs the touch as soon as the user puts a finger on a row, which
     means the page can't be scrolled while a drag is in progress. On long
     lists (Products, UGC Photos, FAQs) the user can't reach off-screen
     positions and ends up trapped.

     This patch monkey-patches Sortable.create the moment it becomes available
     and injects three defaults:

       • delay: 250ms long-press required before drag activates. A quick tap
         and swipe scrolls the page like normal. A deliberate press-and-hold
         enters drag mode.
       • delayOnTouchOnly: keeps mouse drags on desktop instant. Only touch
         input pays the 250ms delay.
       • scroll + scrollSensitivity: when the dragged item nears a viewport
         edge, the page auto-scrolls so the user can drop anywhere in the list
         without needing to release first.

     `__nbm_sortable_patched` flag prevents double-wrapping if Filament's
     panel SPA navigates and re-runs this script.
--}}
<script>
(function () {
    function patchSortable() {
        if (typeof window.Sortable === 'undefined' || ! window.Sortable.create) {
            return false;
        }
        if (window.__nbm_sortable_patched) return true;

        const original = window.Sortable.create;
        window.Sortable.create = function (el, options) {
            return original.call(this, el, Object.assign({
                // Mobile: 250ms long-press before drag mode. Quick tap = scroll.
                delay:                  250,
                delayOnTouchOnly:       true,
                touchStartThreshold:    5,
                // Auto-scroll the page while dragging near top/bottom edges.
                scroll:                 true,
                scrollSensitivity:      80,
                scrollSpeed:            12,
                forceAutoScrollFallback: true,
            }, options || {}));
        };
        window.__nbm_sortable_patched = true;
        return true;
    }

    // Filament loads SortableJS lazily (only when a reorderable table is on
    // the page). Retry every 100ms for up to 5 seconds in case the table
    // isn't visible until after a Livewire interaction.
    if (! patchSortable()) {
        const interval = setInterval(function () {
            if (patchSortable()) clearInterval(interval);
        }, 100);
        setTimeout(function () { clearInterval(interval); }, 5000);
    }
})();
</script>
