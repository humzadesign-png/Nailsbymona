<?php

/**
 * Courier tracking URL templates.
 * Replace {tracking_number} with the actual tracking number at runtime.
 * Keys match the CourierType enum values.
 */
return [
    'tcs'      => 'https://www.tcs.com.pk/track-shipment/?waybill={tracking_number}',
    'leopards' => 'https://leopardscourier.com/leopards-courier-online-tracking/?tracking_number={tracking_number}',
    'mp'       => 'https://mp.pk/tracking?trackno={tracking_number}',
    'blueex'   => 'https://blueex.pk/track.php?trackno={tracking_number}',
];
