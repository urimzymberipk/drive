<template>
  <el-dialog
      title="Save To blockchain"
      :visible.sync="dialogVisible"
      width="30%"
      :custom-class="isDarkMode ? 'customDialogDark' : 'customDialogWhite'"
      :before-close="closeDialog"
  >
    <div class="dialogContent" v-if="!showSpinner && !showDone">
      <ThumbnailItem class="mb-5" :item="selectedPdf"/>

      <div class="selectBoxes">
        <div class="selectBox">
          <el-select v-model="selectedFolder" placeholder="Select Folder" clearable style="width:100%">
            <el-option
                v-for="item in folders"
                :key="item.folderName"
                :label="item.folderName"
                :value="item.folderName">
            </el-option>
          </el-select>
        </div>

        <div class="selectBox">
          <el-select v-model="selectedCertificate" placeholder="Select Certificate" clearable style="width:100%">
            <el-option
                v-for="item in certificates"
                :key="item.name"
                :label="item.name"
                :value="item.name">
            </el-option>
          </el-select>
        </div>
      </div>

      <div class="confirmBtn" @click="save">
        <button class="button-base w-full theme p-2">
          Save
        </button>
      </div>
    </div>

    <div v-else-if="!showDone">
      <Spinner/>
      <div v-if="showP" class="text-sm mt-16 pt-16 lg:py-3.5 py-4 px-5">
        <p class="text-center">Saving document to blockchain. This may take up to 1 minute. Please do not close this
          window.</p>
      </div>
    </div>
    <div v-else>
      <p class="text-center my-12">File saved successfully</p>
      <div class="confirmBtn text-center" @click="closeDialog">
        <button class="button-base w-9/12 theme p-2">
          Done
        </button>
      </div>
    </div>

  </el-dialog>
</template>

<script>
import {mapGetters} from 'vuex'
// import ThumbnailItem from './ThumbnailItem'
import axios from 'axios'
import ThumbnailItem from "../UI/Entries/ThumbnailItem";
import Spinner from "../UI/Others/Spinner";
// import Spinner from "../FilesView/Spinner"

export default {
  name: 'SaveToBlockChain',
  props: ['dialogVisible', 'selectedPdf'],
  components: {
    ThumbnailItem,
    Spinner
  },
  computed: {
    ...mapGetters(['getAuthenticatedUserDetails', 'isDarkMode', 'entries']),
    buttonDisabled() {
      return this.selectedCertificate && this.selectedFolder
    },
  },
  data() {
    return {
      loading: false,
      folders: null,
      selectedCertificate: null,
      selectedFolder: null,
      showSpinner: false,
      showP: false,
      showDone: false,
      certificates: [
        {
          name: 'University Degree',
        },
        {
          name: 'Certificate',
        }
      ]
    }
  },
  methods: {
    getData() {
      this.showSpinner = true

      let foldersUrl = `/api/blockchain/get-folders`;
      let headers = {
        // 'Authorization': 'Bearer ' + this.getAuthenticatedUserDetails,
      }
      axios.get(foldersUrl, {headers}).then((res) => {
        this.folders = res.data
      })
          .catch((error) => {
            this.$store.commit('SET_AUTHENTICATED_USER_DETAILS', null)
            this.$emit('closeDialogAndOpenEdBitLogin')
          })
          .finally(() => {
            this.showSpinner = false
          });
    },
    save() {
      if (!this.selectedCertificate || !this.selectedFolder) {
        this.$notify({
          title: 'Info',
          type: 'info',
          message: 'Both folder and certificate should be selected to proceed!'
        });
      } else {
        this.showSpinner = true
        this.showP = true
        let base_url = window.location.origin;
        let url = base_url + '/api/blockchain/save-file-blockchain'

        let payload = {
          type: this.selectedCertificate,
          folderName: this.selectedFolder,
          fileId: this.selectedPdf.data.id,
        }
        let headers = {
          'Authorization': 'Bearer ' + this.getAuthenticatedUserDetails,
        }

        axios.post(url, payload, {headers}).then((res) => {
          this.$store.dispatch('getFolder', {page: 1, id:this.$route.params.id})
          this.showSpinner = false
          this.showDone = true
        })
            .catch((error) => {
              this.$notify({
                title: 'Error',
                type: 'error',
                message: error.data
              });
            })
            .finally(() => {
              this.showSpinner = false
              this.showDone = true
              // this.closeDialog()
            });
      }
    },
    closeDialog() {
      this.$emit('closeDialog')
    }
  },
  mounted() {

  },
  beforeMount() {
    this.getData()
  }
}
</script>

<style lang="scss">

.customDialogDark {
  background: #171819 !important;
  border-radius: 0.75rem;

  .dialogContent {
    .selectBoxes {
      display: flex;
      gap: 30px;

      .selectBox {
        flex: 1;
      }
    }

    .el-input__inner {
      background-color: #151515 !important;
    }

    .el-select-dropdown__list {
      background-color: #151515 !important;
    }

    .confirmBtn {
      margin-top: 30px;
    }
  }
}

.customDialogWhite {
  background: #FFFFFF !important;
  border-radius: 0.75rem;

  .dialogContent, .actualContent {
    .selectBoxes, .inputsWrapper {
      display: flex !important;
      gap: 30px;

      .selectBox {
        flex: 1;
      }

      .password, .email {
        width: 100%;
      }
    }

    .confirmBtn {
      margin-top: 30px;
    }
  }
}

//.el-notification.right {
//  background: red !important;
//}
</style>

