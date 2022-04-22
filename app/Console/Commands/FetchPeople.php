<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\People;

class FetchPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'people:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch people from SWAPI API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $page = 1;

        if ($lastPeople = People::orderBy('id', 'desc')->first()) {
            $page = ceil($lastPeople->id / 10) + 1;
        }

        $response = Http::get('https://swapi.dev/api/people?page=' . $page);

        if ($response->status() === 404) {
            $this->error('There are no more people');
            return 0;
        }

        foreach ($response->json()['results'] as $people) {
            $this->info('Fetching people ' . $people['name'] . '...');
            People::create([
                'name' => $people['name'],
                'height' => $people['height'],
                'mass' => $people['mass'],
                'hair_color' => $people['hair_color'],
                'skin_color' => $people['skin_color'],
                'eye_color' => $people['eye_color'],
                'birth_year' => $people['birth_year'],
                'gender' => $people['gender'],
            ]);
        }

        $this->info('People successfully received');

        return 0;
    }
}
