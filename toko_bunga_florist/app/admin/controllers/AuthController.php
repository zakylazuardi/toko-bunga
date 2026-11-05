
<?php

class AuthController extends Controller
{
    public function login(string $method): void
    {
        if (Auth::check()) {
            $this->redirect('admin/dashboard');
        }

        $error = null;

        if ($method === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $admin = Admin::findByUsername($username);

            if (
                $admin &&
                password_verify($password, $admin['password'])
            ) {
                Auth::login(
                    (int) $admin['admin_id'],
                    $admin['nama_admin']
                );

                ActivityLog::record(
                    'Login',
                    'Admin berhasil login ke dashboard.'
                );

                $this->redirect('admin/dashboard');
                return;
            }

            $error = 'Username atau password salah.';
        }

        $this->view('admin/views/auth/login', compact('error'));
    }

    public function logout(): void
    {
        if (Auth::check()) {
            ActivityLog::record(
                'Logout',
                'Admin keluar dari dashboard.'
            );
        }

        Auth::logout();
        $this->redirect('admin/login');
    }
}
