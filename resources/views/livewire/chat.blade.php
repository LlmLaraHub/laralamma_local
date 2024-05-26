<?php

use Livewire\Volt\Component;

new class extends Component {

    public \App\Livewire\Forms\ChatInputForm $form;

    /**
     * @var array $messages
     */
    public array $messages = [];

    public bool $running = false;

    public function send()
     {
         $this->running = true;

         $this->validate();

         $this->messages[] = [
             'content' => $this->form->input,
             'role' => 'user'
         ];

         \Illuminate\Support\Facades\Log::info("Input", [
             'input' => $this->form->input
         ]);

         /** @var \App\Services\LlmServices\Responses\CompletionResponse $results */
         $results = \App\Services\LlmServices\LlmDriverFacade::driver(
             config('llmdriver.driver')
         )->completion($this->form->input);

         $this->messages[] = [
             'content' => $results->content,
             'role' => 'assistant'
         ];

         $this->running = false;

         $this->form->reset();

     }
} ?>

<div>
    <div>
        <h1
            class="flex justify-start items-center gap-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>

            Chat</h1>
    </div>
    <div class="border border-secondary shadow-lg p-10 rounded mt-5">

        <div class="h-[300px] overflow-y-scroll">
            @foreach($messages as $index => $message)
                @if($index % 2 === 0)
                <div class="chat chat-start">
                    <div class="chat-bubble">{{ $message['content'] }}</div>
                </div>
                @else
                <div class="chat chat-end">
                    <div class="chat-bubble">
                        <x-markdown>{!! $message['content'] !!}</x-markdown>
                    </div>
                </div>
                @endif
            @endforeach

            <div class="w-full" wire:loading>
                <div class="chat chat-end ">
                    <div class="chat-bubble w-3/4 p-5">
                        <div class="flex flex-col gap-4 w-full">
                            <div class="skeleton h-4 w-full"></div>
                            <div class="skeleton h-4 w-1/2"></div>
                            <div class="skeleton h-4 w-full"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <form wire:submit="send">

            <div class="w-full">

                <label class="form-control">
                    <div class="label">
                        <span class="label-text">Chat Input</span>
                    </div>
                    <textarea
                        wire:model="form.input" class="textarea textarea-bordered w-full" placeholder="Say Hello"></textarea>
                    @error('form.input') <span class="error">{{ $message }}</span> @enderror
                    <div class="label">
                        <span class="label-text-alt">Great way to make sure your local LLM is working</span>
                    </div>
                </label>


            </div>

            <div class="flex justify-end">
                <div wire:loading.remove class="h-10">
                    <button type="submit">Send</button>
                </div>
                <div wire:loading class="h-10">
                    <span class="loading loading-dots loading-md"></span>
                </div>
            </div>
        </form>
    </div>
</div>
