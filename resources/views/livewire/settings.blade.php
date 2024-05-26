<?php
use Livewire\Volt\Component;
use Livewire\Attributes\Session;
use Facades\App\Domains\Settings\DownloadOllama;
use Facades\App\Domains\Settings\SettingsRepository;
use Facades\App\Domains\Settings\CheckOllamaRunning;

new class extends Component {

    public string $path_to_ollama = "";

    #[Session]
    public array $tags = [];

    public ?\App\Models\Setting $settings = null;

    public string $package_name = "Ollama-darwin.zip";

    public bool $ollama_downloaded = false;

    public bool $checking = false;

    public string $download_path = "";

    public function mount()
    {
        $this->path_to_ollama = $this->package_name;

        $this->download_path = DownloadOllama::downloadPath($this->package_name);

        $this->settings = SettingsRepository::getSetting();

        $this->checkDownloaded();
    }

    public function checkDownloaded()
    {
        if(DownloadOllama::isDownloaded($this->package_name)) {
            $this->ollama_downloaded = true;
            SettingsRepository::updateSetting(
                field: 'ollama_downloaded',
                state: true
            );
        } else {
            $this->ollama_downloaded = false;
            SettingsRepository::updateSetting(
                field: 'ollama_downloaded',
                state: false
            );
        }
    }

    public function check()
        {
                try {
                    $this->checking = true;
                    $running = CheckOllamaRunning::isRunning();
                    if(!$running) {

                        SettingsRepository::updateSetting(
                            field: 'ollama_server_reachable',
                            state: false
                        );

                    } else {
                        SettingsRepository::updateSetting(
                            field: 'ollama_server_reachable',
                            state: true
                        );

                        $tags = CheckOllamaRunning::getTags();

                        $this->tags = $tags;
                    }

                    $this->checking = false;

                } catch (\Exception $e) {
                    $this->checking = false;
                    \Illuminate\Support\Facades\Log::info("Catching error", [
                        'error' => $e->getMessage()
                    ]);

                    SettingsRepository::updateSetting(
                        field: 'ollama_server_reachable',
                        state: false
                    );
                }
    }

    public function listModels()
    {
        $tags = CheckOllamaRunning::getTags();

        $this->tags = $tags;
    }

    public function downloadLlama3(string $model = "llama3") {
        \Native\Laravel\Facades\Notification::title('LaraLamma Local')
            ->message('Getting model ' . $model)
            ->show();

        $results = DownloadOllama::pull($model);

        if($results === true) {
            SettingsRepository::updateSetting(
                field: 'ollama_downloaded',
                state: true
            );
        } else {
            \Native\Laravel\Facades\Notification::title('LaraLamma Local')
                ->message('ERROR: ' . $results)
                ->show();

            SettingsRepository::updateSetting(
                field: 'ollama_downloaded',
                state: false
            );
        }
    }

    public function openDownloads()
    {
        \Illuminate\Support\Facades\Process::run('open ~/Downloads');
    }

    public function downloadOllama() {

        \Native\Laravel\Facades\Notification::title('LaraLamma Local')
            ->message('This may take a moment if not downloaded')
            ->show();

        $results = DownloadOllama::download();

        if($results === true) {
            SettingsRepository::updateSetting(
                field: 'ollama_downloaded',
                state: true
            );
        } else {
            \Native\Laravel\Facades\Notification::title('LaraLamma Local')
                ->message('ERROR: ' . $results)
                ->show();

            SettingsRepository::updateSetting(
                field: 'ollama_downloaded',
                state: false
            );
        }

    }
} ?>



<div>
    <div >
        <div class="lg:hidden flex">
        <h1
        class="flex justify-start items-center gap-2"
        >

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            Settings</h1>
        </div>
        <div class="card w-full bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Local Install of Ollama</h2>
                <div>
                    Ollama is a way to run LLMs (Large Language Models) locally.
                    This page will help download, run and test the local installation.
                </div>
            </div>
        </div>

        <ul class="steps w-full mt-10 mb-5">
            <li class="step step-primary">Download Ollama</li>
            <li class="step step-primary">Install Ollama</li>
            <li class="step @if($settings->ollama_server_reachable) step-secondary @endif"">Install Model</li>
            <li class="step @if($settings->ollama_server_reachable) step-secondary @endif">Check if Running</li>
        </ul>

        <div class="border border-secondary shadow-lg p-10 rounded mt-5">
            <ul>
                <li class="flex justify-start items-center gap-2">
                    @if($settings->ollama_server_reachable == true)
                        <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </span>
                        Ollama is working property!

                    @elseif($settings->ollama_server_reachable == false)
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        Ollama is not found so follow the steps below

                    @endif

                </li>
            </ul>
        </div>
        <div class="border border-secondary shadow-lg p-5 rounded mt-5">
            <div>
                Let's check your Ollama install
            </div>
            <button
                wire:loading.class="opacity-25"
                class="btn btn-active btn-secondary"
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


        @if($settings->ollama_server_reachable == false && !$ollama_downloaded)
            <div class="border border-b-gray-400 shadow-lg p-10 rounded mt-5">
                <div>
                    Since we can not reach the server lets make sure you downloaded the Ollama server,
                    downloaded the models and then started the server.
                </div>

                <button wire:click="downloadOllama" class="btn btn-active btn-neutral">download</button>

                <div wire:loading>
                    <progress class="progress w-56"></progress>
                </div>
            </div>

        @endif


        @if($ollama_downloaded == true && !$settings->ollama_server_reachable)
            <div class="border border-b-gray-400 shadow-lg p-10 rounded mt-5">
                <div>
                    The file is downloaded you can now open
                    <span class="font-bold">{{$package_name}}</span>
                    <button wire:click="openDownloads"
                    class="underline">here</button>

                    it and follow tine installation instructions
                </div>
                @if($settings->ollama_server_reachable)
                    <div>
                        <div>
                            Once installed you can click here to download your first model
                        </div>
                        <button wire:click="downloadLlama3" class="btn btn-active btn-neutral">download a model like llama3</button>
                        <div wire:loading>
                            <progress class="progress w-56"></progress>
                        </div>
                    </div>
                @else
                    <div>When it is installed and running click "Check Install"</div>
                @endif
            </div>
        @endif
    </div>



</div>
