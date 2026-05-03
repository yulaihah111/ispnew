<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    private string $token;
    private string $url;
    private int $timeout;

    public function __construct()
    {
        $this->token   = config('fonnte.token', '');
        $this->url     = config('fonnte.url', 'https://api.fonnte.com/send');
        $this->timeout = (int) config('fonnte.timeout', 30);
    }

    /**
     * Kirim pesan WhatsApp ke nomor tujuan.
     *
     * @param  string  $phone  Nomor tujuan (format: 08xxx atau 628xxx)
     * @param  string  $message  Isi pesan
     * @return array ['success' => bool, 'response' => string]
     */
    public function send(string $phone, string $message): array
    {
        $phone = $this->formatPhone($phone);

        if (empty($this->token) || $this->token === 'isi_token_dari_fonnte_disini') {
            Log::warning('[WhatsApp] Token Fonnte belum dikonfigurasi. Pesan tidak terkirim.', [
                'phone' => $phone,
            ]);

            return ['success' => false, 'response' => 'Token belum dikonfigurasi'];
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['Authorization' => $this->token])
                ->post($this->url, [
                    'target'  => $phone,
                    'message' => $message,
                    'delay'   => '2',
                ]);

            $body = $response->json();
            $responseText = json_encode($body);

            if ($response->successful() && isset($body['status']) && $body['status'] === true) {
                Log::info('[WhatsApp] Pesan terkirim.', ['phone' => $phone, 'response' => $responseText]);
                return ['success' => true, 'response' => $responseText];
            }

            Log::warning('[WhatsApp] Pesan gagal dikirim.', ['phone' => $phone, 'response' => $responseText]);
            return ['success' => false, 'response' => $responseText];

        } catch (\Exception $e) {
            Log::error('[WhatsApp] Exception saat kirim pesan: ' . $e->getMessage(), ['phone' => $phone]);
            return ['success' => false, 'response' => $e->getMessage()];
        }
    }

    /**
     * Format nomor HP ke format internasional Indonesia (628xxx).
     */
    public function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // hapus non-digit

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
