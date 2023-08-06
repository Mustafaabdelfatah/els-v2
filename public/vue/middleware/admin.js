export default function ({$auth, redirect}) {
  if (!$auth.loggedIn)
    return redirect('/login')
  else if ($auth.user.type === 'employee')
    return redirect('/employee')
}
