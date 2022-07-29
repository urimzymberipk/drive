<template>
  <el-dialog
      title="Contact your admin"
      :visible.sync="dialogVisible"
      width="30%"
      :custom-class="isDarkMode ? 'customDialogDark' : 'customDialogWhite'"
      :before-close="closeDialog"
  >
    <div class="actualContent">
      <div class="text-sm py-10">
        <p class="text-center">Please contact your admin.</p>
      </div>
      <div class="confirmBtn flex justify-center" @click="closeDialog">
        <button class="button-base w-3/4 theme p-2">
          Close
        </button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "EdBitSaveToBlockChainPopup",
  props: ['dialogVisible'],
  data() {
    return {
      obj: {
        email: null,
        password: null,
      }
    }
  },
  computed: {
    ...mapGetters(['isDarkMode']),
  },
  methods: {
    closeDialog() {
      this.$emit('closeDialog')
    }
  },
}
</script>

<style scoped lang="scss">

.customDialogDark {
  background: #171819 !important;
  border-radius: 0.75rem;


  .actualContent {
    .inputsWrapper {
      display: flex;
      gap: 30px;

      .flex_1 {
        flex: 1;
      }
    }

    .confirmBtn {
      margin-top: 30px;
      //width: 50%;
    }
  }
}

.customDialogDark {
  background: #ffffff !important;
  border-radius: 0.75rem;

  .actualContent {
    .inputsWrapper {
      display: flex;
      gap: 30px;

      .flex_1 {
        flex: 1;
      }
    }

    .confirmBtn {
      margin-top: 30px;
      //width: 50%;
    }
  }
}

.el-notification.right {
  background: #171819 !important;
}
</style>
