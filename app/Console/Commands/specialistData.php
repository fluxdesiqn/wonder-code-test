<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests;
use GuzzleHttp\Client;
use App\Specialist;
use App\Hospital;

class specialistData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:specialists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data for specialists from hospitals XML.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $checkForFirstSchedule = Hospital::get();

        if(count($checkForFirstSchedule) > 0) {
            $hospitals = $checkForFirstSchedule;
        } else {
            $hospitals = [
                0 => [
                    'name' => 'Auckland Surgical Centre',
                    'location' => 'Auckland',
                    'xml' => 'https://www.healthpoint.co.nz/auckland-surgical-centre.xml'
                ],
                1 => [
                    'name' => 'Gillies Hospital',
                    'location' => 'Auckland',
                    'xml' => 'https://www.healthpoint.co.nz/gillies-hospital-clinic.xml'
                ],
                2 => [
                    'name' => 'Southern Cross Hospital - Brightside',
                    'location' => 'Auckland',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-brightside.xml'
                ],
                3 => [
                    'name' => 'Southern Cross Hospital - Christchurch',
                    'location' => 'Christchurch',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-christchurch.xml'
                ],
                4 => [
                    'name' => 'Southern Cross Hospital - Hamilton',
                    'location' => 'Hamilton',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-hamilton.xml'
                ],
                5 => [
                    'name' => 'Southern Cross Hospital - Invercargill',
                    'location' => 'Invercargill',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-invercargill.xml'
                ],
                6 => [
                    'name' => 'Southern Cross Hospital - New Plymouth',
                    'location' => 'New Plymouth',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-new-plymouth.xml'
                ],
                7 => [
                    'name' => 'Southern Cross Hospital - North Harbour',
                    'location' => 'Auckland',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-north-harbour.xml'
                ],
                8 => [
                    'name' => 'Southern Cross Hospital - Rotorua',
                    'location' => 'Rotorua',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-invercargill.xml'
                ],
                9 => [
                    'name' => 'Southern Cross Hospital - Wellington',
                    'location' => 'Wellington',
                    'xml' => 'https://www.healthpoint.co.nz/southern-cross-hospital-wellington.xml'
                ]
            ];
        }

        // Loop through hospitals
        foreach($hospitals as $hospital) {

            // Get xml data
            $guzzleClient = new Client();
            $response = $guzzleClient->get($hospital['xml']);
            
            if($response->getStatusCode() !== 200) {
                dd('here');
                continue;
            }

            // Setup xml file
            $body = $response->getBody();
            $body->seek(0);
            $size = $body->getSize();
            $file = $body->read($size);
            
            // Store xml file locally
            $fileLocation = '/xml/' . Str::slug($hospital['name']) . '.xml';
            Storage::put( $fileLocation , $file);

            // Get data from local xml
            $localFile = Storage::get($fileLocation);
            $parsedFile = simplexml_load_string($localFile);

            // Store hospital data
            $hospitalData = [
                'name' => $hospital['name'],
                'location' => $hospital['location'],
                'type' => $parsedFile['type']->__toString(),
                'href' => $parsedFile['href']->__toString(),
                'logo' => $parsedFile['logo']->__toString(),
                'xml'  => $hospital['xml']
            ];

            // Update hospital data or create new one
            $currentHospital = Hospital::where('href', $hospitalData['href'])->first();

            if(!$currentHospital) {
                $currentHospital = Hospital::create($hospitalData);
            }

            // Check for service data
            if($parsedFile->services && $parsedFile->services->count() == 0) {
                continue;
            }

            // Look through specialists
            foreach($parsedFile->services->children() as $service) {

                // Service xml link and service name
                $serviceDataSrc = $service['src']->__toString();
                $serviceDataName = $service['name']->__toString();
                
                // Get service xml data
                $serviceGuzzleClient = new Client();
                $serviceResponse = $serviceGuzzleClient->get($serviceDataSrc);

                if($serviceResponse->getStatusCode() !== 200) {
                    continue;
                }

                // Setup service xml file
                $serviceBody = $serviceResponse->getBody();
                $serviceBody->seek(0);
                $serviceSize = $serviceBody->getSize();
                $serviceFile = $serviceBody->read($serviceSize);
                
                // Store service xml file locally
                $serviceFileLocation = '/xml/service/' . Str::slug($serviceDataName) . '.xml';
                Storage::put($serviceFileLocation , $serviceFile);

                // Get service data from local xml
                $serviceLocalFile = Storage::get($serviceFileLocation);
                $serviceParsedFile = simplexml_load_string($serviceLocalFile);

                // Check for service data
                if($serviceParsedFile->people->count() == 0) {
                    continue;
                }
                // Loop through specialists
                foreach($serviceParsedFile->people->children() as $specialist) {
                    dd($specialist);
                    // Store specialist data
                    $specialistData = [
                        'name' => $specialist['name']->__toString(),
                        'src' => $specialist['src']->__toString(),
                        'title' => $specialist['title']->__toString(),
                        'hospital_id' => $currentHospital->id
                    ];
                    
                    // Update specialist data or create new one
                    $currentSpecialist = Specialist::where('src', $specialistData['src'])->first();

                    if(!$currentSpecialist) {
                        $currentSpecialist = Specialist::create($specialistData);
                    }
                }
            }
        }
    }
}
