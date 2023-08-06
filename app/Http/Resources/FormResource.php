<?php

namespace App\Http\Resources;

use App\Models\AssignRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class FormResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $items
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
                "last_child_id" => $item->lastchild,
                "name" => $item->name,
                "user_id" => $item->user_id,
                "user" => new UserResource(User::find($item->user_id)),
                "parent" => $item->parent,
                "pages" => $item->pages,
                "organization" => $item->user->organization,
                "department" => $item->user->department,
                "assigned" => AssignFormResource::make($item->assignedRequests),
                "template" => $item->template,
                "children" => self::make($item->children),
                "parent_form" => $item->parent_form,
                "amendments" => ReturnRequestCollection::collection($item->amendmentRequest->sortByDesc('id')),
                "deleted_at" => $item->deleted_at,
                "status" => $item->status,
                "comment" => $item->comment,
//            "created_at"    => \Carbon\Carbon::parse($item->created_at)->diffForHumans(),
                "created_at" => Carbon::parse($item->created_at)->format('Y-m-d H:i'),
                "updated_at" => \Carbon\Carbon::parse($item->updated_at)->diffForHumans(),
                '_id' => self::getWall($item, 'id') ?? $item->id,
                '_created_at' => self::getWall($item, 'created_at') ?? $item->created_at->format('Y-m-d H:i'),
                '_assigned_at' => self::getWallAssignedAt($item),
            ];
        }

        return $result;
    }

    private static function getWall($item, $prop = 'id')
    {
        $form = $item;
        $value = null;
        do {
            if ($form->parent_form) {
                if ($prop === 'created_at')
                    $value = $form->parent_form->$prop->format('Y-m-d H:i') ?? null;
                else
                    $value = $form->parent_form->$prop ?? null;
                $form = $form->parent_form;
            }

        } while ($form->parent_form);

        return $value;
    }

    private static function getWallAssignedAt($item)
    {
        $form = $item;
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
