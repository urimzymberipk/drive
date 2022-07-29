<template>
    <div class="lg:flex h-screen lg:overflow-hidden w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

        <!--Popups-->
        <ProcessingPopup />
        <ConfirmPopup />

        <CreateTeamFolderPopup />
        <EditTeamFolderPopup />

        <ShareCreatePopup />
        <ShareEditPopup />

      <SaveToBlockChainPopup
          v-if="showSaveToBlockChainDialog"
          :dialogVisible="showSaveToBlockChainDialog"
          :selectedPdf="selectedPdf"
          @closeDialog="showSaveToBlockChainDialog = false"
          @closeDialogAndOpenEdBitLogin="closeDialogAndOpenEdBitLogin"
      />

      <EdBitSaveToBlockChainPopup
          v-if="showEdBitLoginModal"
          :dialogVisible="showEdBitLoginModal"
          @closeDialog="closeEdBitDialog"
          :key="edBitDialogKey"
      >
      </EdBitSaveToBlockChainPopup>

        <CreateUploadRequestPopup />
		<NotificationsPopup />
		<RemoteUploadPopup />
        <CreateFolderPopup />
        <RenameItemPopup />
        <MoveItemPopup />

        <!--Mobile components-->
        <FileSortingMobile />
        <FileFilterMobile />

        <!--Navigations-->
        <MobileNavigation />

        <!--Others-->
        <DragUI />

        <!--2 col Sidebars-->
        <PanelNavigationFiles />

        <div
            @contextmenu.prevent.capture="contextMenu($event, undefined)"
            class="lg:flex lg:flex-col lg:w-full lg:px-3.5 min-w-0"
        >
            <DesktopToolbar />

            <MobileToolbar />

            <!--Google Adsense banner-->
            <div v-if="config.allowedAdsense && config.adsenseBanner01" v-html="config.adsenseBanner01" class="mb-5 min-h-[120px]"></div>

            <!--File list & info sidebar-->
            <div class="flex space-x-3 lg:overflow-hidden grow" @drop.stop.prevent="uploadDroppedItems($event)" @dragenter.prevent @dragover.prevent>
                <router-view id="file-view" class="relative w-full min-w-0" :key="$route.fullPath" />

                <InfoSidebar v-if="isVisibleSidebar" />
            </div>
        </div>
    </div>
</template>

<script>
import FileSortingMobile from '../components/Menus/FileSortingMobile'
import SidebarNavigation from '../components/Sidebar/SidebarNavigation'
import FileFilterMobile from '../components/Menus/FileFilterMobile'
import CreateFolderPopup from '../components/Popups/CreateFolderPopup'
import ProcessingPopup from '../components/Popups/ProcessingPopup'
import MobileNavigation from '../components/Mobile/MobileNavigation'
import ShareCreatePopup from '../components/Popups/ShareCreatePopup'
import DesktopToolbar from '../components/Layout/Toolbars/DesktopToolbar'
import CreateTeamFolderPopup from '../components/Teams/CreateTeamFolderPopup'
import ConfirmPopup from '../components/Popups/ConfirmPopup'
import RenameItemPopup from '../components/Popups/RenameItemPopup'
import PanelNavigationFiles from '../components/EntriesView/PanelNavigationFiles'
import MobileToolbar from '../components/Layout/Toolbars/MobileToolbar'
import ShareEditPopup from '../components/Popups/ShareEditPopup'
import FilePreview from '../components/FilePreview/FilePreview'
import MoveItemPopup from '../components/Popups/MoveItemPopup'
import EditTeamFolderPopup from '../components/Teams/EditTeamFolderPopup'
import Spotlight from '../components/Spotlight/Spotlight'
import DragUI from '../components/UI/Others/DragUI'
import InfoSidebar from '../components/Layout/Sidebars/InfoSidebar'
import { events } from '../bus'
import { mapGetters } from 'vuex'
import CreateUploadRequestPopup from "../components/UploadRequest/CreateUploadRequestPopup";
import NotificationsPopup from "../components/Notifications/NotificationsPopup";
import RemoteUploadPopup from "../components/RemoteUpload/RemoteUploadPopup";
import SaveToBlockChainPopup from "../components/Others/SaveToBlockChainPopup";
import EdBitSaveToBlockChainPopup from "../components/Others/EdBitSaveToBlockChainPopup";


export default {
    name: 'Platform',
    components: {
		RemoteUploadPopup,
		NotificationsPopup,
		CreateUploadRequestPopup,
        CreateTeamFolderPopup,
        PanelNavigationFiles,
        EditTeamFolderPopup,
        CreateFolderPopup,
        FileSortingMobile,
        SidebarNavigation,
        FileFilterMobile,
        MobileNavigation,
        ShareCreatePopup,
        ProcessingPopup,
        RenameItemPopup,
        ShareEditPopup,
        DesktopToolbar,
        MoveItemPopup,
        MobileToolbar,
        ConfirmPopup,
        InfoSidebar,
        FilePreview,
        Spotlight,
        DragUI,
        SaveToBlockChainPopup,
        EdBitSaveToBlockChainPopup,
    },
    computed: {
        ...mapGetters(['isVisibleSidebar', 'isLimitedUser', 'config', 'currentFolder', 'getAuthenticatedUserDetails']),
    },
    data() {
        return {
            isScaledDown: false,
            showSaveToBlockChainDialog: false,
            showEdBitLoginModal: false,
            edBitDialogKey: 0,
            selectedPdf: null,
        }
    },
    methods: {
		uploadDroppedItems(event) {
			this.$uploadDraggedFiles(event, this.currentFolder?.data.id)
		},
        contextMenu(event, item) {
            events.$emit('context-menu:show', event, item)
        },
      closeEdBitDialog(){
        this.showEdBitLoginModal = false
        this.edBitDialogKey++
      },
      closeDialogAndOpenEdBitLogin(){
        this.showSaveToBlockChainDialog = false
        this.showEdBitLoginModal = true
      }
    },
    mounted() {
        // TODO: new scaledown effect
        events.$on('mobile-menu:show', () => (this.isScaledDown = true))
        events.$on('mobile-menu:hide', () => (this.isScaledDown = false))

      this.$root.$on('openBlockChainPopup', (item) => {
        // if(this.getAuthenticatedUserDetails){
          this.selectedPdf = item
          this.showSaveToBlockChainDialog =  true
        // }else{
        //   this.$notify({
        //     title: 'Info',
        //     type: 'info',
        //     message: 'You need to login to perform this action!'
        //   });
        //   this.showEdBitLoginModal = true
        // }
      })
    },
}
</script>


<style>
::v-deep .el-dialog__header{
  background: blue;
  display: none;
}
</style>
