<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Monolog\DateTimeImmutable;

class Source extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at ? new DateTimeImmutable($this->created_at) : null;
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at ? new DateTimeImmutable($this->updated_at) : null;
    }
}
