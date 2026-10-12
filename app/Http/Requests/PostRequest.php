<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi dipisah dari controller (dibuat dengan: php artisan make:request PostRequest).
 * Dipakai bersama oleh store() dan update().
 */
class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // belum ada autentikasi pada Tugas 10 (autentikasi dibahas di Tugas 11)
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'body'  => ['required', 'string', 'min:10'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'body'  => 'Isi post',
            'image' => 'Gambar',
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'string'      => ':attribute harus berupa teks.',
            'title.max'   => ':attribute maksimal :max karakter.',
            'body.min'    => ':attribute minimal :min karakter.',
            'image.image' => ':attribute harus berupa file gambar.',
            'image.mimes' => ':attribute harus berformat jpg, jpeg, png, atau webp.',
            'image.max'   => 'Ukuran :attribute maksimal :max KB.',
            'image.uploaded' => ':attribute gagal diunggah (ukuran melebihi batas server).',
        ];
    }
}
