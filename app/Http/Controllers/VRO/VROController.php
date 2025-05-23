<?php

namespace App\Http\Controllers\VRO;

use App\Helpers\BusinessConnection;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessWithACI;
use App\Models\CompetitorShopBusiness;
use App\Models\ShopInformation;
use App\Services\ImageBase64Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VROController extends Controller
{
    public function getSupportingData(){
        $business = Business::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data get successfully!',
            'data' => $business,
        ],200);

    }
    public function getBusinessWiseCustomerInformation(Request $request){
        $businessCode = $request->input('business');
        $customerCode = $request->input('customerCode');

        try {
            $db = BusinessConnection::getConnectionName($businessCode);
            $results = DB::connection($db)->select("select * from Customer where CustomerCode = '$customerCode' AND Active='Y'");

            if(!empty($results[0])){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data get successfully!',
                    'data' => $results[0],
                ],200);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found!',
                ],404);
            }



        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }
    public function storeShopInformation(Request $request){
        DB::beginTransaction();
        try{
            $singleShopInfo = $request->all();
            if (!empty($singleShopInfo['Business'])
                &&!empty($singleShopInfo['CustomerCode'])
                &&!empty($singleShopInfo['OwnerShip'])
                && !empty($singleShopInfo['Condition'])
                && !empty($singleShopInfo['DeedAgreement'])
                && !empty($singleShopInfo['Latitude'])
                && !empty($singleShopInfo['Longitude'])
                && !empty($singleShopInfo['ShopAddress'])
                && !empty($singleShopInfo['ShopPhoto'])
                && !empty($singleShopInfo['CustomerReputation'])
                && !empty($singleShopInfo['PaymentBehaviour'])
                && !empty($singleShopInfo['ModeOfPayment'])
                && !empty($singleShopInfo['YearlyPurchasePotential'])
                && !empty($singleShopInfo['PaymentTermsInDays'])
                && !empty($singleShopInfo['BalancePerCustomer'])
                && !empty($singleShopInfo['CustomerProposedCreditLimit'])
                && !empty($singleShopInfo['RepresentativeComment'])
                && !empty($singleShopInfo['RepresentativePhoto'])
                && !empty($singleShopInfo['RepresentativePhoto'])
            ){

                //BussinessWiseCustomerInformation
                $db = BusinessConnection::getConnectionName($singleShopInfo['Business']);
                $customerCode = ($singleShopInfo['CustomerCode']);
                $results = DB::connection($db)->select("select * from Customer where CustomerCode = '$customerCode' AND Active='Y'");

                if(!empty($results[0])){
                    $existingShopInformation = $results[0];
                }

                // Create a new record for each field
                $shop = new ShopInformation();
                $shop->AssignVROStaffId = Auth::user()->UserID;
                $shop->Business =$singleShopInfo['Business'] ;
                $shop->CustomerName = $existingShopInformation->CustomerName;
                $shop->CustomerCode = $singleShopInfo['CustomerCode'];
                $shop->CustomerContactPersonName = $existingShopInformation->ContactPerson ?? null;
                $shop->CustomerContactPersonDesignation = $singleShopInfo['CustomerContactPersonDesignation'] ?? null;
                $shop->CustomerProprietorName = $singleShopInfo['CustomerProprietorName'] ?? null;
                $shop->CustomerMobileNo = $existingShopInformation->Mobile ?? null;
                $shop->CustomerMobileNoTwo = $existingShopInformation->Mobile ?? null;
                $shop->CustomerAddress = $existingShopInformation->Add1 ?? null;
                $shop->OwnerShip = $singleShopInfo['OwnerShip'] ?? null;

                $shop->Condition = $singleShopInfo['Condition'] ?? null;
                $shop->DeedAgreement = $singleShopInfo['DeedAgreement'] ?? null;
                $shop->Latitude = $singleShopInfo['Latitude'] ?? null;
                $shop->Longitude = $singleShopInfo['Longitude'] ?? null;
                $shop->ShopAddress = $singleShopInfo['ShopAddress'] ?? null;

                $shop->ShopPhoto = $singleShopInfo['ShopPhoto'] ? ImageBase64Service::imageResizeUpload($singleShopInfo['ShopPhoto'], 'ShopPhoto', public_path('uploads/')): null;
                $shop->CustomerReputation = $singleShopInfo['CustomerReputation'] ?? null;
                $shop->PaymentBehaviour = $singleShopInfo['PaymentBehaviour'] ?? null;
                $shop->ModeOfPayment = $singleShopInfo['ModeOfPayment'] ?? null;
                $shop->YearlyPurchasePotential = $singleShopInfo['YearlyPurchasePotential'] ?? null;
                $shop->PaymentTermsInDays = $singleShopInfo['PaymentTermsInDays'] ?? null;
                $shop->CustomerProposedCreditLimit = $singleShopInfo['CustomerProposedCreditLimit'] ?? null;
                $shop->RepresentativeComment = $singleShopInfo['RepresentativeComment'] ?? null;

                $shop->RepresentativePhoto = $singleShopInfo['RepresentativePhoto'] ? ImageBase64Service::imageResizeUpload($singleShopInfo['RepresentativePhoto'], 'RepresentativePhoto', public_path('uploads/')): null;
                $shop->BalancePerCustomer = $singleShopInfo['BalancePerCustomer'] ?? null;
                $shop->EntryBy = Auth::user()->UserID;
                $shop->EntryDate = Carbon::now();
                $shop->IpAddress = request()->ip();


                $shop->save();



                //Insert Competitor Shop Business
                if(!empty($singleShopInfo['CompetitorShopBusiness'])){
                    foreach($singleShopInfo['CompetitorShopBusiness'] as $competitorBusiness){
                        $competitor = new CompetitorShopBusiness();
                        $competitor->ShopID = $shop->ShopID;
                        $competitor->Name = $competitorBusiness['Name'];
                        $competitor->InvoiceAmount = $competitorBusiness['InvoiceAmount'];
                        $competitor->PaymentAmount = $competitorBusiness['PaymentAmount'];
                        $competitor->save();
                    }
                }

                //Insert Competitor Shop Business
                if(!empty($singleShopInfo['BusinessWithACI'])){
                    foreach($singleShopInfo['BusinessWithACI'] as $businessACI){
                        $businessWithAci = new BusinessWithACI();
                        $businessWithAci->Business = $businessACI['Business'];
                        $businessWithAci->ShopID = $shop->ShopID;
                        $businessWithAci->Limit = $businessACI['Limit'];
                        $businessWithAci->Days = $businessACI['Days'];
                        $businessWithAci->Dues = $businessACI['Dues'];
                        $businessWithAci->OverDue = $businessACI['OverDue'];
                        $businessWithAci->Age = $businessACI['Age'];
                        $businessWithAci->save();
                    }
                }




                DB::commit();

                return response()->json(['status' => 'Success','message' => 'Shop Information created successfully!', 'Data' => $shop->ShopID], 201);

            }
            else{
                // If required fields are missing
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Missing required fields',
                    'data' => $singleShopInfo
                ], 422);
            }

        }

        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $exception->getMessage() . '-' . $exception->getLine()
            ], 500);
        }
    }
    public function getExistingShop(Request $request){
        $userId =  $request->userId;
        if(!empty($userId)){
            $shop = ShopInformation::where('AssignVROStaffId', $userId)->get();
            if($shop){
                return response()->json(['status' => 'Success',
                    'message' => 'Shop Information fetched successfully!',
                    'Data' => $shop], 200);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Shop not found!',
                ],404);
            }

        }

    }

    public function updateExistingShop(Request $request){
        $shopID =  $request->ShopId;
        if(!empty($shopID)){
            $shop = ShopInformation::where('ShopID', $shopID)->first();
            if($shop){
                ShopInformation::where('ShopID', $shopID)->delete();
                BusinessWithACI::where('ShopID', $shopID)->delete();
                CompetitorShopBusiness::where('ShopID', $shopID)->delete();

                DB::beginTransaction();
                try{
                    $singleShopInfo = $request->all();
                    if (!empty($singleShopInfo['Business'])
                        &&!empty($singleShopInfo['CustomerCode'])
                        &&!empty($singleShopInfo['OwnerShip'])
                        && !empty($singleShopInfo['Condition'])
                        && !empty($singleShopInfo['DeedAgreement'])
                        && !empty($singleShopInfo['Latitude'])
                        && !empty($singleShopInfo['Longitude'])
                        && !empty($singleShopInfo['ShopAddress'])
                        && !empty($singleShopInfo['ShopPhoto'])
                        && !empty($singleShopInfo['CustomerReputation'])
                        && !empty($singleShopInfo['PaymentBehaviour'])
                        && !empty($singleShopInfo['ModeOfPayment'])
                        && !empty($singleShopInfo['YearlyPurchasePotential'])
                        && !empty($singleShopInfo['PaymentTermsInDays'])
                        && !empty($singleShopInfo['BalancePerCustomer'])
                        && !empty($singleShopInfo['CustomerProposedCreditLimit'])
                        && !empty($singleShopInfo['RepresentativeComment'])
                        && !empty($singleShopInfo['RepresentativePhoto'])
                        && !empty($singleShopInfo['RepresentativePhoto'])
                    ){

                        //BussinessWiseCustomerInformation
                        $db = BusinessConnection::getConnectionName($singleShopInfo['Business']);
                        $customerCode = ($singleShopInfo['CustomerCode']);
                        $results = DB::connection($db)->select("select * from Customer where CustomerCode = '$customerCode' AND Active='Y'");

                        if(!empty($results[0])){
                            $existingShopInformation = $results[0];
                        }

                        // Create a new record for each field
                        $shop = new ShopInformation();
                        $shop->AssignVROStaffId = Auth::user()->UserID;
                        $shop->Business =$singleShopInfo['Business'] ;
                        $shop->CustomerName = $existingShopInformation->CustomerName;
                        $shop->CustomerCode = $singleShopInfo['CustomerCode'];
                        $shop->CustomerContactPersonName = $existingShopInformation->ContactPerson ?? null;
                        $shop->CustomerContactPersonDesignation = $singleShopInfo['CustomerContactPersonDesignation'] ?? null;
                        $shop->CustomerProprietorName = $singleShopInfo['CustomerProprietorName'] ?? null;
                        $shop->CustomerMobileNo = $existingShopInformation->Mobile ?? null;
                        $shop->CustomerMobileNoTwo = $existingShopInformation->Mobile ?? null;
                        $shop->CustomerAddress = $existingShopInformation->Add1 ?? null;
                        $shop->OwnerShip = $singleShopInfo['OwnerShip'] ?? null;

                        $shop->Condition = $singleShopInfo['Condition'] ?? null;
                        $shop->DeedAgreement = $singleShopInfo['DeedAgreement'] ?? null;
                        $shop->Latitude = $singleShopInfo['Latitude'] ?? null;
                        $shop->Longitude = $singleShopInfo['Longitude'] ?? null;
                        $shop->ShopAddress = $singleShopInfo['ShopAddress'] ?? null;

                        $shop->ShopPhoto = $singleShopInfo['ShopPhoto'] ? ImageBase64Service::imageResizeUpload($singleShopInfo['ShopPhoto'], 'ShopPhoto', public_path('uploads/')): null;
                        $shop->CustomerReputation = $singleShopInfo['CustomerReputation'] ?? null;
                        $shop->PaymentBehaviour = $singleShopInfo['PaymentBehaviour'] ?? null;
                        $shop->ModeOfPayment = $singleShopInfo['ModeOfPayment'] ?? null;
                        $shop->YearlyPurchasePotential = $singleShopInfo['YearlyPurchasePotential'] ?? null;
                        $shop->PaymentTermsInDays = $singleShopInfo['PaymentTermsInDays'] ?? null;
                        $shop->CustomerProposedCreditLimit = $singleShopInfo['CustomerProposedCreditLimit'] ?? null;
                        $shop->RepresentativeComment = $singleShopInfo['RepresentativeComment'] ?? null;

                        $shop->RepresentativePhoto = $singleShopInfo['RepresentativePhoto'] ? ImageBase64Service::imageResizeUpload($singleShopInfo['RepresentativePhoto'], 'RepresentativePhoto', public_path('uploads/')): null;
                        $shop->BalancePerCustomer = $singleShopInfo['BalancePerCustomer'] ?? null;
                        $shop->EditBy = Auth::user()->UserID;
                        $shop->EditDate = Carbon::now();
                        $shop->save();



                        //Insert Competitor Shop Business
                        if(!empty($singleShopInfo['CompetitorShopBusiness'])){
                            foreach($singleShopInfo['CompetitorShopBusiness'] as $competitorBusiness){
                                $competitor = new CompetitorShopBusiness();
                                $competitor->ShopID = $shop->ShopID;
                                $competitor->Name = $competitorBusiness['Name'];
                                $competitor->InvoiceAmount = $competitorBusiness['InvoiceAmount'];
                                $competitor->PaymentAmount = $competitorBusiness['PaymentAmount'];
                                $competitor->save();
                            }
                        }

                        //Insert Competitor Shop Business
                        if(!empty($singleShopInfo['BusinessWithACI'])){
                            foreach($singleShopInfo['BusinessWithACI'] as $businessACI){
                                $businessWithAci = new BusinessWithACI();
                                $businessWithAci->Business = $businessACI['Business'];
                                $businessWithAci->ShopID = $shop->ShopID;
                                $businessWithAci->Limit = $businessACI['Limit'];
                                $businessWithAci->Days = $businessACI['Days'];
                                $businessWithAci->Dues = $businessACI['Dues'];
                                $businessWithAci->OverDue = $businessACI['OverDue'];
                                $businessWithAci->Age = $businessACI['Age'];
                                $businessWithAci->save();
                            }
                        }

                        DB::commit();

                        return response()->json(['status' => 'Success','message' => 'Shop Information updated successfully!', 'Data' => $shop->ShopID], 201);

                    }
                    else{
                        // If required fields are missing
                        return response()->json([
                            'status' => 'Error',
                            'message' => 'Missing required fields',
                            'data' => $singleShopInfo
                        ], 422);
                    }

                }

                catch (\Exception $exception) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Something went wrong!' . $exception->getMessage() . '-' . $exception->getLine()
                    ], 500);
                }
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Shop not found!',
                ],404);
            }
            }
    }

}
