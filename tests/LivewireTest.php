<?php

namespace Rennokki\QueryCache\Test;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Rennokki\QueryCache\Test\Models\Post;

class LivewireTest extends TestCase
{
    #[DataProvider('strictModeContextProvider')]
    public function test_livewire_component_poll_doesnt_break_when_callback_is_already_set()
    {
        // See: https://github.com/renoki-co/laravel-eloquent-query-cache/issues/163
        Livewire::component('post', PostComponent::class);

        $posts = factory(Post::class, 30)->create();

        /** @var \Livewire\Testing\TestableLivewire $component */
        Livewire::test(PostComponent::class, ['post' => $posts->first()])
            ->assertOk()
            ->assertSee($posts[0]->name)
            ->call('$refresh')
            ->assertOk();
    }
}

class PostComponent extends Component
{
    public Post $post;
}
