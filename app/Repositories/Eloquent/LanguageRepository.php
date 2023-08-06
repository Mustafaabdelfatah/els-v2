<?php


namespace App\Repositories\Eloquent;

use App\Models\Language;
use App\Repositories\Contracts\ILanguage;


class LanguageRepository extends BaseRepository implements ILanguage
{
    public function model()
    {
        return Language::class;
    }
}
