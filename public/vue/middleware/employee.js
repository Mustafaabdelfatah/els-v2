export default function ({$auth, redirect}) {
  if (!$auth.loggedIn)
    return redirect('/login')
  else if ($auth.user.type === 'admin')
    return redirect('/')
}
