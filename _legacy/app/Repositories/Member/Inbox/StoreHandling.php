<?php

/**
 * @author Tim Pengembang <arbisyarifudin@gmail.com>
 */

namespace App\Repositories\Member\Inbox;

use App\Models\Inbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Store Inbox Data.
 */
class StoreHandling
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request  = $request;
  }

  public function validate()
  {
    $rules = [
      'subject' => [
        'required',
      ],
      'category' => [
        'required',
      ],
      'fullname' => [
        'required',
        'min:3',
      ],
      'nisn' => [
        'required',
        'min:5',
      ],
      'email' => [
        'required',
        'email',
      ],
      'message' => [
        'required',
        'min:10',
      ],
      'recaptchaToken' => [
        'nullable',
        'string',
      ],
    ];

    $messages = [
      'subject.required' => 'Subjek pesan wajib diisi.',
      'category.required' => 'Kategori pesan wajib diisi.',
      'fullname.required' => 'Nama Lengkap wajib diisi.',
      'fullname.min' => 'Nama Lengkap terlalu pendek.',
      'nisn.required' => 'NISN wajib diisi.',
      'email.required' => 'Email wajib diisi.',
      'message.required' => 'Pesan wajib diisi.',
      'message.min' => 'Pesan terlalu pendek.',
    ];

    $validated = Validator::make($this->request->all(), $rules, $messages)->validate();
    return $validated;
  }

  public function handle()
  {
    $validated = $this->validate();

    // google recaptcha check
    if (isset($validated['recaptchaToken']) && !empty($validated['recaptchaToken'])) {
      $captchVerified = $this->__verifyCaptcha(@$validated['recaptchaToken']);
      if (!$captchVerified) {
        throw new \Exception('Ups! Anda terindikasi sebagai spam oleh sistem kami.', 403);
      }
    }

    unset($validated['recaptchaToken']);
    $data = Inbox::create($validated);
    $data['message'] = 'Inbox stored successfully!';
    return $data;
  }

  private function __verifyCaptcha($captcha = null)
  {
    if (app()->environment('local') || env('APP_ENV') === 'local' || env('APP_DEBUG', true)) {
      return true;
    }

    if (!$captcha) return false;

    $secret = env('GOOGLE_RECAPTCHA');
    if (empty($secret) || str_contains($secret, 'XXXX')) {
      return true;
    }

    try {
      $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
      $response = @file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $remoteIp
      );
      if (!$response) return true;
      $response = json_decode($response);
      if (!$response || @$response->success == false) return false;
      if (@$response->success == true && (@$response->score ?? 1) <= 0.5) {
        return false;
      }
      return true;
    } catch (\Throwable $e) {
      return true;
    }
  }
}
