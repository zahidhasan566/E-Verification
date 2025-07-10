<template>
  <div class="container-fluid">
    <breadcrumb :options="['Assign Vro']">
      <button class="btn btn-primary" @click="addVroModal()">Assign Vro</button>
    </breadcrumb>
    <div class="row" style="padding:8px 0px;">
      <div class="col-md-4">
        <button type="button" class="btn btn-success btn-sm" @click="exportData">Export to Excel</button>
      </div>
    </div>
    <advanced-datatable :options="tableOptions">
      <template slot="action" slot-scope="row">
          <button class="btn-danger" @click="deleteVRO(row.item.AssignedVroID)"><i class="fas fa-trash"><span >Delete</span></i></button>
<!--        <a href="javascript:" @click="changePassword(row.item.UserID)"> <i class="ti-lock"></i></a>-->
      </template>
    </advanced-datatable>
    <add-edit-assign-vro @changeStatus="changeStatus" v-if="loading"/>
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
        source: 'vro/assigned-vro-list',
        search: true,
        slots: [8],
        hideColumn: ['RoleID','UserID'],
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
    bus.$off('changeStatus',function () {
      this.changeStatus()
    })
  },
  methods: {
    changeStatus() {
      this.loading = false
    },
      addVroModal(row = '') {
      this.loading = true;
      setTimeout(() => {
        bus.$emit('add-edit-assign-vro', row);
      })
    },
    changePassword(row) {
      this.loading = true;
      setTimeout(() => {
        bus.$emit('edit-password', row);
      })
    },
    deleteVRO(AssignedVroID) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                let  submitUrl = 'vro/delete-vro';
                this.axiosPost(submitUrl, {
                    AssignedVroID: AssignedVroID,
                }, (response) => {
                    Swal.fire(
                         'Delete !',
                        'Your file has been Deleted',
                        'success'
                    )
                    bus.$emit('refresh-datatable');
                }, (error) => {
                    this.errorNoti(error);
                })
            }
        })
    },
    exportData() {
      bus.$emit('export-data','assign-vro-list-'+moment().format('YYYY-MM-DD'))
    }
  }
}
</script>
