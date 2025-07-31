<?php

namespace App\Http\Controllers\VRO;

use App\Helpers\BusinessConnection;
use App\Http\Controllers\Controller;
use App\Models\AssignedVro;
use App\Models\Business;
use App\Models\BusinessWithACI;
use App\Models\CompetitorShopBusiness;
use App\Models\ShopInformation;
use App\Services\ImageBase64Service;
use Carbon\Carbon;
use Faker\Provider\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
                && !empty($singleShopInfo['CustomerReputation'])
                && !empty($singleShopInfo['PaymentBehaviour'])
                && !empty($singleShopInfo['ModeOfPayment'])
                && !empty($singleShopInfo['YearlyPurchasePotential'])
                && !empty($singleShopInfo['PaymentTermsInDays'])
                && !empty($singleShopInfo['BalancePerCustomer'])
                && !empty($singleShopInfo['CustomerProposedCreditLimit'])
                && !empty($singleShopInfo['RepresentativeComment'])
            ){

                if (!empty($singleShopInfo['ShopId'])) {
                    $updateShopInfo  = $this->updateExistingShop($singleShopInfo);
                    return $updateShopInfo;
                }

                if(!empty($singleShopInfo['CustomerCode'])){
                   $checkExisting =  ShopInformation::where('CustomerCode', $singleShopInfo['CustomerCode'])->first();


                   if( !empty($checkExisting->CustomerCode) && $checkExisting->CustomerCode == $singleShopInfo['CustomerCode']){
                       return response()->json([
                          'status' => 'error',
                          'message' => 'Customer code already exist!',
                       ],405);
                   }

                }
                    DB::beginTransaction();
                    //BussinessWise Customer Information
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
                    $shop->ContactPersonDesignation =$singleShopInfo['ContactPersonDesignation'] ;
                    $shop->ProprietorName =$singleShopInfo['ProprietorName'] ;
                    $shop->TypeOfEntity =$singleShopInfo['TypeOfEntity'] ;



                    $shop->CustomerName = $existingShopInformation->CustomerName;
                    $shop->CustomerCode = $singleShopInfo['CustomerCode'];
                    $shop->CustomerContactPersonName = $existingShopInformation->ContactPerson ?? null;
                    $shop->CustomerContactPersonDesignation = $singleShopInfo['CustomerContactPersonDesignation'] ?? null;
                    $shop->CustomerProprietorName = $singleShopInfo['CustomerProprietorName'] ?? null;
                    $shop->ContactPersonName = $singleShopInfo['ContactPersonName'] ?? null;
                    $shop->CustomerMobileNo = $existingShopInformation->Mobile ?? null;
                    $shop->CustomerMobileNoTwo = $existingShopInformation->Mobile ?? null;
                    $shop->CustomerAddress = $existingShopInformation->Add1 ?? null;
                    $shop->OwnerShip = $singleShopInfo['OwnerShip'] ?? null;

                    $shop->Condition = $singleShopInfo['Condition'] ?? null;
                    $shop->DeedAgreement = $singleShopInfo['DeedAgreement'] ?? null;
                    $shop->Latitude = $singleShopInfo['Latitude'] ?? null;
                    $shop->Longitude = $singleShopInfo['Longitude'] ?? null;
                    $shop->ShopAddress = $singleShopInfo['ShopAddress'] ?? null;

                    $shop->ShopPhoto = $singleShopInfo['ShopPhoto'] ? ImageBase64Service::uploadBase64Image($singleShopInfo['ShopPhoto'], public_path('uploads/'),'ShopPhoto'): null;
                    $shop->CustomerReputation = $singleShopInfo['CustomerReputation'] ?? null;
                    $shop->PaymentBehaviour = $singleShopInfo['PaymentBehaviour'] ?? null;
                    $shop->ModeOfPayment = $singleShopInfo['ModeOfPayment'] ?? null;
                    $shop->YearlyPurchasePotential = $singleShopInfo['YearlyPurchasePotential'] ?? null;
                    $shop->PaymentTermsInDays = $singleShopInfo['PaymentTermsInDays'] ?? null;
                    $shop->CustomerProposedCreditLimit = $singleShopInfo['CustomerProposedCreditLimit'] ?? null;
                    $shop->RepresentativeComment = $singleShopInfo['RepresentativeComment'] ?? null;
                    $shop->SBU = $singleShopInfo['SBU'] ?? null;

                    $shop->RepresentativePhoto = $singleShopInfo['RepresentativePhoto'] ? ImageBase64Service::uploadBase64Image($singleShopInfo['RepresentativePhoto'], public_path('uploads/'),'RepresentativePhoto'): null;
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
    public function getExistingShop(){
        $userId = Auth::user()->UserID;
        if(!empty($userId)){

            if(Auth::user()->RoleID === 'Admin'){
                $shop = ShopInformation::with('competitorShopBusinesses','businessWithACI')->select(
                    'ShopInformation.*',
                    'Users.Name as VROStaffName',
                    DB::raw("CASE WHEN ShopInformation.ShopPhoto IS NULL THEN 'default.png' ELSE CONCAT('https://wa.acibd.com/e-verification/uploads/', ShopInformation.ShopPhoto) END AS ShopPhoto"),
                    DB::raw("CASE WHEN ShopInformation.RepresentativePhoto IS NULL THEN 'default.png' ELSE CONCAT('https://wa.acibd.com/e-verification/uploads/', ShopInformation.RepresentativePhoto) END AS RepresentativePhoto"),
                    'Business.BusinessName',
                )

                ->join('Users', 'Users.UserID', '=', 'ShopInformation.AssignVROStaffId')
                ->join('Business', 'Business.Business', '=', 'ShopInformation.Business')
                ->orderBy('ShopInformation.ShopID', 'desc')
                ->get();
            }
            else{
                $shop = ShopInformation::with('competitorShopBusinesses','businessWithACI')->select(
                    'ShopInformation.*',
                    'Users.Name as VROStaffName',
                    DB::raw("CASE WHEN ShopInformation.ShopPhoto IS NULL THEN 'default.png' ELSE CONCAT('https://wa.acibd.com/e-verification/uploads/', ShopInformation.ShopPhoto) END AS ShopPhoto"),
                    DB::raw("CASE WHEN ShopInformation.RepresentativePhoto IS NULL THEN 'default.png' ELSE CONCAT('https://wa.acibd.com/e-verification/uploads/', ShopInformation.RepresentativePhoto) END AS RepresentativePhoto"),
                    'Business.BusinessName',

                )

                    ->join('Users', 'Users.UserID', '=', 'ShopInformation.AssignVROStaffId')
                    ->join('Business', 'Business.Business', '=', 'ShopInformation.Business')
                    ->orderBy('ShopInformation.ShopID', 'desc')
                    ->where('AssignVROStaffId', $userId)->get();
            }

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
    public function updateExistingShop($singleShopInfo){
        $shopID =  $singleShopInfo['ShopId'];
        if(!empty($shopID)){
            $shop = ShopInformation::where('ShopID', $shopID)->first();
            if($shop){
                BusinessWithACI::where('ShopID', $shopID)->delete();
                CompetitorShopBusiness::where('ShopID', $shopID)->delete();

                try{
                    if (!empty($singleShopInfo['Business'])
                        &&!empty($singleShopInfo['CustomerCode'])
                        &&!empty($singleShopInfo['OwnerShip'])
                        && !empty($singleShopInfo['Condition'])
                        && !empty($singleShopInfo['DeedAgreement'])
                        && !empty($singleShopInfo['Latitude'])
                        && !empty($singleShopInfo['Longitude'])
                        && !empty($singleShopInfo['ShopAddress'])
                        && !empty($singleShopInfo['CustomerReputation'])
                        && !empty($singleShopInfo['PaymentBehaviour'])
                        && !empty($singleShopInfo['ModeOfPayment'])
                        && !empty($singleShopInfo['YearlyPurchasePotential'])
                        && !empty($singleShopInfo['PaymentTermsInDays'])
                        && !empty($singleShopInfo['BalancePerCustomer'])
                        && !empty($singleShopInfo['CustomerProposedCreditLimit'])
                        && !empty($singleShopInfo['RepresentativeComment'])
                    ){

                        //BussinessWiseCustomerInformation
                        $db = BusinessConnection::getConnectionName($singleShopInfo['Business']);
                        $customerCode = ($singleShopInfo['CustomerCode']);
                        $results = DB::connection($db)->select("select * from Customer where CustomerCode = '$customerCode' AND Active='Y'");

                        if(!empty($results[0])){
                            $existingShopInformation = $results[0];
                            DB::beginTransaction();
                            // Create a new record for each field
                            $shopUpdate = ShopInformation::where('ShopID', $shopID)->first();
                            $shopUpdate->AssignVROStaffId = Auth::user()->UserID;
                            $shopUpdate->Business =$singleShopInfo['Business'] ;
                            $shopUpdate->ContactPersonDesignation =$singleShopInfo['ContactPersonDesignation'] ;
                            $shopUpdate->ProprietorName =$singleShopInfo['ProprietorName'] ;
                            $shopUpdate->TypeOfEntity =$singleShopInfo['TypeOfEntity'] ;
                            $shopUpdate->CustomerName = $existingShopInformation->CustomerName;
                            $shopUpdate->CustomerCode = $singleShopInfo['CustomerCode'];
                            $shopUpdate->CustomerContactPersonName = $existingShopInformation->ContactPerson ?? null;
                            $shopUpdate->CustomerContactPersonDesignation = $singleShopInfo['CustomerContactPersonDesignation'] ?? null;
                            $shopUpdate->CustomerProprietorName = $singleShopInfo['CustomerProprietorName'] ?? null;
                            $shopUpdate->ContactPersonName = $singleShopInfo['ContactPersonName'] ?? null;
                            $shopUpdate->CustomerMobileNo = $existingShopInformation->Mobile ?? null;
                            $shopUpdate->CustomerMobileNoTwo = $existingShopInformation->Mobile ?? null;
                            $shopUpdate->CustomerAddress = $existingShopInformation->Add1 ?? null;
                            $shopUpdate->OwnerShip = $singleShopInfo['OwnerShip'] ?? null;

                            $shopUpdate->Condition = $singleShopInfo['Condition'] ?? null;
                            $shopUpdate->DeedAgreement = $singleShopInfo['DeedAgreement'] ?? null;
                            $shopUpdate->Latitude = $singleShopInfo['Latitude'] ?? null;
                            $shopUpdate->Longitude = $singleShopInfo['Longitude'] ?? null;
                            $shopUpdate->ShopAddress = $singleShopInfo['ShopAddress'] ?? null;
                            $shop->SBU = $singleShopInfo['SBU'] ?? null;
                            $shopUpdate->ShopPhoto = $singleShopInfo['ShopPhoto'] ? ImageBase64Service::uploadBase64Image($singleShopInfo['ShopPhoto'], public_path('uploads/'),'ShopPhoto'): $shopUpdate->ShopPhoto;
                            $shopUpdate->CustomerReputation = $singleShopInfo['CustomerReputation'] ?? null;
                            $shopUpdate->PaymentBehaviour = $singleShopInfo['PaymentBehaviour'] ?? null;
                            $shopUpdate->ModeOfPayment = $singleShopInfo['ModeOfPayment'] ?? null;
                            $shopUpdate->YearlyPurchasePotential = $singleShopInfo['YearlyPurchasePotential'] ?? null;
                            $shopUpdate->PaymentTermsInDays = $singleShopInfo['PaymentTermsInDays'] ?? null;
                            $shopUpdate->CustomerProposedCreditLimit = $singleShopInfo['CustomerProposedCreditLimit'] ?? null;
                            $shopUpdate->RepresentativeComment = $singleShopInfo['RepresentativeComment'] ?? null;
                            $shopUpdate->RepresentativePhoto = $singleShopInfo['RepresentativePhoto'] ? ImageBase64Service::uploadBase64Image($singleShopInfo['RepresentativePhoto'], public_path('uploads/'),'RepresentativePhoto'):$shopUpdate->RepresentativePhoto;
                            $shopUpdate->BalancePerCustomer = $singleShopInfo['BalancePerCustomer'] ?? null;;
                            $shopUpdate->EditBy = Auth::user()->UserID;
                            $shopUpdate->EditDate = Carbon::now();
                            $shopUpdate->save();
                        }





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
    public function getVroAssignList(){
        $vroCustomerList = AssignedVro::select('AssignedVro.CustomerCode', 'AssignedVro.Business', 'Business.BusinessName')
            ->join('Business', 'Business.Business', '=', 'AssignedVro.Business')
            ->leftJoin('ShopInformation', 'ShopInformation.CustomerCode', '=', 'AssignedVro.CustomerCode')
            ->whereNull('ShopInformation.CustomerCode')
            ->where('AssignedVROStaffId', Auth::user()->UserID)
            ->get();


        if(empty($vroCustomerList[0]->CustomerCode)){
            return response()->json([
                'status' => 'error',
                'message' => 'Assign customer not found!',
            ],404);
        }
        return response()->json(['status' => 'Success',
            'message' => 'Assign customer fetched successfully!',
            'data' => $vroCustomerList], 200);


    }

}
