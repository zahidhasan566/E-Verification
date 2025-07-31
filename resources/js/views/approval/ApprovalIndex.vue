<template>
    <div class="content" id="approval">
        <div class="container-fluid">
            <breadcrumb :options="['Approval']">
            </breadcrumb>
            <div class="row" style="padding:8px 0px;">
                <div class="col-md-4">
                    <button type="button" class="btn btn-success btn-sm" @click="exportData">Export to Excel</button>
                </div>
            </div>
            <advanced-datatable :options="tableOptions">
                <template slot="action" slot-scope="row">
                    <span v-if="row.item.ApproveStatus =='' || row.item.ApproveStatus ==null ">
                     <button type="button" style="padding: 2px 5px" @click="approvalShopReq(row.item.ShopID,'Y')" class="btn btn-warning btn-sm"><i class="fa fa-arrow-circle-right"></i> Approve</button>
                    <button type="button" style="padding: 2px 5px" @click="approvalShopReq(row.item.ShopID,'N')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Reject</button>
                    </span>
                    <span v-else>
                        <span style="background: #00a55d;color: white; padding: 5px 5px;border-radius: 10px;" v-if="row.item.ApproveStatus == 'Y'">Approved</span>
                        <span style="background: red;color: white;padding: 5px 5px;border-radius: 10px;" v-else>Rejected</span>
                    </span>


                </template>
            </advanced-datatable>
            <add-edit-user @changeStatus="changeStatus" v-if="loading"/>
            <reset-password @changeStatus="changeStatus" v-if="loading"/>
        </div>
    </div>

</template>
<script >

import {bus} from "../../app";
import {Common} from "../../mixins/common";
import moment from "moment";

export default {
  mixins: [Common],
  data() {
    return {
      tableOptions: {
        source: 'approval/shop-list',
        search: true,
        slots: [24],
        hideColumn: ['ApproveStatus'],
        slotsName: ['action'],
        sortable: [2],
        pages: [20, 50, 100],
        addHeader: ['Action']
      },
      loading: false,
      cpLoading: false
    }
  },
  mounted() {
      console.log("fdd")
    bus.$off('changeStatus',function () {
      this.changeStatus()
    })
  },
  methods: {
    changeStatus() {
      this.loading = false
    },
      addUserModal(row = '') {
      this.loading = true;
      setTimeout(() => {
        bus.$emit('add-edit-user', row);
      })
    },
    changePassword(row) {
      this.loading = true;
      setTimeout(() => {
        bus.$emit('edit-password', row);
      })
    },
    deleteDept(id) {
      this.deleteAlert(() => {
        this.axiosDelete('users', id, (response) => {
          this.successNoti(response.message);
          this.$store.commit('departmentDelete', id);
          bus.$emit('refresh-datatable');
        }, (error) => {
          this.errorNoti(error);
        })
      });
    },
      approvalShopReq(shopID, status) {
          let proposalStatus = status === 'Y' ? 'Approved' : 'Rejected';

          Swal.fire({
              title: proposalStatus + '?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: "Yes",
              input: 'textarea',
              inputPlaceholder: 'Add your remarks (optional)',
              inputAttributes: {
                  'aria-label': 'Add your remarks here'
              },
              preConfirm: (inputValue) => {
                  return { remarks: inputValue };
              }
          }).then(async (result) => {
              if (result.isConfirmed) {
                  try {
                      this.postData(shopID,status,result.value.remarks);
                  } catch (error) {
                      this.errorNoti(error.response?.data?.message || 'Error updating invoice status');
                  }
              }
          });

      },
      postData(shopID,status,remarks){
          //preloader
          this.PreLoader = true;
          let  submitUrl = 'approval/add-reject-shop-requisition';
          this.axiosPost(submitUrl, {
              shopID: shopID,
              status: status,
              remarks: remarks,
          }, (response) => {
              this.PreLoader = false;
              this.successNoti(response.message);
              bus.$emit('refresh-datatable');
          }, (error) => {
              this.PreLoader = false;
              this.errorNoti(error);
          })
      },
    exportData() {
      bus.$emit('export-data','shop-approval-list-'+moment().format('YYYY-MM-DD'))
    }
  }
}
</script>
<style>
#approval th:nth-child(21){
    width: 300px !important;
}
</style>
