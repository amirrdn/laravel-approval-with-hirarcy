import { createStore } from 'vuex'

const store = createStore({
  state() {
    return {
      user: null
    }
  },
  mutations: {
    setUser(state, user) {
      state.user = user
    }
  },
  actions: {
    fetchUser({ commit }) {
      axios.get('/api/user').then(response => {
        commit('setUser', response.data)
      })
    }
  },
  getters: {
    isLoggedIn: (state) => !!state.user,
    user: (state) => state.user
  }
})

export default store
