<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\User;

class UserService
{
    /**
     * @param mixed $artwork
     * @param User $user
     * @return void
     */
    public function addArtwork(mixed $artwork, User $user): void
    {
        $art = Artwork::where('foreign_id', $artwork['foreign_id'])->where('source_id', $artwork['source_id']);
        if ($art->count() == 0) $art = Artwork::create([
            'name' => $artwork['name'],
            'foreign_id' => $artwork['foreign_id'],
            'source_id' => $artwork['source_id']
        ]);
        $already = $user->artworks()->where('foreign_id', $artwork['foreign_id'])
            ->where('source_id', $artwork['source_id'])->get();
        if ($already->count() == 0) $user->artworks()->attach($art->get()->last()->id);
    }
}
