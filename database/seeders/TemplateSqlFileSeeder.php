<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\TemplatePage;
use App\Models\TemplatePageItem;
use Illuminate\Support\Facades\Log;

class TemplateSqlFileSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $data = json_encode([
            [
                'text' => 'Top secret',
            ],
            [
                'text' => 'Secret',
            ],
            [
                'text' => 'Confidential',
            ],
            [
                'text' => 'Normal',
            ],
        ]);
//        log::info(['data is :' => $data]);
////        template
        Template::create([
            'name' => 'Legal advice form request',
            'type' => 'form',
            'user_id' => 1,
            'icon' => 'cs-save',
        ]);
        Template::create([
            'name' => 'Litigation and request form',
            'type' => 'form',
            'user_id' => 1,
            'icon' => 'cs-save',
        ]);
        Template::create([
            'name' => 'Contract review form',
            'type' => 'form',
            'user_id' => 1,
            'icon' => 'cs-save',
        ]);
//        pages
        TemplatePage::create([
            'title' => 'advice form',
            'template_id' => 1
        ]);
        TemplatePage::create([
            'title' => 'Litigation request form',
            'template_id' => 2
        ]);
        TemplatePage::create([
            'title' => 'Contract review form',
            'template_id' => 3
        ]);
//        items
        TemplatePageItem::create([
            'label' => 'Requestor name',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '200',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Employee Id',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '200',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Department',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Legal advice subject',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Legal advice description',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Attached file',
            'type' => 'file',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'The purpose of legal advice',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Confidential legal advice',
            'type' => 'select',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-4',
            'input_type' => 'text',
            'height' => 'auto',
            'childList' => $data,
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Notes',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-4',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 1
        ]);
        TemplatePageItem::create([
            'label' => 'Requestor name ',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-4',
            'input_type' => 'text',
            'height' => '',
            'length' => '100',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Employee Id ',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '100',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Department ',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Subject',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Purpose of litigation and request',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '1000',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Details  of the lawsuit/litigation',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '1000',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Attached files support the request ',
            'type' => 'file',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'The date of the court session, if exist ',
            'type' => 'text',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '20',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Confidential Litigation and pleading ',
            'type' => 'select',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '20',
            'childList' => $data,
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Notes ',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '1000',
            'childList' => json_encode([]),
            'template_page_id' => 2
        ]);
        TemplatePageItem::create([
            'label' => 'Requestor name ',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '100',
            'childList' => json_encode([]),
            'template_page_id' => 3
        ]);
        TemplatePageItem::create([
            'label' => 'Employee Id ',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '100',
            'childList' => json_encode([]),
            'template_page_id' => 3
        ]);
        TemplatePageItem::create([
            'label' => 'Department ',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 3
        ]);
        TemplatePageItem::create([
            'label' => 'Subject',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 3
        ]);
        TemplatePageItem::create([
            'label' => 'Contract description',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'template_page_id' => 3
        ]);
        TemplatePageItem::create([
            'label' => 'Purpose of the request',
            'type' => 'radio',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([
                [
                    'text' => 'Review contract',
                ],
                [
                    'text' => 'Prepare contract',
                ],
            ]),
            'template_page_id' => 3
        ]);
        TemplatePageItem::create([
            'label' => 'Attached contract file',
            'type' => 'file',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([]),
            'template_page_id' => 3
        ]);
        TemplatePageItem::create([
            'label' => 'Notes',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '1000',
            'childList' => json_encode([]),
            'template_page_id' => 3
        ]);
    }
}
