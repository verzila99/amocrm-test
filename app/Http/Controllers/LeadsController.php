<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AmoCRM\Client\AmoCRMApiClient;
use App\Actions\TokenActions;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Client\Token\AccessToken;

class LeadsController extends Controller
{

    public function store()
    {
        $token_file = dirname(__DIR__, 3) . '/' . 'token_info.json';

        $apiClient = new AmoCRMApiClient(env('AMO_CLIENT_ID', ''), env('AMO_SECRET', ''), env('AMO_REDIRECT_URL', ''));
        $apiClient->setAccountBaseDomain(env('AMO_BASE_DOMAIN'));

        $accessToken = TokenActions::getToken($apiClient, $token_file);

        $apiClient->setAccessToken($accessToken);

        $leadsService = $apiClient->leads();
        try {
            $leads = $leadsService->get(null, ['catalog_elements', 'is_price_modified_by_robot', 'loss_reason', 'contacts', 'source_id']);
        } catch (\Exception $e) {
            die((string) $e);
        }


        foreach ($leads->all() as $key => $value) {
            $lead = $value->toArray();
            try {
                foreach ($value->contacts->all() as $key => $val) {
                    $contacts[] = $apiClient->contacts()->getOne($val->id)->toArray();
                }
                $company = $apiClient->companies()->getOne($value->company->getId())->toArray();
            } catch (\Exception $e) {
                die((string) $e);
            }
            DB::transaction(function () use ($lead, $company, $contacts) {

                DB::table('leads')->upsert([
                    'id' => $lead['id'],
                    "name" => $lead['name'],
                    "price" => $lead['price'],
                    "responsible_user_id" => $lead['responsible_user_id'],
                    "group_id" => $lead['group_id'],
                    "status_id" => $lead['status_id'],
                    "pipeline_id" => $lead['pipeline_id'],
                    "loss_reason_id" => $lead['loss_reason_id'],
                    "source_id" => $lead['source_id'],
                    "created_by" => $lead['created_by'],
                    "updated_by" => $lead['updated_by'],
                    "created_at" => $lead['created_at'],
                    "updated_at" => $lead['updated_at'],
                    "closed_at" => $lead['closed_at'],
                    "closest_task_at" => $lead['closest_task_at'],
                    "is_deleted" => $lead['is_deleted'],
                    "custom_fields_values" => json_encode($lead['custom_fields_values']),
                    "score" => $lead['score'],
                    "account_id" => $lead['account_id'],
                    "is_price_modified_by_robot" => $lead['is_price_modified_by_robot'],
                    "contacts" => json_encode($contacts),
                    "company" => json_encode($company),
                ], 'id', ['name', 'price', 'group_id', 'status_id', 'pipeline_id', 'loss_reason_id', 'source_id', 'updated_by', 'updated_at', 'closest_task_at', 'is_deleted', 'custom_fields_values', 'score', 'account_id', 'is_price_modified_by_robot', 'contacts', 'company']);

            });

        }

        return redirect()->action([LeadsController::class, 'index']);
    }
    public function index()
    {

        $leads = DB::table('leads')->get()->all();

        return view('leads', ['leads' => $leads]);
    }
}