<template>
  <el-dialog
      title="Log In to EdBit"
      :visible.sync="dialogVisible"
      width="30%"
      :custom-class="isDarkMode ? 'customDialogDark' : 'customDialogWhite'"
      :before-close="closeDialog"
  >
    <div class="actualContent">
      <div class="inputsWrapper">
        <div class="email flex_1">
          <el-input placeholder="Email" v-model="obj.email"></el-input>
        </div>

        <div class="password flex_1">
          <el-input placeholder="Password" v-model="obj.password"></el-input>
        </div>
      </div>

      <div class="confirmBtn" @click="logIn">
        <button class="button-base w-full theme p-2">
          Log In
        </button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
import axios from "axios";
import {mapGetters} from "vuex";

export default {
  name: "EdBitSaveToBlockChainPopup",
  props: ['dialogVisible'],
  data() {
    return {
      obj:{
        email: null,
        password: null,
      }
    }
  },
  computed:{
    ...mapGetters(['isDarkMode']),
  },
  methods: {
    logIn(){
      let url = `https://api.edbit.io/auth/login`;
      axios.post(url, this.obj).then((res) => {
        this.$store.commit('SET_AUTHENTICATED_USER_DETAILS', res.data.accessToken)
        this.closeDialog()
      })
      .catch((error) => {
        this.$notify({
          title: 'Error',
          type: 'error',
          message: error.data
        });
      })
      .finally(() => {
      });
    },
    closeDialog(){
      this.$emit('closeDialog')
    }
  },
}
</script>

<style scoped lang="scss">

.customDialogDark {
  background: #171819 !important;
  border-radius: 0.75rem;


  .actualContent{
    .inputsWrapper{
      display: flex;
      gap: 30px;

      .flex_1{
        flex: 1;
      }
    }
    .confirmBtn{
      margin-top: 30px;
      //width: 50%;
    }
  }
}

.customDialogDark {
  background: #ffffff !important;
  border-radius: 0.75rem;

  .actualContent{
    .inputsWrapper{
      display: flex;
      gap: 30px;

      .flex_1{
        flex: 1;
      }
    }
    .confirmBtn{
      margin-top: 30px;
      //width: 50%;
    }
  }
}

.el-notification.right {
  background: #171819 !important;
}
</style>
