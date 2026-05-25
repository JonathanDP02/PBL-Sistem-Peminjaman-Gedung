<?php

namespace App\Support;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeHelper
{
    /**
     * Generate QR code and return as base64-encoded PNG string.
     */
    public static function generateBase64(string $data, int $size = 120): string
    {
        $result = Builder::create()
            ->writer(new PngWriter)
            ->writerOptions([])
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size($size)
            ->margin(4)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        return base64_encode($result->getString());
    }
}
