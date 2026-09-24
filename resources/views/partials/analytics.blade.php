{{-- Google Analytics 4 + Microsoft Clarity — shared by the storefront and
     checkout layouts. Also sends any events queued server-side via
     App\Support\Analytics (checkout started, order placed, …). --}}
@php($nbmQueuedEvents = \App\Support\Analytics::pull())
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZC5X3P3PT4"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-ZC5X3P3PT4');

    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window,document,"clarity","script","wrhay4fga3");

    // One helper for both tools: GA gets the full e-commerce payload,
    // Clarity gets the event name (so recordings can be filtered by it).
    window.nbmTrack = function (name, params) {
        try { gtag('event', name, params || {}); } catch (e) {}
        try { clarity('event', name); } catch (e) {}
    };

    @foreach ($nbmQueuedEvents as $e)
    nbmTrack(@js($e['name']), @js($e['params']));
    @endforeach
</script>
