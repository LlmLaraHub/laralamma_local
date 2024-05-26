<?php
use Livewire\Volt\Component;

new class extends Component {

    public string $path_to_ollama = "";

    public array $tags = [];

    public \App\Models\Setting $settings;

    public string $package_name = "Ollama-darwin.zip";

    public bool $ollama_binary_downloaded = false;

    public function mount()
    {
        $this->path_to_ollama = storage_path($this->package_name);

        $this->settings = \App\Models\Setting::first();

        if(!$this->settings) {
            $this->settings = \App\Models\Setting::create();
        }

        if(\Illuminate\Support\Facades\File::exists(storage_path($this->package_name))) {
            $this->ollama_binary_downloaded = true;
        }
    }

    public function check()
        {
            $response = \Illuminate\Support\Facades\Http::get("http://localhost:11434/api/tags");

            \Illuminate\Support\Facades\Log::info("Response", [
                'info' => $response->status()
            ]);

            if(!$response->ok()) {
                $this->settings->update([
                    'ollama_server_reachable' => false
                ]);

            } else {
                $this->settings->update([
                    'ollama_server_reachable' => true
                ]);

                $this->tags = $response->json();
            }
    }

    public function listModels()
    {
        $response = \Illuminate\Support\Facades\Http::get("http://localhost:11434/api/tags");

        $this->tags = $response->json();
    }

    public function downloadLlama3() {
        \Illuminate\Support\Facades\Log::info("Going to download Llama3");

        $url = 'https://ollama.com/download/Ollama-darwin.zip';

        try {
            \Illuminate\Support\Facades\Process::run('ollama pull llama3');

            $this->setting->update([
                'ollama_completion_model' => 'llama3'
            ]);

            \Illuminate\Support\Facades\Log::info("Downloaded");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("An error occurred while downloading: " . $e->getMessage());
        }
    }

    public function downloadOllama() {
        \Illuminate\Support\Facades\Log::info("Going to download");

        $url = 'https://ollama.com/download/Ollama-darwin.zip';

        try {
            $response = \Illuminate\Support\Facades\Http::get($url);

            if ($response->ok()) {
                \Illuminate\Support\Facades\File::put(storage_path($this->package_name), $response->body());
                \Illuminate\Support\Facades\Log::info("File downloaded successfully");
                $this->ollama_download = true;
            } else {
                \Illuminate\Support\Facades\Log::error("Failed to download file, HTTP status code: " . $response->status());
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("An error occurred while downloading: " . $e->getMessage());
        }
    }
} ?>



<div>
    <div >
        <h1
        class="flex justify-start items-center gap-2"
        >

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            Settings</h1>

        <div class="border border-secondary shadow-lg p-10 rounded mt-5">
            <ul>
                <li class="flex justify-start items-center gap-2">
                    @if($settings->ollama_server_reachable == true)
                        <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </span>
                    @elseif($settings->ollama_server_reachable == false)
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                    @endif
                    Ollama Can be Reached
                </li>
            </ul>
        </div>
        <div class="border border-secondary shadow-lg p-5 rounded mt-5">
            <div>
                Let's check your Ollama install
            </div>
            <button
                class="btn btn-active btn-secondary "
                wire:click="check">Check Install</button>


            <div>
                @if(count($tags) === 0 )
                    No models yet or not checked.
                @else
                    <div class="overflow-x-auto mt-10 border-secondary">
                        <table class="table table-zebra border">
                            <!-- head -->
                            <thead>
                            <tr>
                                <th></th>
                                <th>Model Name</th>
                                <th>Date</th>
                                <th>Size</th>
                                <th>parameter_size</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach(data_get($tags, 'models', []) as $tag)
                                <tr>
                                    <th></th>
                                    <td>{{ $tag['name'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($tag['modified_at'])->format("Y-m-d") }}</td>
                                    <td>{{  number_format($tag['size'] / 1073741824, 1) . ' GB' }}</td>
                                    <td>{{ data_get($tag, 'details.parameter_size') }}</td>
                                </tr>
                            @endforeach
                            <!-- row 1 -->
                            </tbody>
                        </table>
                    </div>


                @endif
            </div>


        </div>


        @if($settings->ollama_server_reachable == false)
            <div class="border border-b-gray-400 shadow-lg p-10 rounded mt-5">
                <div>
                    Since we can not reach the server lets make sure you downloaded the Ollama server,
                    downloaded the models and then started the server.
                </div>

                <button wire:click="downloadOllama" class="btn btn-active btn-neutral">download</button>
            </div>

        @endif


        @if($ollama_binary_downloaded == true && !$settings->ollama_server_reachable)
            <div class="border border-b-gray-400 shadow-lg p-10 rounded mt-5">
                <div>
                    The file is downloaded you can now open it and follow tine installation instructions
                </div>
                <div>
                    Once installed you can click here to download your first model
                </div>
                <button wire:click="downloadLlama3" class="btn btn-active btn-neutral">download llama3</button>
            </div>
        @endif
    </div>



</div>
