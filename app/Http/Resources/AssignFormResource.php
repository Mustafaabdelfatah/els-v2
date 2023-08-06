<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignFormResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public static function make($items): array
    {
        $result = [];
        $counter = count($items);
        foreach ($items as  $item) {
            $result[] = [
                "index" => $counter--,
                "id" => $item->id,
                "date" => $item->date->format('Y-m-d'),
                "form" => $item->form,
                "assigner" => $item->form->user,
                "user" => $item->user,
                "organization" => $item->form->user->organization,
                "department" => $item->user->department,
                "template" => $item->form->template,
                "status" => $item->status,
                "amendments" => ReturnRequestCollection::collection($item->form->amendmentRequest->sortByDesc('id')),

                '_id' => self::getWall($item,'id') ?? $item->form->id,
                '_created_at' => self::getWall($item,'created_at') ?? $item->form->created_at->format('Y-m-d H:i'),

                "created_at" => self::getWallAssignedAt($item),
            ];
        }
        return $result;
    }

    private static function getWall($item , $prop = 'id')
    {
        $form = $item->form;
        $value = null;
        do {
            if ($form->parent_form) {
                if($prop === 'created_at')
                    $value = $form->parent_form->$prop->format('Y-m-d H:i') ?? null;
                else
                    $value = $form->parent_form->$prop ?? null;
                $form = $form->parent_form;
            }

        }
        while ($form->parent_form);

        return $value;
    }

    private static function getWallAssignedAt($item)
    {
        $form = $item->form;
        $value = null;
        if (count($form->assignedRequests))
            $value = $form->assignedRequests[0]->created_at->format('Y-m-d H:i');
        do {
            if ($form->parent_form) {
                $form = $form->parent_form;
                if (count($form->assignedRequests))
                    $value = $form->assignedRequests[0]->created_at->format('Y-m-d H:i') ?? null;
            }

        } while ($form->parent_form);

        return $value;
    }
}
