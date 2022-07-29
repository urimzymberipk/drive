<template>
  <PageTab>
    <div class="card shadow-card">
      <FormLabel>
        Login to blockchain
      </FormLabel>
      <AppInputText
          title="Email"
      >
        <input
            v-model="blockchain.email"
            placeholder="email@example.com"
            class="focus-border-theme input-dark"
            type="text"
            required
        />
      </AppInputText>
      <AppInputText
          title="Password"
      >
        <input
            v-model="blockchain.password"
            class="focus-border-theme input-dark"
            type="password"
            required
        />
      </AppInputText>
      <ButtonBase
          button-style="theme"
          class="w-full sm:w-auto"
          @click.native="login"
      >
        Login
      </ButtonBase>
      <Spinner v-if="isLoading"/>
    </div>
  </PageTab>
</template>

<script>
import {Edit2Icon} from 'vue-feather-icons'
import {ValidationObserver, ValidationProvider} from 'vee-validate/dist/vee-validate.full'
// import AppInputButton from '../../../../components/Admin/AppInputButton'
// import FormLabel from '../../../../components/Others/Forms/FormLabel'
// import ButtonBase from '../../../../components/FilesView/ButtonBase'
// import AppInputText from '../../../../components/Admin/AppInputText'
// import PageTab from '../../../../components/Others/Layout/PageTab'
import {required} from 'vee-validate/dist/rules'
import axios from 'axios'
// import Spinner from "../../../../components/FilesView/Spinner";
import AppInputButton from "../../../../components/Forms/Layouts/AppInputButton";
import Spinner from "../../../../components/UI/Others/Spinner";
import FormLabel from "../../../../components/UI/Labels/FormLabel";
import PageTab from "../../../../components/Layout/PageTab";
import ButtonBase from "../../../../components/UI/Buttons/ButtonBase";
import AppInputText from "../../../../components/Forms/Layouts/AppInputText";

export default {
  name: 'AppOthers',
  components: {
    AppInputButton,
    ValidationObserver,
    ValidationProvider,
    AppInputText,
    ButtonBase,
    Edit2Icon,
    FormLabel,
    required,
    PageTab,
    Spinner
  },
  data() {
    return {
      isLoading: false,
      blockchain: {
        email: null,
        password: null
      },
    }
  },
  methods: {
    login() {
      this.isLoading = true
      if (!this.blockchain.email || !this.blockchain.password) {
        this.$notify({
          title: 'Info',
          type: 'info',
          message: 'Both folder and certificate should be selected to proceed!'
        });
        this.isLoading = false
      } else {
        axios.post('/api/blockchain/login', this.blockchain).then(res => {
          this.$notify({
            title: 'Success',
            type: 'success',
            message: 'Successful login to blockchain!'
          });
        })
            .catch(() => {
              this.$notify({
                title: 'Error',
                type: 'error',
                message: 'Failed login to blockchain!'
              });
              this.isLoading = false
            })
            .finally(() => {
              this.isLoading = false
            });
      }
    }
  },
}
</script>

<style>
.input-dark {
  /*width: 50%;*/
}
</style>
