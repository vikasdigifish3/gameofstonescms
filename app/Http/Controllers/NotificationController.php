<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\DeviceToken;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;
use App\Jobs\SendNotificationJob;
use MongoDB\BSON\UTCDateTime;

class NotificationController extends Controller
{
    public function showForm()
    {
        $subdomains = [
            'gamecafe', 'gamesclub', 'gamecafeiq',
            'gameterrain', 'mobilecafe', 'funzstationtv', 'beyondhealth',
            'beyondlifestyle', 'kidszone', 'joyfulkids', 'mobilebuzz', 'desirable',
            'funzstation', 'funzstations', 'funzflix', 'playhub', 'mindfullness'
        ];
        $countries = Country::all();
        sort($subdomains);
        return view('notifications.create', compact('subdomains', 'countries'));
    }


    public function sendNotification(Request $request)
    {
        Log::info('sendNotification: Processing started');

        $validatedData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'icon' => 'required|image',
            'subdomain' => 'required|string',
            'country' => 'required|string',
        ]);

        Log::info('Validation successful', $validatedData);

        $title = $validatedData['title'];
        $body = html_entity_decode($validatedData['body']);
        $body = strip_tags($body);
        $iconUrl = null; // Default value in case the icon is not uploaded
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
            $iconUrl = Storage::url($iconPath);
        }

        $subdomain = $validatedData['subdomain'];
        $countryId = $validatedData['country'];

        $query = DeviceToken::where('subdomain', $subdomain);

        if ($countryId !== 'all') {
            $query->where('country_id', $countryId);
        }

        $now = now();
        $userCategory = $request->userCategory;
        $tokens = $this->filterTokensBasedOnUserCategory($userCategory, $now, $query);
        if ($tokens->isEmpty()) {
            Log::info('sendNotification: No device tokens found', ['subdomain' => $subdomain, 'userCategory' => $userCategory]);

            DB::connection('mongodb')->collection('notification_logs')->insert([
                'title' => $title,
                'body' => $body,
                'iconUrl' => $iconUrl,
                'subdomain' => $subdomain,
                'countryId' => $countryId,
                'status' => 'failed',
                'error' => 'No device tokens found',
                'createdAt' => new UTCDateTime(now()->getTimestamp() * 1000),
            ]);

            return back()->with('error', 'No device tokens found for the selected portal.');
        }

        $portalInfo = $this->getPortalInfo($subdomain);
        Log::info('Portal information retrieved', ['portalInfo' => $portalInfo]);

        $tokens->chunk(500)->each(function ($tokenChunk) use ($title, $body, $iconUrl, $portalInfo) {
            Log::info('Notification job dispatched', ['tokenChunkSize' => count($tokenChunk)]);

            SendNotificationJob::dispatch($tokenChunk, $title, $body, $iconUrl, $portalInfo['url']);
        });

        Log::info('All notification jobs dispatched successfully.');

        // Log success to MongoDB
        DB::connection('mongodb')->collection('notification_logs')->insert([
            'title' => $title,
            'body' => $body,
            'iconUrl' => $iconUrl,
            'subdomain' => $subdomain,
            'countryId' => $countryId,
            'status' => 'sent',
            'error' => null,
            'createdAt' => new UTCDateTime(now()->getTimestamp() * 1000), // Convert to MongoDB's UTCDateTime
        ]);

        return back()->with('success', 'Notification jobs dispatched successfully!');
    }

    protected function filterTokensBasedOnUserCategory($userCategory, $now, $query)
    {
        switch ($userCategory) {
            case 'new':
                // Query MongoDB for new users
                $newUserPhones = DB::connection('mongodb')->collection('portal_activity')
                    ->where('created_at', '>=', $now->subWeek())
                    ->pluck('phone');
                $query->whereIn('phone', $newUserPhones);
                break;
            case 'frequent':
                // Query MongoDB for frequent users
                $frequentUserPhones = DB::connection('mongodb')->collection('portal_activity')
                    ->where('timestamp', '>=', $now->subMonth())
                    ->groupBy('phone')
                    ->havingRaw('COUNT(phone) >= 5')
                    ->pluck('phone');
                $query->whereIn('phone', $frequentUserPhones);
                break;
            case 'idle':
                // Query MongoDB for idle users
                $idleUserPhones = DB::connection('mongodb')->collection('portal_activity')
                    ->where('timestamp', '<=', $now->subMonth())
                    ->pluck('phone');
                $query->whereIn('phone', $idleUserPhones);
                break;
            case 'all':
            default:
                break;
        }

        return $query->pluck('token');
    }

    protected function getPortalInfo($subdomain)
    {

        $portalData = [
            'mobilecafe' => [
                'name' => 'Mobile Cafe',
                'url' => 'https://mobilecafe.gamess4u.com/',
                'icon' => 'https://mobilecafe.gamess4u.com/mobilecafe/images/header-logo.png'
            ],
            'gamecafe' => [
                'name' => 'Game Cafe',
                'url' => 'https://gamecafe.gamess4u.com/',
                'icon' => 'https://gamecafe.gamess4u.com/gamecafe/images/header-logo.png'
            ],
            'gamesclub' => [
                'name' => 'Games Club',
                'url' => 'https://gamesclub.gamess4u.com/',
                'icon' => 'https://gamesclub.gamess4u.com/gamesclub/images/header-logo.png'
            ],
            'funzstationtv' => [
                'name' => 'Funzstation TV',
                'url' => 'https://funzstationtv.gamess4u.com/',
                'icon' => 'https://funzstationtv.gamess4u.com/funzstationtv/images/header-logo.png'
            ],
            'beyondhealth' => [
                'name' => 'Beyond Health',
                'url' => 'https://beyondhealth.gamess4u.com/',
                'icon' => 'https://beyondhealth.gamess4u.com/beyondhealth/images/header-logo.png'
            ],
            'beyondlifestyle' => [
                'name' => 'Beyond Lifestyle',
                'url' => 'https://beyondlifestyle.gamess4u.com/',
                'icon' => 'https://beyondlifestyle.gamess4u.com/beyondlifestyle/images/header-logo.png'
            ],
            'kidszone' => [
                'name' => 'KidsZone',
                'url' => 'https://kidszone.gamess4u.com/',
                'icon' => 'https://kidszone.gamess4u.com/kidszone/images/header-logo.png'
            ],
            'joyfulkids' => [
                'name' => 'Joyful Kids',
                'url' => 'https://joyfulkids.gamess4u.com/',
                'icon' => 'https://joyfulkids.gamess4u.com/joyfulkids/images/header-logo.png'
            ],
            'mobilebuzz' => [
                'name' => 'Mobile Buzz',
                'url' => 'https://mobilebuzz.gamess4u.com/',
                'icon' => 'https://mobilebuzz.gamess4u.com/mobilebuzz/images/header-logo.png'
            ],
            'desirable' => [
                'name' => 'Desirable',
                'url' => 'https://desirable.gamess4u.com/',
                'icon' => 'https://desirable.gamess4u.com/desirable/images/header-logo.png'
            ],
            'funzstation' => [
                'name' => 'Funzstation',
                'url' => 'https://funzstation.gamess4u.com/',
                'icon' => 'https://funzstation.gamess4u.com/funzstation/images/header-logo.png'
            ],
            'funzstations' => [
                'name' => 'Funzstations',
                'url' => 'https://funzstations.gamess4u.com/',
                'icon' => 'https://funzstations.gamess4u.com/funzstations/images/header-logo.png'
            ],
            'funzflix' => [
                'name' => 'Funzflix',
                'url' => 'https://funzflix.gamess4u.com/',
                'icon' => 'https://funzflix.gamess4u.com/funzflix/images/header-logo.png'
            ],
            'playhub' => [
                'name' => 'Playhub',
                'url' => 'https://playhub.gamess4u.com/',
                'icon' => 'https://playhub.gamess4u.com/playhub/images/header-logo.png'
            ],
            'mindfullness' => [
                'name' => 'Mindfullness',
                'url' => 'https://mindfullness.gamess4u.com/',
                'icon' => 'https://mindfullness.gamess4u.com/mindfullness/images/header-logo.png'
            ]
        ];

        return $portalData[$subdomain] ?? $portalData['mobilecafe'];

    }
}
