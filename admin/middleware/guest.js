export default function ({ store, redirect }) {
  // if (store.state.auth.loggedIn) {
  if (!store.state.auth.loggedIn) {
    return redirect('/login')
  }
}
