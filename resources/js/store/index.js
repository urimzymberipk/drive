import Vuex from 'vuex'
import Vue from 'vue'

import authenticatedUser from './modules/authenticatedUser'
import uploadRequest from './modules/uploadRequest'
import fileFunctions from './modules/fileFunctions'
import broadcasting from './modules/broadcasting'
import fileBrowser from './modules/fileBrowser'
import payments from './modules/payments'
import userAuth from './modules/userAuth'
import sharing from './modules/sharing'
import lists from './modules/lists'
import teams from './modules/teams'
import app from './modules/app'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        authenticatedUser,
        uploadRequest,
        fileFunctions,
        broadcasting,
        fileBrowser,
        payments,
        userAuth,
        sharing,
        lists,
        teams,
        app,
    },
})
