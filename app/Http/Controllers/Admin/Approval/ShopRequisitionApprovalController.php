<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use App\Models\ShopInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopRequisitionApprovalController extends Controller
{
    public function index(Request $request)
    {
        $take = $request->take;
        $search = $request->search;

        $shopInfo  =  ShopInformation::select(
                        'ShopInformation.ShopID',
                        'ShopInformation.AssignVROStaffId',
                        'Users.Name as AssignVroName',
                        'ShopInformation.Business',
                        'ShopInformation.ContactPersonName',
                        'ShopInformation.ContactPersonDesignation',
                        'ShopInformation.ProprietorName',
                        'ShopInformation.TypeOfEntity',
                        'ShopInformation.CustomerName',
                        'ShopInformation.CustomerCode',
                        'ShopInformation.CustomerMobileNo',
                        'ShopInformation.CustomerAddress',
                        'ShopInformation.OwnerShip',
                        'ShopInformation.Condition',
                        'ShopInformation.DeedAgreement',
                        'ShopInformation.ShopPhoto',
                        'ShopInformation.CustomerReputation',
                        'ShopInformation.PaymentBehaviour',
                        'ShopInformation.ModeOfPayment',
                        'ShopInformation.YearlyPurchasePotential',
                        'ShopInformation.PaymentTermsInDays',
                        'ShopInformation.CustomerProposedCreditLimit',
                        'ShopInformation.RepresentativeComment',
                        'ShopInformation.RepresentativePhoto',
                        'ShopInformation.BalancePerCustomer',
                        'ShopInformation.ApproveStatus',
                        )->where(function ($q) use ($search) {
                            $q->where('Name', 'like', '%' . $search . '%');
                            $q->orWhere('ShopID', 'like', '%' . $search . '%');
                            $q->orWhere('CustomerCode', 'like', '%' . $search . '%');
                        })
                        ->join('Users', 'Users.UserID', '=', 'ShopInformation.AssignVROStaffId')
                        ->OrderBy('ShopInformation.ShopID', 'desc');


        if ($request->type === 'export') {
            return response()->json([
                'data' => $shopInfo->get(),
            ]);
        } else {
            return $shopInfo->paginate($take);
        }

    }

    public function approveRejectRequisition(Request $request){

        try{
            if($request->shopID && $request->status){
                ShopInformation::where('ShopID', $request->shopID)->update([
                    'ApproveStatus' => $request->status,
                    'ApprovedBy' => Auth::user()->UserID,
                    'ApprovedDate' => date('Y-m-d H:i:s'),
                    'Remarks' => $request->remarks
                ]);

                $status  = $request->status == 'Y' ? 'Approved' : 'Rejected';

                return response()->json([
                    'status' => 'success',
                    'message' => 'Shop Requisition' .$status .'Successfully'
                ]);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage().' '.$e->getLine()
            ]);
        }

        dd($request);
    }
}
