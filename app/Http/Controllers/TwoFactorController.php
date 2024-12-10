<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

use SimpleSoftwareIO\QrCode\Facades\QrCode;


class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $google2fa  = new Google2FA();

        // generate secret key
        $secretKey  = $google2fa->generateSecretKey();

        // dd($request->user());

        // save to user
        $request->user()->update([
            'two_factor_secret' => Crypt::encrypt($secretKey),
        ]);

        // generate QR code url
        $qrCodeUrl  = $google2fa->getQRCodeUrl(
            'Fikri Company',
            $request->user()->email,
            $secretKey
        );

        // Generate QR Code using Simple QR Code
        // $qrCodeImage = base64_encode(QrCode::format('png')->size(300)->generate($qrCodeUrl));
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        $qrCodeSvg = $writer->writeString($qrCodeUrl);


        return view('auth.two-factor', [
            'qrCodeSvg' => $qrCodeSvg
            // 'qrCodeUrl' => $qrCodeUrl,
            // 'qrCodeImage' => $qrCodeImage
        ]);
    }

    public function verify(Request $request)
    {
        $google2fa = new Google2FA();

        $secretKey = Crypt::decrypt($request->user()->two_factor_secret);
        $valid = $google2fa->verifyKey($secretKey, $request->otp);

        if ($valid) {
            $request->user()->update(['two_factor_confirmed_at' => now()]);
            return redirect()->route('dashboard')->with('status', '2FA enabled!');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }
}
