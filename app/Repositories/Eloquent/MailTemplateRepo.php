<?php

namespace App\Http\Nmcs\Repositories\Eloquent;

use App\Http\Nmcs\Repositories\Interfaces\MailTemplateRepoInterface;
use App\Models\MailTemplate;


class MailTemplateRepo extends AbstractRepo implements  MailTemplateRepoInterface
{
    public function __construct()
    {
        parent::__construct(MailTemplate::class);
    }

    public function create($data)
    {
        return MailTemplate::create($data);
    }

    public function update($data, $template)
    {
        return $template->update($data);
    }

}
