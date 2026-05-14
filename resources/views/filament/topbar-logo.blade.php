{{-- Centred brand logo on mobile topbar.
     The <style> block is injected with the hook so we don't depend on
     Tailwind utilities being present in Filament's compiled CSS. --}}
<style>
@media (max-width: 1023px) {
    nav.fi-topbar { position: relative !important; }
    .fi-topbar-brand-mobile { display: flex !important; }
}

/* ── Dashboard stats cards — mobile fixes ─────────────────────────────────── */
@media (max-width: 767px) {
    /* Shrink the big value number so long values like "Rs. 6,200" don't
       wrap and inflate the card height unevenly */
    .fi-wi-stats-overview-stat .fi-wi-stats-overview-stat-value {
        font-size: 1.375rem !important;
        line-height: 1.75rem !important;
        letter-spacing: -0.01em !important;
    }
    /* Slightly tighten the label and description text */
    .fi-wi-stats-overview-stat .fi-wi-stats-overview-stat-label {
        font-size: 0.75rem !important;
        line-height: 1rem !important;
    }
    .fi-wi-stats-overview-stat .fi-wi-stats-overview-stat-description {
        font-size: 0.7rem !important;
        line-height: 1rem !important;
    }
    /* Ensure all cards in a row stretch to equal height */
    .fi-wi-stats-overview-stat {
        height: 100%;
        display: flex !important;
        flex-direction: column !important;
    }
    .fi-wi-stats-overview-stat-content {
        flex: 1 !important;
    }
}
</style>
<div class="fi-topbar-brand-mobile"
     style="display:none;position:absolute;left:50%;transform:translateX(-50%);align-items:center;pointer-events:none;z-index:20">
    <img src="{{ asset('logo-text.svg') }}"
         alt="Nails by Mona"
         style="height:2rem;width:auto">
</div>
