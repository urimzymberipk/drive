import {events} from "../../bus";

const defaultState = {
    authenticatedUserDetails: null,
}

const mutations = {
    SET_AUTHENTICATED_USER_DETAILS(state, payload) {
        state.authenticatedUserDetails = payload
    },
}

const getters = {
    getAuthenticatedUserDetails: (state) => state.authenticatedUserDetails,
}

export default {
    state: defaultState,
    getters,
    mutations,
}
