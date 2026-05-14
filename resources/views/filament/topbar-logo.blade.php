{{-- Centred brand logo on mobile topbar.
     The <style> block is injected with the hook so we don't depend on
     Tailwind utilities being present in Filament's compiled CSS. --}}
<style>
@media (max-width: 1023px) {
    nav.fi-topbar { position: relative !important; }
    .fi-topbar-brand-mobile { display: flex !important; }
}
</style>
<div class="fi-topbar-brand-mobile"
     style="display:none;position:absolute;left:50%;transform:translateX(-50%);align-items:center;pointer-events:none;z-index:20">
    <img src="{{ asset('logo-text.svg') }}"
         alt="Nails by Mona"
         style="height:3.5rem;width:auto">
</div>
