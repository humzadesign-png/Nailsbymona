<?php

namespace App\Enums;

enum SizingCaptureMethod: string
{
    case LiveCamera      = 'live_camera';
    case Upload          = 'upload';
    case FromProfile     = 'from_profile';
    case WhatsappPending = 'whatsapp_pending';

    public function label(): string
    {
        return match($this) {
            self::LiveCamera      => 'Live camera (guided)',
            self::Upload          => 'Photo uploaded',
            self::FromProfile     => 'Saved profile used',
            self::WhatsappPending => 'Sending via WhatsApp',
        };
    }

    /** Human-readable note shown on the confirmation and tracking pages. */
    public function confirmationNote(): string
    {
        return match($this) {
            self::LiveCamera      => '✓ Sizing photos received',
            self::Upload          => '✓ Sizing photo uploaded',
            self::FromProfile     => '✓ Using saved sizing profile',
            self::WhatsappPending => '⏳ Please send your sizing photos via WhatsApp',
        };
    }
}
