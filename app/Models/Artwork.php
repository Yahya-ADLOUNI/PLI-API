<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artwork extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "foreign_id",
        "source_id",
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getForeignId(): ?int
    {
        return $this->foreign_id;
    }

    /**
     * @param int|null $foreign_id
     */
    public function setForeignId(?int $foreign_id): void
    {
        $this->foreign_id = $foreign_id;
    }

    /**
     * @return int|null
     */
    public function getSourceId(): ?int
    {
        return $this->source_id;
    }

    /**
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * @param int|null $source_id
     */
    public function setSourceId(?int $source_id): void
    {
        $this->source_id = $source_id;
    }

    /***
     * @return BelongsToMany
     */
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Interest::class);
    }
}
