<?php

use GrantHolle\Scru64Laravel\Concerns\HasScru64Ids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Schema;

class Post extends Model
{
    use HasScru64Ids;

    public $timestamps = false;

    protected $guarded = [];
}

beforeEach(function () {
    Schema::create('posts', function ($table) {
        $table->scru64();
        $table->string('title');
    });
});

it('creates the column via blueprint macros', function () {
    Schema::create('comments', function ($table) {
        $table->scru64();
        $table->foreignScru64('post_id')->constrained();
    });

    expect(Schema::getColumnType('comments', 'id'))->toBe('varchar')
        ->and(Schema::getColumnType('comments', 'post_id'))->toBe('varchar')
        ->and(collect(Schema::getForeignKeys('comments'))->pluck('foreign_table'))->toContain('posts')
        ->and(collect(Schema::getIndexes('comments'))->firstWhere('primary', true)['columns'])->toBe(['id']);
});

it('assigns a scru64 id on create', function () {
    $post = Post::create(['title' => 'Hello']);

    expect($post->id)->toMatch('/^[0-9a-z]{12}$/')
        ->and($post->getIncrementing())->toBeFalse()
        ->and($post->getKeyType())->toBe('string')
        ->and(Post::find($post->id)->title)->toBe('Hello');
});

it('generates sortable ids', function () {
    $a = Post::create(['title' => 'a']);
    $b = Post::create(['title' => 'b']);

    expect($a->id < $b->id)->toBeTrue();
});

it('keeps an explicitly set id', function () {
    $post = Post::create(['id' => '0u2pf62ji4b9', 'title' => 'x']);

    expect($post->id)->toBe('0u2pf62ji4b9');
});

it('rejects invalid ids in route binding', function () {
    (new Post)->resolveRouteBinding('not-a-scru64');
})->throws(ModelNotFoundException::class);
